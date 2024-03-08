<?php

namespace USM\Post_Types;

defined('ABSPATH') || exit;

use QM;
use USM\Taxonomies\Competition;
use USM\Taxonomies\Season;
use USM\Traits\Singleton;

class Event
{
  use Singleton;

  private const ADMIN_COLUMN_NAME_TAXONOMY_COMPETITION = 'taxonomy-' . Competition::TAXONOMY_NAME;
  private const ADMIN_COLUMN_NAME_TAXONOMY_SEASON = 'taxonomy-' . Season::TAXONOMY_NAME;
  public const POST_TYPE_NAME = 'event';

  /**
   * Initialize post type
   */
  public static function init(): void
  {
    if (self::has_instance()) return;
    self::get_instance();

    // register post type
    add_action('init', [self::class, 'register_post_type']);

    // manage admin table
    add_filter('manage_' . self::POST_TYPE_NAME . '_posts_columns', [self::class, 'manage_columns']);
    add_filter('manage_edit-' . self::POST_TYPE_NAME . '_sortable_columns', [self::class, 'set_sortable_columns']);
    add_action('restrict_manage_posts', [self::class, 'restrict_manage_posts']);
  }

  /**
   * Manage admin table columns
   *
   * @param string[] $post_columns
   *
   * @return string[]
   */
  public static function manage_columns(array $post_columns): array
  {
    $offset = 2;

    return array_merge(
      array_slice($post_columns, 0, $offset),
      [self::ADMIN_COLUMN_NAME_TAXONOMY_SEASON => $post_columns[self::ADMIN_COLUMN_NAME_TAXONOMY_SEASON]],
      [self::ADMIN_COLUMN_NAME_TAXONOMY_COMPETITION => $post_columns[self::ADMIN_COLUMN_NAME_TAXONOMY_COMPETITION]],
      array_slice($post_columns, $offset)
    );
  }

  /**
   * Register post type
   */
  public static function register_post_type(): void
  {
    register_post_type(self::POST_TYPE_NAME, [
      'labels'            => [
        /* translators: General name for the post type, usually plural. Default is ‘Posts’ / ‘Pages’. */
        'name'                     => _x('Events', 'Post type label', 'usm'),
        /* translators: Name for one object of this post type. Default is ‘Post’ / ‘Page’. */
        'singular_name'            => _x('Event', 'Post type label', 'usm'),
        /* translators: Label for adding a new item. Default is ‘Add New Post’ / ‘Add New Page’. */
        'add_new'                  => _x('Add an event', 'Post type label', 'usm'),
        /* translators: Label for adding a new singular item. Default is ‘Add New Post’ / ‘Add New Page’. */
        'add_new_item'             => _x('Add an event', 'Post type label', 'usm'),
        /* translators: Label for editing a singular item. Default is ‘Edit Post’ / ‘Edit Page’. */
        'edit_item'                => _x('Edit event', 'Post type label', 'usm'),
        /* translators: Label for the new item page title. Default is ‘New Post’ / ‘New Page’. */
        'new_item'                 => _x('New event', 'Post type label', 'usm'),
        /* translators: Label for viewing a singular item. Default is ‘View Post’ / ‘View Page’. */
        'view_item'                => _x('View event', 'Post type label', 'usm'),
        /* translators: Label for viewing post type archives. Default is ‘View Posts’ / ‘View Pages’. */
        'view_items'               => _x('View events', 'Post type label', 'usm'),
        /* translators: Label for searching plural items. Default is ‘Search Posts’ / ‘Search Pages’. */
        'search_items'             => _x('Search events', 'Post type label', 'usm'),
        /* translators: Label used when no items are found. Default is ‘No posts found’ / ‘No pages found’. */
        'not_found'                => _x('No event found.', 'Post type label', 'usm'),
        /* translators: Label used when no items are in the Trash. Default is ‘No posts found in Trash’ / ‘No pages found in Trash’. */
        'not_found_in_trash'       => _x('No event found in trash.', 'Post type label', 'usm'),
        /* translators: Label used to prefix parents of hierarchical items. Not used on non-hierarchical post types. Default is ‘Parent Page:’. */
        'parent_item_colon'        => _x('Parent event:', 'Post type label', 'usm'),
        /* translators: Label to signify all items in a submenu link. Default is ‘All Posts’ / ‘All Pages’. */
        'all_items'                => _x('All events', 'Post type label', 'usm'),
        /* translators: Label for archives in nav menus. Default is ‘Post Archives’ / ‘Page Archives’. */
        'archives'                 => _x('Event archives', 'Post type label', 'usm'),
        /* translators: Label for the attributes meta box. Default is ‘Post Attributes’ / ‘Page Attributes’. */
        'attributes'               => _x('Event attributes', 'Post type label', 'usm'),
        /* translators: Label for the media frame button. Default is ‘Insert into post’ / ‘Insert into page’. */
        'insert_into_item'         => _x('Add to event', 'Post type label', 'usm'),
        /* translators: Label for the media frame filter. Default is ‘Uploaded to this post’ / ‘Uploaded to this page’. */
        'uploaded_to_this_item'    => _x('Uploaded to this event', 'Post type label', 'usm'),
        /* translators: Label for the menu name. Default is the same as name. */
        'menu_name'                => _x('Events', 'Post type label', 'usm'),
        /* translators: Label for the table views hidden heading. Default is ‘Filter posts list’ / ‘Filter pages list’. */
        'filter_items_list'        => _x('Filter the list of events', 'Post type label', 'usm'),
        /* translators: Label for the date filter in list tables. Default is ‘Filter by date’. */
        'filter_by_date'           => _x('Filter events by date', 'Post type label', 'usm'),
        /* translators: Label for the table pagination hidden heading. Default is ‘Posts list navigation’ / ‘Pages list navigation’. */
        'items_list_navigation'    => _x('Navigation in the list of events', 'Post type label', 'usm'),
        /* translators: Label for the table hidden heading. Default is ‘Posts list’ / ‘Pages list’. */
        'items_list'               => _x('List of events', 'Post type label', 'usm'),
        /* translators: Label used when an item is published. Default is ‘Post published.’ / ‘Page published.’ */
        'item_published'           => _x('Event published.', 'Post type label', 'usm'),
        /* translators: Label used when an item is published with private visibility. Default is ‘Post published privately.’ / ‘Page published privately.’ */
        'item_published_privately' => _x('Event published privately.', 'Post type label', 'usm'),
        /* translators: Label used when an item is switched to a draft. Default is ‘Post reverted to draft.’ / ‘Page reverted to draft.’ */
        'item_reverted_to_draft'   => _x('Event reverted to draft.', 'Post type label', 'usm'),
        /* translators: Label used when an item is moved to Trash. Default is ‘Post trashed.’ / ‘Page trashed.’ */
        'item_trashed'             => _x('Event moved to trash.', 'Post type label', 'usm'),
        /* translators: Label used when an item is scheduled for publishing. Default is ‘Post scheduled.’ / ‘Page scheduled.’ */
        'item_scheduled'           => _x('Event scheduled.', 'Post type label', 'usm'),
        /* translators: Label used when an item is updated. Default is ‘Post updated.’ / ‘Page updated.’ */
        'item_updated'             => _x('Event updated.', 'Post type label', 'usm'),
        /* translators: Title for a navigation link block variation. Default is ‘Post Link’ / ‘Page Link’. */
        'item_link'                => _x('Event link', 'Post type label', 'usm'),
        /* translators: Description for a navigation link block variation. Default is ‘A link to a post.’ / ‘A link to a page.’ */
        'item_link_description'    => _x('A link to an event.', 'Post type label', 'usm'),
      ],
      'public'            => true,
      'show_in_nav_menus' => false,
      'show_in_rest'      => true,
      'menu_position'     => 5,
      'menu_icon'         => 'dashicons-calendar-alt',
      'capability_type'   => ['event', 'events'],
      'map_meta_cap'      => true,
      'supports'          => ['title', 'author', 'editor', 'excerpt', 'revisions', 'thumbnail'],
      'taxonomies'        => [Season::TAXONOMY_NAME, Competition::TAXONOMY_NAME],
      'delete_with_user'  => false,
    ]);
  }

