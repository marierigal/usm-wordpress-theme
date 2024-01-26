<?php
/**
 * Title: Pricing
 * Slug: usm/license-price
 * Categories: about, services
 */
?>

<!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|small","left":"var:preset|spacing|small","top":"var:preset|spacing|small","bottom":"var:preset|spacing|small"}},"layout":{"selfStretch":"fixed","flexSize":"200px"},"border":{"radius":"8px"},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base","gradient":"gradient-apple-sapphire","layout":{"type":"flex","orientation":"vertical","justifyContent":"center","flexWrap":"nowrap","verticalAlignment":"space-between"}} -->
<div
  class="wp-block-group has-base-color has-gradient-apple-sapphire-gradient-background has-text-color has-background has-link-color"
  style="border-radius:8px;padding-top:var(--wp--preset--spacing--small);padding-right:var(--wp--preset--spacing--small);padding-bottom:var(--wp--preset--spacing--small);padding-left:var(--wp--preset--spacing--small)"
>
  <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|xx-small"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center","flexWrap":"nowrap"}} -->
  <div class="wp-block-group">
    <!-- wp:heading {"level":3,"style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base"} -->
    <h3
      class="wp-block-heading has-base-color has-text-color has-link-color"
    ><?php _ex('Title', 'Pattern placeholder', 'usm') ?></h3>
    <!-- /wp:heading -->

    <!-- wp:heading {"level":4,"style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base","fontFamily":"body"} -->
    <h4
      class="wp-block-heading has-base-color has-text-color has-link-color has-body-font-family"
    ><?php _ex('Subtitle', 'Pattern placeholder', 'usm') ?></h4>
    <!-- /wp:heading -->
  </div>
  <!-- /wp:group -->

  <!-- wp:paragraph {"align":"center"} -->
  <p
    class="has-text-align-center"
  >Lorem ipsum dolor sit amet, sed dolore magna aliqua.</p>
  <!-- /wp:paragraph -->

  <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"4em","fontStyle":"normal","fontWeight":"700"}},"fontFamily":"heading"} -->
  <p
    class="has-text-align-center has-heading-font-family"
    style="font-size:4em;font-style:normal;font-weight:700"
  ><?php _ex('$100', 'Pattern placeholder', 'usm') ?></p>
  <!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
