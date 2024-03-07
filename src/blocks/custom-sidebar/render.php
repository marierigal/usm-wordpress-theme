<?php
/**
 * The following variables are exposed:
 *
 * @var array  $block        The block settings and attributes.
 * @var string $content      The block inner HTML (empty).
 * @var bool   $is_preview   True during backend preview render.
 * @var int    $post_id      The post ID the block is rendering content against.
 *                           This is either the post ID currently being displayed inside a query
 *                           loop, or the post ID of the post hosting this block.
 * @var array  $context      The context provided to the block by the post or its parent block.
 */

$sidebar_id = get_field('sidebar', $post_id);
$default_sidebar_id = $block['data']['default_sidebar'];
$post = $sidebar_id ? get_post($sidebar_id) : get_post($default_sidebar_id);

if (!$post || $post->post_status !== 'publish') return '';

setup_postdata($post);
the_content();
