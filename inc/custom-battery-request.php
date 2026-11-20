<?php

if (!defined('ABSPATH')) {
    exit;
}

function lihi_cbr_options()
{
    return [
        'industry' => [
            'Drones & UAVs',
            'Defense',
            'Telecom',
            'Energy',
            'Agriculture',
            'Industrial',
            'Logistics',
            'Other',
        ],
        'chemistry' => [
            'Lithium-ion',
            'Lithium Polymer (LiPo)',
            'LiFePO4',
            'Other',
            'Not Sure',
        ],
    ];
}

function lihi_cbr_create_request_token()
{
    $token = wp_generate_password(40, false, false);
    set_transient('lihi_cbr_' . hash('sha256', $token), 1, HOUR_IN_SECONDS);

    return $token;
}

function lihi_cbr_enqueue_script()
{
    if (!is_page('custom-battery-request') && !is_page_template('page-custom-battery-request.php')) {
        return;
    }

    $script_path = get_template_directory() . '/js/custom-battery-request.js';

    wp_enqueue_script(
        'lihi-custom-battery-request',
        get_template_directory_uri() . '/js/custom-battery-request.js',
        [],
        filemtime($script_path),
        true
    );
}
add_action('wp_enqueue_scripts', 'lihi_cbr_enqueue_script');

