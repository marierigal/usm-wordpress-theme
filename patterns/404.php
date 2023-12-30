<?php
/**
 * Title: 404
 * Slug: usm/404
 * Categories: hidden
 * Inserter: no
 */
?>

<!-- wp:image {"lightbox":{"enabled":false},"id":1705,"sizeSlug":"full","linkDestination":"none","style":{"color":[]},"className":"is-style-default"} -->
<figure class="wp-block-image size-full is-style-default">
  <img
    class="wp-image-1705"
    src="<?php echo ASSETS . '/images/404-illustration.png'; ?>"
    alt=""
  />
</figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":1} -->
<h1 class="wp-block-heading has-text-align-center">
  <?php _e('404 Error', 'usm'); ?>
</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">
  <?php _e('The page you are looking for does no longer exists or has been moved.', 'usm'); ?>
</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
  <!-- wp:button -->
  <div class="wp-block-button">
    <a class="wp-block-button__link wp-element-button" href="<?php echo get_home_url(); ?>">
      <?php _e('Back home', 'usm'); ?>
    </a>
  </div>
  <!-- /wp:button -->
</div>
<!-- /wp:buttons -->
