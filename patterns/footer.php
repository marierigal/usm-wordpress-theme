<?php
/**
 * Title: Footer
 * Slug: usm/footer
 * Categories: footer
 */
?>

<!-- wp:group {"style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}},"spacing":{"padding":{"top":"var:preset|spacing|large","bottom":"var:preset|spacing|large","left":"var:preset|spacing|small","right":"var:preset|spacing|small"},"margin":{"top":"0","bottom":"0"},"blockGap":"0"}},"backgroundColor":"main","textColor":"base","layout":{"type":"constrained"}} -->
<div
  class="wp-block-group has-base-color has-main-background-color has-text-color has-background has-link-color"
  style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--large);padding-right:var(--wp--preset--spacing--small);padding-bottom:var(--wp--preset--spacing--large);padding-left:var(--wp--preset--spacing--small)"
>
  <!-- wp:columns {"align":"wide"} -->
  <div class="wp-block-columns alignwide">
    <!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|small"}}} -->
    <div class="wp-block-column">
      <!-- wp:site-logo {"width":120,"align":"center","className":"is-style-default"} /-->

      <!-- wp:navigation {"overlayMenu":"never","layout":{"type":"flex","justifyContent":"center"}} /-->
    </div>
    <!-- /wp:column -->

    <!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|small"}}} -->
    <div class="wp-block-column">
      <!-- wp:heading -->
      <h2 class="wp-block-heading">Footer 1</h2>
      <!-- /wp:heading -->

      <!-- wp:navigation {"overlayMenu":"never","layout":{"type":"flex","orientation":"vertical","flexWrap":"nowrap"},"style":{"spacing":{"blockGap":"var:preset|spacing|xx-small"}}} /-->
    </div>
    <!-- /wp:column -->

    <!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|small"}}} -->
    <div class="wp-block-column">
      <!-- wp:heading -->
      <h2 class="wp-block-heading">Footer 2</h2>
      <!-- /wp:heading -->

      <!-- wp:navigation {"overlayMenu":"never","layout":{"type":"flex","orientation":"vertical","flexWrap":"nowrap"},"style":{"spacing":{"blockGap":"var:preset|spacing|xx-small"}}} /-->
    </div>
    <!-- /wp:column -->

    <!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|small"}}} -->
    <div class="wp-block-column">
      <!-- wp:heading -->
      <h2 class="wp-block-heading">Footer 3</h2>
      <!-- /wp:heading -->

      <!-- wp:navigation {"overlayMenu":"never","layout":{"type":"flex","orientation":"vertical","flexWrap":"nowrap"},"style":{"spacing":{"blockGap":"var:preset|spacing|xx-small"}}} /-->
    </div>
    <!-- /wp:column -->
  </div>
  <!-- /wp:columns -->
</div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}},"spacing":{"padding":{"top":"var:preset|spacing|small","bottom":"var:preset|spacing|small","left":"var:preset|spacing|small","right":"var:preset|spacing|small"},"margin":{"top":"0","bottom":"0"},"blockGap":"0"}},"backgroundColor":"gray-dark","textColor":"base","layout":{"type":"constrained"}} -->
<div
  class="wp-block-group alignwide has-base-color has-gray-dark-background-color has-text-color has-background has-link-color"
  style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--small);padding-right:var(--wp--preset--spacing--small);padding-bottom:var(--wp--preset--spacing--small);padding-left:var(--wp--preset--spacing--small)"
>
  <!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|small"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
  <div class="wp-block-group alignwide">
    <!-- wp:paragraph -->
    <p>© <?php echo date('Y') ?> <strong><?php echo get_bloginfo('name') ?></strong>, tous droits réservés</p>
    <!-- /wp:paragraph -->

    <!-- wp:buttons -->
    <div class="wp-block-buttons">
      <!-- wp:button {"style":{"spacing":{"padding":{"left":"var:preset|spacing|small","right":"var:preset|spacing|small","top":"var:preset|spacing|xx-small","bottom":"var:preset|spacing|xx-small"}}},"className":"is-style-outline","fontSize":"large"} -->
      <div class="wp-block-button has-custom-font-size is-style-outline has-large-font-size">
        <a
          class="wp-block-button__link wp-element-button" href="#"
          style="padding-top:var(--wp--preset--spacing--xx-small);padding-right:var(--wp--preset--spacing--small);padding-bottom:var(--wp--preset--spacing--xx-small);padding-left:var(--wp--preset--spacing--small)"
        >⊼</a>
      </div>
      <!-- /wp:button -->
    </div>
    <!-- /wp:buttons -->
  </div>
  <!-- /wp:group -->
</div>
<!-- /wp:group -->
