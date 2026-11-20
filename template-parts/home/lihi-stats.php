<?php
/** LiHi homepage stats parallax */
if (!defined('ABSPATH')) { exit; }
$stats_bg = get_template_directory_uri() . '/images/Twilight Drone Batteries.png';
?>

<section class="lihi-stats lihi-parallax-container"
    id="lihiStats"
    data-lihi-parallax-container
    style="--stats-bg:url('<?php echo esc_url($stats_bg); ?>');"
    aria-label="Our numbers">

  
  <div class="lihi-px lihi-px--sky lihi-parallax-layer" data-lihi-parallax="0.28"></div>

  <div class="wrap">
    <div class="lihi-stats__grid">
        <div class="lihi-stat">
      
      <div><div class="lihi-stat__num" data-count="35" data-suffix="+">35+</div><div class="lihi-stat__label">Successful Missions</div></div>
      </div>
      <div class="lihi-stat">
        
        <div><div class="lihi-stat__num" data-count="2" data-suffix="M+">2M+</div><div class="lihi-stat__label">Flight Hours Powered</div></div>
      </div>
      <div class="lihi-stat">
        
        <div><div class="lihi-stat__num" data-count="25" data-suffix="+">25+</div><div class="lihi-stat__label">Countries</div></div>
      </div>
      <div class="lihi-stat">
        
        <div><div class="lihi-stat__num" data-count="50" data-suffix="+">50+</div><div class="lihi-stat__label">Enterprise Partners</div></div>
      </div>
    </div>
    
  </div>
</section>