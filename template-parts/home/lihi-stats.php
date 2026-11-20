<?php
/** LiHi homepage stats parallax */
if (!defined('ABSPATH')) {
  exit;
}
$stats_bg = get_template_directory_uri() . '/images/Twilight Drone Batteries.png';
?>

<section class="lihi-stats lihi-parallax-container" id="lihiStats" data-lihi-parallax-container
  style="--stats-bg:url('<?php echo esc_url($stats_bg); ?>');" aria-label="Our numbers">


  <div class="lihi-px lihi-px--sky lihi-parallax-layer" data-lihi-parallax="0.28"></div>

  <div class="wrap">
    <div class="lihi-stats__grid">
      <div class="lihi-stat">

        <div>
          <div class="lihi-stat__num" data-count="100" data-suffix="%">100%</div>
          <div class="lihi-stat__label">Safety-Centric Power System</div>
        </div>
      </div>
      <div class="lihi-stat">

        <div>
          <div class="lihi-stat__num" data-count="80" data-suffix="%">80%</div>
          <div class="lihi-stat__label">Reduction in Downtime</div>
        </div>
      </div>
      <div class="lihi-stat">

        <div>
          <div class="lihi-stat__num" data-count="70" data-suffix="%">70%</div>
          <div class="lihi-stat__label">Increase in Battery Lifespan</div>
        </div>
      </div>
      <div class="lihi-stat">

        <div>
          <div class="lihi-stat__num" data-count="60" data-suffix="%">60%</div>
          <div class="lihi-stat__label">Lower Carbon Footprint</div>
        </div>
      </div>

    </div>

  </div>
</section>