<?php
/*
 * Template Name: Custom Battery Request
 * Template Post Type: page
 */

$cbr_state = isset($GLOBALS['lihi_custom_battery_request_state'])
    ? $GLOBALS['lihi_custom_battery_request_state']
    : [
        'status' => '',
        'message' => '',
        'values' => [],
        'errors' => [],
    ];
$cbr_values = $cbr_state['values'];
$cbr_errors = $cbr_state['errors'];

get_header();
?>

<main class="cbr-page">
    <?php
    get_template_part('components/parallax-hero', null, [
        'eyebrow' => 'CUSTOM BATTERY SOLUTIONS',
        'heading' => 'CUSTOM POWER.',
        'highlight' => 'BUILT AROUND YOUR NEEDS.',
        'description' => 'Tell us your battery requirements. Our engineering team will work with you to develop a power solution tailored to your application.',
        'background' => 'battery-bg.jpg',
    ]);
    ?>

    <section class="cbr-intro">
        <div class="cbr-wrap">
            <span class="cbr-eyebrow">LET'S BUILD YOUR BATTERY SOLUTION</span>
            <h2>Tell Us What You Need</h2>
            <p>Share your application and battery requirements with us. Our team will review your request and get in touch to discuss the right power solution.</p>
        </div>
    </section>

    <section class="cbr-request-section" aria-labelledby="cbr-form-title">
        <div class="cbr-wrap">
            <div class="cbr-form-heading">
                <h2 id="cbr-form-title">Custom Battery Request</h2>
                <p>Fields marked <span class="cbr-required-mark" aria-hidden="true">*</span> are required.</p>
            </div>

            <?php if (!empty($cbr_state['message'])) : ?>
                <div class="cbr-notice cbr-notice--<?php echo esc_attr($cbr_state['status']); ?>" role="<?php echo $cbr_state['status'] === 'success' ? 'status' : 'alert'; ?>" aria-live="polite">
                    <?php echo esc_html($cbr_state['message']); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($cbr_errors)) : ?>
                <div class="cbr-error-summary" role="alert">
                    <p>Please check the following:</p>
                    <ul>
                        <?php foreach ($cbr_errors as $error) : ?>
                            <li><?php echo esc_html($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form class="cbr-form" method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('lihi_custom_battery_request', 'cbr_nonce'); ?>
                <input type="hidden" name="cbr_submit" value="1">
                <input type="hidden" name="cbr_request_token" value="<?php echo esc_attr(lihi_cbr_create_request_token()); ?>">

                <div class="cbr-honeypot" aria-hidden="true">
                    <label for="cbr-website">Leave this field empty</label>
                    <input type="text" id="cbr-website" name="cbr_website" value="" tabindex="-1" autocomplete="off">
                </div>

                <fieldset class="cbr-form-group">
                    <legend><span class="cbr-section-number">01</span> Contact Information</legend>
                    <div class="cbr-fields">
                        <div class="cbr-field<?php echo isset($cbr_errors['full_name']) ? ' cbr-field--error' : ''; ?>">
                            <label for="cbr-full-name">Full Name <span class="cbr-required-mark" aria-hidden="true">*</span></label>
                            <input type="text" id="cbr-full-name" name="cbr[full_name]" value="<?php echo esc_attr(isset($cbr_values['full_name']) ? $cbr_values['full_name'] : ''); ?>" placeholder="e.g. Alex Morgan" autocomplete="name" maxlength="200" required aria-invalid="<?php echo isset($cbr_errors['full_name']) ? 'true' : 'false'; ?>">
                            <?php if (isset($cbr_errors['full_name'])) : ?><span class="cbr-field-error"><?php echo esc_html($cbr_errors['full_name']); ?></span><?php endif; ?>
                        </div>
                        <div class="cbr-field">
                            <label for="cbr-company">Company Name <span class="cbr-optional">(optional)</span></label>
                            <input type="text" id="cbr-company" name="cbr[company]" value="<?php echo esc_attr(isset($cbr_values['company']) ? $cbr_values['company'] : ''); ?>" placeholder="Your company" autocomplete="organization" maxlength="200">
                        </div>
                        <div class="cbr-field<?php echo isset($cbr_errors['email']) ? ' cbr-field--error' : ''; ?>">
                            <label for="cbr-email">Business Email <span class="cbr-required-mark" aria-hidden="true">*</span></label>
                            <input type="email" id="cbr-email" name="cbr[email]" value="<?php echo esc_attr(isset($cbr_values['email']) ? $cbr_values['email'] : ''); ?>" placeholder="you@company.com" autocomplete="email" maxlength="254" required aria-invalid="<?php echo isset($cbr_errors['email']) ? 'true' : 'false'; ?>">
                            <?php if (isset($cbr_errors['email'])) : ?><span class="cbr-field-error"><?php echo esc_html($cbr_errors['email']); ?></span><?php endif; ?>
                        </div>
                        <div class="cbr-field<?php echo isset($cbr_errors['phone']) ? ' cbr-field--error' : ''; ?>">
                            <label for="cbr-phone">Phone Number <span class="cbr-required-mark" aria-hidden="true">*</span></label>
                            <input type="tel" id="cbr-phone" name="cbr[phone]" value="<?php echo esc_attr(isset($cbr_values['phone']) ? $cbr_values['phone'] : ''); ?>" placeholder="Include country code if applicable" autocomplete="tel" maxlength="30" required aria-invalid="<?php echo isset($cbr_errors['phone']) ? 'true' : 'false'; ?>">
                            <?php if (isset($cbr_errors['phone'])) : ?><span class="cbr-field-error"><?php echo esc_html($cbr_errors['phone']); ?></span><?php endif; ?>
                        </div>
                        <div class="cbr-field<?php echo isset($cbr_errors['country']) ? ' cbr-field--error' : ''; ?>">
                            <label for="cbr-country">Country <span class="cbr-required-mark" aria-hidden="true">*</span></label>
                            <input type="text" id="cbr-country" name="cbr[country]" value="<?php echo esc_attr(isset($cbr_values['country']) ? $cbr_values['country'] : ''); ?>" placeholder="Your country" autocomplete="country-name" maxlength="200" required aria-invalid="<?php echo isset($cbr_errors['country']) ? 'true' : 'false'; ?>">
                            <?php if (isset($cbr_errors['country'])) : ?><span class="cbr-field-error"><?php echo esc_html($cbr_errors['country']); ?></span><?php endif; ?>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="cbr-form-group">
                    <legend><span class="cbr-section-number">02</span> Application Details</legend>
                    <div class="cbr-fields">
                        <div class="cbr-field<?php echo isset($cbr_errors['industry']) ? ' cbr-field--error' : ''; ?>">
                            <label for="cbr-industry">Industry <span class="cbr-required-mark" aria-hidden="true">*</span></label>
                            <select id="cbr-industry" name="cbr[industry]" required aria-invalid="<?php echo isset($cbr_errors['industry']) ? 'true' : 'false'; ?>">
                                <option value="">Select an industry</option>
                                <?php foreach (lihi_cbr_options()['industry'] as $industry) : ?>
                                    <option value="<?php echo esc_attr($industry); ?>"<?php selected(isset($cbr_values['industry']) ? $cbr_values['industry'] : '', $industry); ?>><?php echo esc_html($industry); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($cbr_errors['industry'])) : ?><span class="cbr-field-error"><?php echo esc_html($cbr_errors['industry']); ?></span><?php endif; ?>
                        </div>
                        <div class="cbr-field<?php echo isset($cbr_errors['application']) ? ' cbr-field--error' : ''; ?>">
                            <label for="cbr-application">Application / End Use <span class="cbr-required-mark" aria-hidden="true">*</span></label>
                            <input type="text" id="cbr-application" name="cbr[application]" value="<?php echo esc_attr(isset($cbr_values['application']) ? $cbr_values['application'] : ''); ?>" placeholder="How will the battery be used?" maxlength="200" required aria-invalid="<?php echo isset($cbr_errors['application']) ? 'true' : 'false'; ?>">
                            <?php if (isset($cbr_errors['application'])) : ?><span class="cbr-field-error"><?php echo esc_html($cbr_errors['application']); ?></span><?php endif; ?>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="cbr-form-group">
                    <legend><span class="cbr-section-number">03</span> Battery Requirements</legend>
                    <div class="cbr-fields">
                        <div class="cbr-field<?php echo isset($cbr_errors['chemistry']) ? ' cbr-field--error' : ''; ?>">
                            <label for="cbr-chemistry">Battery Chemistry <span class="cbr-optional">(optional)</span></label>
                            <select id="cbr-chemistry" name="cbr[chemistry]" aria-invalid="<?php echo isset($cbr_errors['chemistry']) ? 'true' : 'false'; ?>">
                                <option value="">Select if known</option>
                                <?php foreach (lihi_cbr_options()['chemistry'] as $chemistry) : ?>
                                    <option value="<?php echo esc_attr($chemistry); ?>"<?php selected(isset($cbr_values['chemistry']) ? $cbr_values['chemistry'] : '', $chemistry); ?>><?php echo esc_html($chemistry); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($cbr_errors['chemistry'])) : ?><span class="cbr-field-error"><?php echo esc_html($cbr_errors['chemistry']); ?></span><?php endif; ?>
                        </div>
                        <div class="cbr-field">
                            <label for="cbr-voltage">Nominal Voltage (V) <span class="cbr-optional">(optional)</span></label>
                            <input type="text" id="cbr-voltage" name="cbr[voltage]" value="<?php echo esc_attr(isset($cbr_values['voltage']) ? $cbr_values['voltage'] : ''); ?>" placeholder="e.g. 12.8 V" maxlength="200">
                        </div>
                        <div class="cbr-field">
                            <label for="cbr-capacity">Capacity (mAh / Ah) <span class="cbr-optional">(optional)</span></label>
                            <input type="text" id="cbr-capacity" name="cbr[capacity]" value="<?php echo esc_attr(isset($cbr_values['capacity']) ? $cbr_values['capacity'] : ''); ?>" placeholder="e.g. 5,000 mAh or 5 Ah" maxlength="200">
                        </div>
                        <div class="cbr-field">
                            <label for="cbr-discharge-rate">Discharge Rate (C) <span class="cbr-optional">(optional)</span></label>
                            <input type="text" id="cbr-discharge-rate" name="cbr[discharge_rate]" value="<?php echo esc_attr(isset($cbr_values['discharge_rate']) ? $cbr_values['discharge_rate'] : ''); ?>" placeholder="e.g. 10C" maxlength="200">
                        </div>
                        <div class="cbr-field">
                            <label for="cbr-dimensions">Dimensions (L × W × H) <span class="cbr-optional">(optional)</span></label>
                            <input type="text" id="cbr-dimensions" name="cbr[dimensions]" value="<?php echo esc_attr(isset($cbr_values['dimensions']) ? $cbr_values['dimensions'] : ''); ?>" placeholder="Include units, e.g. 100 × 50 × 30 mm" maxlength="200">
                        </div>
                        <div class="cbr-field">
                            <label for="cbr-maximum-weight">Maximum Weight <span class="cbr-optional">(optional)</span></label>
                            <input type="text" id="cbr-maximum-weight" name="cbr[maximum_weight]" value="<?php echo esc_attr(isset($cbr_values['maximum_weight']) ? $cbr_values['maximum_weight'] : ''); ?>" placeholder="Include units, e.g. 2 kg" maxlength="200">
                        </div>
                        <div class="cbr-field<?php echo isset($cbr_errors['quantity']) ? ' cbr-field--error' : ''; ?>">
                            <label for="cbr-quantity">Required Quantity <span class="cbr-optional">(optional)</span></label>
                            <input type="number" id="cbr-quantity" name="cbr[quantity]" value="<?php echo esc_attr(isset($cbr_values['quantity']) ? $cbr_values['quantity'] : ''); ?>" placeholder="e.g. 100" min="1" step="1" inputmode="numeric" aria-invalid="<?php echo isset($cbr_errors['quantity']) ? 'true' : 'false'; ?>">
                            <?php if (isset($cbr_errors['quantity'])) : ?><span class="cbr-field-error"><?php echo esc_html($cbr_errors['quantity']); ?></span><?php endif; ?>
                        </div>
                        <div class="cbr-field">
                            <label for="cbr-connector">Connector / Terminal Requirements <span class="cbr-optional">(optional)</span></label>
                            <input type="text" id="cbr-connector" name="cbr[connector]" value="<?php echo esc_attr(isset($cbr_values['connector']) ? $cbr_values['connector'] : ''); ?>" placeholder="Connector type, polarity, or terminal details" maxlength="200">
                        </div>
                    </div>
                </fieldset>

                <fieldset class="cbr-form-group">
                    <legend><span class="cbr-section-number">04</span> Additional Information</legend>
                    <div class="cbr-fields">
                        <div class="cbr-field cbr-field--full<?php echo isset($cbr_errors['requirements']) ? ' cbr-field--error' : ''; ?>">
                            <label for="cbr-requirements">Additional Requirements / Description <span class="cbr-required-mark" aria-hidden="true">*</span></label>
                            <textarea id="cbr-requirements" name="cbr[requirements]" rows="6" placeholder="Tell us about performance needs, operating conditions, certifications, or anything else we should know." maxlength="5000" required aria-invalid="<?php echo isset($cbr_errors['requirements']) ? 'true' : 'false'; ?>"><?php echo esc_textarea(isset($cbr_values['requirements']) ? $cbr_values['requirements'] : ''); ?></textarea>
                            <?php if (isset($cbr_errors['requirements'])) : ?><span class="cbr-field-error"><?php echo esc_html($cbr_errors['requirements']); ?></span><?php endif; ?>
                        </div>
                        <div class="cbr-field cbr-field--full<?php echo isset($cbr_errors['file']) ? ' cbr-field--error' : ''; ?>">
                            <label for="cbr-file">Upload Technical Specifications / Reference File <span class="cbr-optional">(optional)</span></label>
                            <input class="cbr-file-input" type="file" id="cbr-file" name="cbr_file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx" aria-invalid="<?php echo isset($cbr_errors['file']) ? 'true' : 'false'; ?>">
                            <span class="cbr-upload-help">Accepted formats: PDF, JPG, JPEG, PNG, DOC, DOCX, XLS, XLSX. Maximum size: 10 MB.</span>
                            <?php if (isset($cbr_errors['file'])) : ?><span class="cbr-field-error"><?php echo esc_html($cbr_errors['file']); ?></span><?php endif; ?>
                        </div>
                        <div class="cbr-field cbr-field--full">
                            <label class="cbr-checkbox" for="cbr-not-sure">
                                <input type="checkbox" id="cbr-not-sure" name="cbr[not_sure]" value="1"<?php checked(!empty($cbr_values['not_sure'])); ?>>
                                <span>I'm not sure about the technical specifications. Please help me choose.</span>
                            </label>
                        </div>
                    </div>
                </fieldset>

                <div class="cbr-submit-row">
                    <button class="cbr-submit" type="submit">
                        <span>SUBMIT CUSTOM BATTERY REQUEST</span>
                        <span class="cbr-submit-arrow" aria-hidden="true">→</span>
                    </button>
                    <p>Our team will review your request and follow up with you.</p>
                </div>
            </form>
        </div>
    </section>
</main>

<?php get_footer(); ?>
