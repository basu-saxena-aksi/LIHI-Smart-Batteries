<section id="technology" class="tech-section">

  <?php
  $technology_description = get_field('technology_description');
  $technology_image_raw = get_field('technology_image');
  $technology_image = is_numeric($technology_image_raw)
      ? wp_get_attachment_image_url($technology_image_raw, 'large')
      : (is_array($technology_image_raw) ? ($technology_image_raw['url'] ?? '') : $technology_image_raw);
  $technology_button_text = get_field('technology_button_text');
  $technology_button_link = get_field('technology_button_link');

  $technology_feature_1_title = get_field('technology_feature_1_title');
  $technology_feature_1_description = get_field('technology_feature_1_description');

  $technology_feature_2_title = get_field('technology_feature_2_title');
  $technology_feature_2_description = get_field('technology_feature_2_description');

  $technology_feature_3_title = get_field('technology_feature_3_title');
  $technology_feature_3_description = get_field('technology_feature_3_description');

  $technology_feature_4_title = get_field('technology_feature_4_title');
  $technology_feature_4_description = get_field('technology_feature_4_description');
  ?>

  <div class="wrap">
    <div class="tech-alt-grid">

      <div class="tech-alt-left tech-left">
        <h2 class="home-section-title">
          ADVANCED <span class="g">TECHNOLOGY.</span><br />
          SUPERIOR PERFORMANCE.
        </h2>
        <div class="rule"></div>
        <p class="home-body-text"><?php echo esc_html($technology_description); ?></p>
        <a href="<?php echo esc_url($technology_button_link); ?>" class="btn btn-outline">
          <span><?php echo esc_html($technology_button_text); ?></span>
          <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </a>
      </div>

      <div class="tech-alt-media-wrap tech-center">
        <div class="tech-alt-media">
          <img
            src="<?php echo esc_url($technology_image); ?>"
            alt="LiHi battery and charger technology"
          />
        </div>
      </div>

      <div class="tech-feature-list tech-right">

        <div class="tech-feature-item">
          <span class="tech-feature-num">01</span>
          <div>
            <h4 class="home-card-title"><?php echo esc_html($technology_feature_1_title); ?></h4>
            <p class="home-body-text"><?php echo esc_html($technology_feature_1_description); ?></p>
          </div>
        </div>

        <div class="tech-feature-item">
          <span class="tech-feature-num">02</span>
          <div>
            <h4 class="home-card-title"><?php echo esc_html($technology_feature_2_title); ?></h4>
            <p class="home-body-text"><?php echo esc_html($technology_feature_2_description); ?></p>
          </div>
        </div>

        <div class="tech-feature-item">
          <span class="tech-feature-num">03</span>
          <div>
            <h4 class="home-card-title"><?php echo esc_html($technology_feature_3_title); ?></h4>
            <p class="home-body-text"><?php echo esc_html($technology_feature_3_description); ?></p>
          </div>
        </div>

        <div class="tech-feature-item">
          <span class="tech-feature-num">04</span>
          <div>
            <h4 class="home-card-title"><?php echo esc_html($technology_feature_4_title); ?></h4>
            <p class="home-body-text"><?php echo esc_html($technology_feature_4_description); ?></p>
          </div>
        </div>

      </div>

    </div>
  </div>
</section>