function lihi_cbr_process_submission()
{
    if (
        (!is_page('custom-battery-request') && !is_page_template('page-custom-battery-request.php')) ||
        'POST' !== strtoupper(isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '') ||
        !isset($_POST['cbr_submit'])
    ) {
        return;
    }

    $state = [
        'status' => 'error',
        'message' => '',
        'values' => [],
        'errors' => [],
    ];

    $posted_fields = isset($_POST['cbr']) && is_array($_POST['cbr']) ? wp_unslash($_POST['cbr']) : [];
    $text_fields = [
        'full_name',
        'company',
        'email',
        'phone',
        'country',
        'industry',
        'application',
        'chemistry',
        'voltage',
        'capacity',
        'discharge_rate',
        'dimensions',
        'maximum_weight',
        'quantity',
        'connector',
    ];

    foreach ($text_fields as $field) {
        $value = isset($posted_fields[$field]) && is_scalar($posted_fields[$field])
            ? (string) $posted_fields[$field]
            : '';
        $state['values'][$field] = sanitize_text_field($value);
    }

    $requirements = isset($posted_fields['requirements']) && is_scalar($posted_fields['requirements'])
        ? (string) $posted_fields['requirements']
        : '';
    $state['values']['requirements'] = sanitize_textarea_field($requirements);
    $state['values']['not_sure'] = isset($posted_fields['not_sure']) &&
        is_scalar($posted_fields['not_sure']) &&
        (string) $posted_fields['not_sure'] === '1';

    $nonce = isset($_POST['cbr_nonce']) && is_scalar($_POST['cbr_nonce'])
        ? sanitize_text_field(wp_unslash($_POST['cbr_nonce']))
        : '';

    if (!$nonce || !wp_verify_nonce($nonce, 'lihi_custom_battery_request')) {
        $state['message'] = 'Your form session expired. Please review your details and submit again.';
        $GLOBALS['lihi_custom_battery_request_state'] = $state;
        return;
    }

    $request_token = isset($_POST['cbr_request_token']) && is_scalar($_POST['cbr_request_token'])
        ? sanitize_text_field(wp_unslash($_POST['cbr_request_token']))
        : '';
    $transient_key = 'lihi_cbr_' . hash('sha256', $request_token);

    if (
        !$request_token ||
        '1' !== (string) get_transient($transient_key)
    ) {
        $state['message'] = 'This form has already been submitted or expired. Please review your details and submit again.';
        $GLOBALS['lihi_custom_battery_request_state'] = $state;
        return;
    }

    if (!delete_transient($transient_key)) {
        $state['message'] = 'This form has already been submitted or expired. Please review your details and submit again.';
        $GLOBALS['lihi_custom_battery_request_state'] = $state;
        return;
    }

    $honeypot = isset($_POST['cbr_website']) && is_scalar($_POST['cbr_website'])
        ? sanitize_text_field(wp_unslash($_POST['cbr_website']))
        : '';

    if ($honeypot !== '') {
        $state['status'] = 'success';
        $state['message'] = 'Thank you. Your custom battery request has been received.';
        $state['values'] = [];
        $GLOBALS['lihi_custom_battery_request_state'] = $state;
        return;
    }

    $required_fields = [
        'full_name' => 'Please enter your full name.',
        'email' => 'Please enter your business email.',
        'phone' => 'Please enter your phone number.',
        'country' => 'Please enter your country.',
        'industry' => 'Please select an industry.',
        'application' => 'Please describe the application or end use.',
        'requirements' => 'Please describe your additional requirements.',
    ];

    foreach ($required_fields as $field => $message) {
        if ($state['values'][$field] === '') {
            $state['errors'][$field] = $message;
        }
    }

    if (
        $state['values']['email'] !== '' &&
        !is_email($state['values']['email'])
    ) {
        $state['errors']['email'] = 'Please enter a valid email address.';
    }

    if (
        $state['values']['phone'] !== '' &&
        (
            !preg_match('/^[0-9+().\-\s]{7,30}$/', $state['values']['phone']) ||
            strlen(preg_replace('/[^0-9]/', '', $state['values']['phone'])) < 7
        )
    ) {
        $state['errors']['phone'] = 'Please enter a valid phone number, including its country code if applicable.';
    }

    $options = lihi_cbr_options();
    if (
        $state['values']['industry'] !== '' &&
        !in_array($state['values']['industry'], $options['industry'], true)
    ) {
        $state['errors']['industry'] = 'Please select an industry from the list.';
    }

    if (
        $state['values']['chemistry'] !== '' &&
        !in_array($state['values']['chemistry'], $options['chemistry'], true)
    ) {
        $state['errors']['chemistry'] = 'Please select a battery chemistry from the list.';
    }

    if ($state['values']['quantity'] !== '') {
        $quantity = filter_var(
            $state['values']['quantity'],
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        if ($quantity === false) {
            $state['errors']['quantity'] = 'Enter a whole quantity of 1 or more.';
        }
    }

    if (strlen($state['values']['requirements']) > 5000) {
        $state['errors']['requirements'] = 'Please keep the description under 5,000 characters.';
    }

    foreach (['full_name', 'company', 'country', 'application', 'voltage', 'capacity', 'discharge_rate', 'dimensions', 'maximum_weight', 'connector'] as $field) {
        if (strlen($state['values'][$field]) > 200) {
            $state['errors'][$field] = 'Please keep this field under 200 characters.';
        }
    }

    if ($state['errors']) {
        $state['message'] = 'Please review the highlighted fields and try again.';
        $GLOBALS['lihi_custom_battery_request_state'] = $state;
        return;
    }

    $recipient = apply_filters(
        'lihi_custom_battery_request_recipient',
        'business@aksiaerospace.com'
    );

    if (!is_string($recipient) || !is_email($recipient)) {
        error_log('LiHi custom battery request recipient is not a valid email address.');
        $state['message'] = 'We could not process your request right now. Please try again later.';
        $GLOBALS['lihi_custom_battery_request_state'] = $state;
        return;
    }

    $file = isset($_FILES['cbr_file']) && is_array($_FILES['cbr_file'])
        ? $_FILES['cbr_file']
        : [];
    $file_error = isset($file['error']) ? (int) $file['error'] : UPLOAD_ERR_NO_FILE;
    $attachment_path = '';
    $attachment_name = '';

    if ($file_error !== UPLOAD_ERR_NO_FILE) {
        if ($file_error !== UPLOAD_ERR_OK || empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            $state['errors']['file'] = 'The file could not be uploaded. Please try again with a file under 10 MB.';
        } else {
            $file_size = filesize($file['tmp_name']);
            if ($file_size === false || $file_size > 10 * MB_IN_BYTES) {
                $state['errors']['file'] = 'The file must be no larger than 10 MB.';
            } else {
                $allowed_mimes = [
                    'pdf' => 'application/pdf',
                    'jpg|jpeg' => 'image/jpeg',
                    'png' => 'image/png',
                    'doc' => 'application/msword',
                    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'xls' => 'application/vnd.ms-excel',
                    'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ];
                $filename = isset($file['name']) ? sanitize_file_name($file['name']) : '';
                $checked_file = wp_check_filetype_and_ext($file['tmp_name'], $filename, $allowed_mimes);
                $extension = isset($checked_file['ext']) ? strtolower($checked_file['ext']) : '';
                $mime = isset($checked_file['type']) ? $checked_file['type'] : '';
                $accepted_types = array_values($allowed_mimes);

                if (!$extension || !in_array($mime, $accepted_types, true)) {
                    $state['errors']['file'] = 'Choose a PDF, JPG, JPEG, PNG, DOC, DOCX, XLS, or XLSX file.';
                } else {
                    $upload = wp_handle_upload($file, [
                        'test_form' => false,
                        'mimes' => $allowed_mimes,
                    ]);

                    if (!empty($upload['error']) || empty($upload['file'])) {
                        $state['errors']['file'] = 'The file could not be saved. Please try uploading it again.';
                    } else {
                        $attachment_path = $upload['file'];
                        $attachment_name = basename($upload['file']);
                    }
                }
            }
        }
    }

    if ($state['errors']) {
        $state['message'] = 'Please review the highlighted fields and try again.';
        $GLOBALS['lihi_custom_battery_request_state'] = $state;
        return;
    }

    $labels = [
        'full_name' => 'Full Name',
        'company' => 'Company Name',
        'email' => 'Business Email',
        'phone' => 'Phone Number',
        'country' => 'Country',
        'industry' => 'Industry',
        'application' => 'Application / End Use',
        'chemistry' => 'Battery Chemistry',
        'voltage' => 'Nominal Voltage (V)',
        'capacity' => 'Capacity (mAh / Ah)',
        'discharge_rate' => 'Discharge Rate (C)',
        'dimensions' => 'Dimensions (L x W x H)',
        'maximum_weight' => 'Maximum Weight',
        'quantity' => 'Required Quantity',
        'connector' => 'Connector / Terminal Requirements',
        'requirements' => 'Additional Requirements / Description',
    ];
    $body_lines = [];

    foreach ($labels as $field => $label) {
        $body_lines[] = $label . ': ' . ($state['values'][$field] !== '' ? $state['values'][$field] : 'Not provided');
    }

    $body_lines[] = 'Technical specifications assistance requested: ' . ($state['values']['not_sure'] ? 'Yes' : 'No');
    $body_lines[] = 'Reference file: ' . ($attachment_name !== '' ? $attachment_name : 'None');

    $attachments = $attachment_path !== '' ? [$attachment_path] : [];
    $subject = 'Custom Battery Request - ' . $state['values']['full_name'];
    $headers = ['Reply-To: ' . $state['values']['email']];

    try {
        $sent = wp_mail($recipient, $subject, implode("\n", $body_lines), $headers, $attachments);
    } finally {
        if ($attachment_path !== '' && file_exists($attachment_path)) {
            unlink($attachment_path);
        }
    }

    if (!$sent) {
        error_log('LiHi custom battery request email delivery failed.');
        $state['message'] = 'We could not send your request right now. Your details are still here; please try again later. If you attached a file, please select it again.';
        $GLOBALS['lihi_custom_battery_request_state'] = $state;
        return;
    }

    $state['status'] = 'success';
    $state['message'] = 'Thank you. Your custom battery request has been received. Our team will review your requirements and get in touch.';
    $state['values'] = [];
    $state['errors'] = [];
    $GLOBALS['lihi_custom_battery_request_state'] = $state;
}
add_action('template_redirect', 'lihi_cbr_process_submission');