  /**
   * Add custom filters to admin table
   */
  public static function restrict_manage_posts(string $post_type): void
  {
    if ($post_type !== self::POST_TYPE_NAME) return;

    // Season filter
    $season_taxonomy = get_taxonomy(Season::TAXONOMY_NAME);
    if ($season_taxonomy) {
      wp_dropdown_categories([
        'show_option_all' => $season_taxonomy->labels->all_items,
        'taxonomy'        => Season::TAXONOMY_NAME,
        'name'            => Season::TAXONOMY_NAME,
        'orderby'         => 'name',
        'value_field'     => 'slug',
        'selected'        => $_REQUEST[Season::TAXONOMY_NAME] ?? '',
      ]);
    }

    // Competition filter
    $competition_taxonomy = get_taxonomy(Competition::TAXONOMY_NAME);
    QM::debug($competition_taxonomy);
    if ($competition_taxonomy) {
      wp_dropdown_categories([
        'show_option_all' => $competition_taxonomy->labels->all_items,
        'taxonomy'        => Competition::TAXONOMY_NAME,
        'name'            => Competition::TAXONOMY_NAME,
        'orderby'         => 'name',
        'value_field'     => 'slug',
        'selected'        => $_REQUEST[Competition::TAXONOMY_NAME] ?? '',
      ]);
    }
  }

  /**
   * Set sortable columns in admin table
   *
   * @param string[] $sortable_columns
   *
   * @return string[]
   */
  public static function set_sortable_columns(array $sortable_columns): array
  {
    $sortable_columns[self::ADMIN_COLUMN_NAME_TAXONOMY_SEASON] = [
      self::ADMIN_COLUMN_NAME_TAXONOMY_SEASON,
      false,
      _x('Season', 'Admin column title', 'usm'),
      _x('Table sorted by season.', 'Admin column label', 'usm'),
    ];

    $sortable_columns[self::ADMIN_COLUMN_NAME_TAXONOMY_COMPETITION] = [
      self::ADMIN_COLUMN_NAME_TAXONOMY_COMPETITION,
      false,
      _x('Competition', 'Admin column title', 'usm'),
      _x('Table sorted by competition.', 'Admin column label', 'usm'),
    ];

    return $sortable_columns;
  }
}
