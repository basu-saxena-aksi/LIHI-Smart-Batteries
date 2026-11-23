<?php


$integ_icons = [
    'understand' => '<circle cx="9" cy="8" r="3"/><path d="M2 20a6 6 0 0 1 12 0"/><circle cx="18" cy="9" r="2.4"/><path d="M14.5 20a4.2 4.2 0 0 1 8.5 0"/>',
    'design' => '<path d="m15 5 4 4"/><path d="m4 20 4.5-1 10-10a2.8 2.8 0 0 0-4-4l-10 10L4 20Z"/><path d="m13.5 6.5 4 4"/>',
    'build' => '<path d="M12 2v3 M12 19v3"/><path d="m4.9 4.9 2.1 2.1 M17 17l2.1 2.1"/><path d="M2 12h3 M19 12h3"/><path d="m4.9 19.1 2.1-2.1 M17 7l2.1-2.1"/><circle cx="12" cy="12" r="5"/>',
    'test' => '<rect x="4" y="3" width="16" height="18" rx="2"/><path d="m8 11 2.5 2.5L16 8"/>',
    'deliver' => '<path d="M3 7h11v11H3z"/><path d="M14 11h4l3 3v4h-7z"/><circle cx="7.5" cy="19" r="2"/><circle cx="17.5" cy="19" r="2"/>',
    'support' => '<path d="M3 13v-2a9 9 0 0 1 18 0v2"/><path d="M3 13h3v6H5a2 2 0 0 1-2-2z"/><path d="M21 13h-3v6h1a2 2 0 0 0 2-2z"/><path d="M18 19a6 6 0 0 1-6 3"/>',
];

$integ_steps = [
    // label,       icon,         side,   anchor x, centre y, dot s, path
    [
        'Understand',
        'understand',
        'left',
        355,
        187,
        3,
        'M456 243.5H420A10 10 0 0 1 410 233.5V197.5A10 10 0 0 0 400 187.5H367'
    ],
    [
        'Design',
        'design',
        'left',
        328,
        267,
        7,
        'M456 267.5H339'
    ],
    [
        'Build',
        'build',
        'left',
        355,
        348,
        7,
        'M456 291.5H420A10 10 0 0 0 410 301.5V337.5A10 10 0 0 1 400 347.5H367'
    ],
    [
        'Test',
        'test',
        'right',
        667,
        187,
        7,
        'M566 243.5H600A10 10 0 0 0 610 233.5V197.5A10 10 0 0 1 620 187.5H654'
    ],
    [
        'Deliver',
        'deliver',
        'right',
        694,
        267,
        6,
        'M566 267.5H681'
    ],
    [
        'Support',
        'support',
        'right',
        666,
        348,
        6,
        'M566 291.5H600A10 10 0 0 1 610 301.5V337.5A10 10 0 0 0 620 347.5H654'
    ],
];
?>
<section class="integ" id="integrations">
    <div class="integ__stage">

        <!-- animated colour glow behind the diagram -->
        <span class="integ__glow integ__glow--a" aria-hidden="true"></span>
        <span class="integ__glow integ__glow--b" aria-hidden="true"></span>



        <h2 class="integ__title">OUR<br>PROCESS</h2>

        <!-- centre card -->
        <div class="integ__card" aria-hidden="true">
            <svg viewBox="0 0 80 70" width="80" height="70" fill="none" stroke="rgba(255,255,255,.75)" stroke-width="1"
                stroke-linecap="round">
                <rect x="17" y="8" width="37" height="25" rx="7" />
                <rect x="17" y="37" width="25" height="25" rx="7" />
                <path d="M54 20 H63" />
                <path d="M29 33 V37" />
            </svg>
        </div>

        <!-- static connector lines -->
        <svg class="integ__lines" viewBox="0 0 1000 425" width="1000" height="425" aria-hidden="true">
            <?php foreach ($integ_steps as $s): ?>
                <path d="<?= $s[6] ?>" />
            <?php endforeach; ?>
        </svg>

        <!-- moving dots (card -> step) -->
        <?php foreach ($integ_steps as $s): ?>
            <i class="integ__dot" aria-hidden="true"
                style="offset-path:path('<?= $s[6] ?>');animation-duration:<?= $s[5] ?>s"></i>
        <?php endforeach; ?>

        <!-- icon + label rows -->
        <?php foreach ($integ_steps as $s):
            [$label, $icon, $side, $ax, $cy] = $s;
            $pos = $side === 'left' ? 'right:' . (1000 - $ax) . 'px' : 'left:' . $ax . 'px';
            $svg = '<span class="integ__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $integ_icons[$icon] . '</svg></span>';
            $txt = '<span class="integ__label">' . htmlspecialchars($label) . '</span>'; ?>
            <div class="integ__item integ__item--<?= $side ?>" style="<?= $pos ?>;top:<?= $cy - 18 ?>px">
                <?= $side === 'left' ? $svg . $txt : $txt . $svg ?>
            </div>
        <?php endforeach; ?>

    </div>
    <p class="integ__note">Can’t see your favorite platform? Let us know by submitting a ticket.</p>
</section>