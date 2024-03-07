<?php

namespace USM\Post_Types;

defined('ABSPATH') || exit;

use USM\Traits\Singleton;

class Sidebar
{
  use Singleton;

  public const POST_TYPE_NAME = 'sidebar';

  /**
   * Initialize post type
   */
  public static function init(): void
  {
    if (self::has_instance()) return;
    self::get_instance();

    // register post type
    add_action('init', [self::class, 'register_post_type']);
  }

  /**
   * Register post type
   */
  public static function register_post_type(): void
  {
    register_post_type(self::POST_TYPE_NAME, [
      'labels'              => [
        /* translators: General name for the post type, usually plural. Default is ‘Posts’ / ‘Pages’. */
        'name'                     => _x('Sidebars', 'Post type label', 'usm'),
        /* translators: Name for one object of this post type. Default is ‘Post’ / ‘Page’. */
        'singular_name'            => _x('Sidebar', 'Post type label', 'usm'),
        /* translators: Label for adding a new item. Default is ‘Add New Post’ / ‘Add New Page’. */
        'add_new'                  => _x('Add a sidebar', 'Post type label', 'usm'),
        /* translators: Label for adding a new singular item. Default is ‘Add New Post’ / ‘Add New Page’. */
        'add_new_item'             => _x('Add a sidebar', 'Post type label', 'usm'),
        /* translators: Label for editing a singular item. Default is ‘Edit Post’ / ‘Edit Page’. */
        'edit_item'                => _x('Edit sidebar', 'Post type label', 'usm'),
        /* translators: Label for the new item page title. Default is ‘New Post’ / ‘New Page’. */
        'new_item'                 => _x('New sidebar', 'Post type label', 'usm'),
        /* translators: Label for viewing a singular item. Default is ‘View Post’ / ‘View Page’. */
        'view_item'                => _x('View sidebar', 'Post type label', 'usm'),
        /* translators: Label for viewing post type archives. Default is ‘View Posts’ / ‘View Pages’. */
        'view_items'               => _x('View sidebars', 'Post type label', 'usm'),
        /* translators: Label for searching plural items. Default is ‘Search Posts’ / ‘Search Pages’. */
        'search_items'             => _x('Search sidebars', 'Post type label', 'usm'),
        /* translators: Label used when no items are found. Default is ‘No posts found’ / ‘No pages found’. */
        'not_found'                => _x('No sidebar found.', 'Post type label', 'usm'),
        /* translators: Label used when no items are in the Trash. Default is ‘No posts found in Trash’ / ‘No pages found in Trash’. */
        'not_found_in_trash'       => _x('No sidebar found in trash.', 'Post type label', 'usm'),
        /* translators: Label used to prefix parents of hierarchical items. Not used on non-hierarchical post types. Default is ‘Parent Page:’. */
        'parent_item_colon'        => _x('Parent sidebar:', 'Post type label', 'usm'),
        /* translators: Label to signify all items in a submenu link. Default is ‘All Posts’ / ‘All Pages’. */
        'all_items'                => _x('All sidebars', 'Post type label', 'usm'),
        /* translators: Label for archives in nav menus. Default is ‘Post Archives’ / ‘Page Archives’. */
        'archives'                 => _x('Sidebar archives', 'Post type label', 'usm'),
        /* translators: Label for the attributes meta box. Default is ‘Post Attributes’ / ‘Page Attributes’. */
        'attributes'               => _x('Sidebar attributes', 'Post type label', 'usm'),
        /* translators: Label for the media frame button. Default is ‘Insert into post’ / ‘Insert into page’. */
        'insert_into_item'         => _x('Add to sidebar', 'Post type label', 'usm'),
        /* translators: Label for the media frame filter. Default is ‘Uploaded to this post’ / ‘Uploaded to this page’. */
        'uploaded_to_this_item'    => _x('Uploaded to this sidebar', 'Post type label', 'usm'),
        /* translators: Label for the menu name. Default is the same as name. */
        'menu_name'                => _x('Sidebars', 'Post type label', 'usm'),
        /* translators: Label for the table views hidden heading. Default is ‘Filter posts list’ / ‘Filter pages list’. */
        'filter_items_list'        => _x('Filter the list of sidebars', 'Post type label', 'usm'),
        /* translators: Label for the date filter in list tables. Default is ‘Filter by date’. */
        'filter_by_date'           => _x('Filter sidebars by date', 'Post type label', 'usm'),
        /* translators: Label for the table pagination hidden heading. Default is ‘Posts list navigation’ / ‘Pages list navigation’. */
        'items_list_navigation'    => _x('Navigation in the list of sidebars', 'Post type label', 'usm'),
        /* translators: Label for the table hidden heading. Default is ‘Posts list’ / ‘Pages list’. */
        'items_list'               => _x('List of sidebars', 'Post type label', 'usm'),
        /* translators: Label used when an item is published. Default is ‘Post published.’ / ‘Page published.’ */
        'item_published'           => _x('Sidebar published.', 'Post type label', 'usm'),
        /* translators: Label used when an item is published with private visibility. Default is ‘Post published privately.’ / ‘Page published privately.’ */
        'item_published_privately' => _x('Sidebar published privately.', 'Post type label', 'usm'),
        /* translators: Label used when an item is switched to a draft. Default is ‘Post reverted to draft.’ / ‘Page reverted to draft.’ */
        'item_reverted_to_draft'   => _x('Sidebar reverted to draft.', 'Post type label', 'usm'),
        /* translators: Label used when an item is moved to Trash. Default is ‘Post trashed.’ / ‘Page trashed.’ */
        'item_trashed'             => _x('Sidebar moved to trash.', 'Post type label', 'usm'),
        /* translators: Label used when an item is scheduled for publishing. Default is ‘Post scheduled.’ / ‘Page scheduled.’ */
        'item_scheduled'           => _x('Sidebar scheduled.', 'Post type label', 'usm'),
        /* translators: Label used when an item is updated. Default is ‘Post updated.’ / ‘Page updated.’ */
        'item_updated'             => _x('Sidebar updated.', 'Post type label', 'usm'),
        /* translators: Title for a navigation link block variation. Default is ‘Post Link’ / ‘Page Link’. */
        'item_link'                => _x('Sidebar link', 'Post type label', 'usm'),
        /* translators: Description for a navigation link block variation. Default is ‘A link to a post.’ / ‘A link to a page.’ */
        'item_link_description'    => _x('A link to a sidebar.', 'Post type label', 'usm'),
      ],
      'public'              => true,
      'exclude_from_search' => true,
      'publicly_queryable'  => false,
      'show_in_nav_menus'   => false,
      'show_in_rest'        => true,
      'menu_position'       => 20,
      'menu_icon'           => 'dashicons-align-pull-right',
      'capability_type'     => ['sidebar', 'sidebars'],
      'map_meta_cap'        => true,
      'supports'            => ['title', 'editor', 'revisions'],
      'taxonomies'          => [],
      'delete_with_user'    => false,
    ]);
  }
}
