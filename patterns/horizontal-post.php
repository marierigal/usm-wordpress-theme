<?php
/**
 * Title: Horizontal Post
 * Slug: usm/horizontal-post
 * Categories: posts
 */
?>

<!-- wp:group {"tagName":"article","layout":{"type":"default"}} -->
<article class="wp-block-group">
  <!-- wp:columns {"verticalAlignment":"center"} -->
  <div class="wp-block-columns are-vertically-aligned-center">
    <!-- wp:column {"verticalAlignment":"center"} -->
    <div class="wp-block-column is-vertically-aligned-center">
      <!-- wp:post-featured-image {"isLink":true,"style":{"color":{"duotone":"unset"}}} /-->
    </div>
    <!-- /wp:column -->

    <!-- wp:column {"verticalAlignment":"center","style":{"spacing":{"blockGap":"var:preset|spacing|small"}}} -->
    <div class="wp-block-column is-vertically-aligned-center">
      <!-- wp:post-title {"level":3,"isLink":true,"style":{"elements":{"link":{"color":{"text":"var:preset|color|primary"}}}},"textColor":"primary","fontSize":"xx-large"} /-->

      <!-- wp:post-date {"format":"j M Y","style":{"elements":{"link":{"color":{"text":"var:preset|color|gray-dark"}}},"typography":{"textTransform":"uppercase"}},"textColor":"gray-dark","fontSize":"small"} /-->

      <!-- wp:post-excerpt {"moreText":"Lire la suite →"} /-->
    </div>
    <!-- /wp:column -->
  </div>
  <!-- /wp:columns -->
</article>
<!-- /wp:group -->
