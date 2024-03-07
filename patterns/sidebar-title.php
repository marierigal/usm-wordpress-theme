<?php
/**
 * Title: Sidebar Title
 * Slug: usm/sidebar-title
 * Categories: sidebar, text
 */
?>
<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group">
  <!-- wp:heading {"style":{"elements":{"link":{"color":{"text":"var:preset|color|gray-dark"}}}},"textColor":"gray-dark"} -->
  <h2
    class="wp-block-heading has-gray-dark-color has-text-color has-link-color"
  ><?php _ex('Title', 'Sidebar title', 'usm'); ?></h2>
  <!-- /wp:heading -->

  <!-- wp:separator {"style":{"layout":{"selfStretch":"fill","flexSize":null}},"backgroundColor":"gray-light"} -->
  <hr
    class="wp-block-separator has-text-color has-gray-light-color has-alpha-channel-opacity has-gray-light-background-color has-background"
  />
  <!-- /wp:separator -->
</div>
<!-- /wp:group -->
