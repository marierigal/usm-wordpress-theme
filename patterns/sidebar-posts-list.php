<?php
/**
 * Title: Sidebar list of posts
 * Slug: usm/sidebar-posts-list
 * Categories: posts,sidebar
 * Block Types: core/query
 */
?>

<!-- wp:query {"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true},"align":"wide"} -->
<div class="wp-block-query alignwide">
  <!-- wp:post-template {"layout":{"type":"default"}} -->
  <!-- wp:pattern {"slug":"usm/horizontal-post"} /-->
  <!-- /wp:post-template -->

  <!-- wp:query-pagination {"paginationArrow":"arrow","showLabel":false,"layout":{"type":"flex","justifyContent":"center","orientation":"horizontal"}} -->
  <!-- wp:query-pagination-previous /-->

  <!-- wp:query-pagination-numbers /-->

  <!-- wp:query-pagination-next /-->
  <!-- /wp:query-pagination -->

  <!-- wp:query-no-results -->
  <!-- wp:pattern {"slug":"usm/no-results"} /-->
  <!-- /wp:query-no-results -->
</div>
<!-- /wp:query -->
