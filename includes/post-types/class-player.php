<?php

namespace USM\Post_Types;

defined('ABSPATH') || exit;

use USM\DOM_Helper;
use USM\Taxonomies\Position;
use USM\Traits\Singleton;
use WP_Query;

class Player
{
  use Singleton;

  private const ACF_FIELD_KEY_FIRST_NAME = 'field_655c01c5be8ee';
  private const ACF_FIELD_KEY_LAST_NAME = 'field_655c01c5be8e6';
  private const ACF_FIELD_KEY_ROLE = 'field_655c01c5c2714';
  private const ADMIN_COLUMN_NAME_ROLE = 'role';
  private const ADMIN_COLUMN_NAME_TAXONOMY = 'taxonomy-' . Position::TAXONOMY_NAME;
  private const HOOK_NAME_SAVE_POST = 'save_post_' . self::POST_TYPE_NAME;
  private const META_KEY_ROLE = 'role';

  public const POST_TYPE_NAME = 'player';

  /**
   * Initialize post type
   */
  public static function init(): void
  {
    if (self::has_instance()) return;
    self::get_instance();

    // register post type
    add_action('init', [self::class, 'register_post_type']);

    // on save
    add_action(self::HOOK_NAME_SAVE_POST, [self::class, 'save_post']);

    // manage admin table
    add_filter('manage_' . self::POST_TYPE_NAME . '_posts_columns', [self::class, 'manage_columns']);
    add_filter('manage_edit-' . self::POST_TYPE_NAME . '_sortable_columns', [self::class, 'set_sortable_columns']);
    add_action('manage_' . self::POST_TYPE_NAME . '_posts_custom_column', [self::class, 'manage_custom_columns']);
    add_action('restrict_manage_posts', [self::class, 'restrict_manage_posts']);
    add_action('pre_get_posts', [self::class, 'pre_get_posts']);
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
      [self::ADMIN_COLUMN_NAME_ROLE => _x('Role', 'Admin column title', 'usm')],
      [self::ADMIN_COLUMN_NAME_TAXONOMY => $post_columns[self::ADMIN_COLUMN_NAME_TAXONOMY]],
      array_slice($post_columns, $offset)
    );
  }

  /**
   * Populate admin table custom columns
   */
  public static function manage_custom_columns(string $column_name): void
  {
    switch ($column_name) {
      case self::ADMIN_COLUMN_NAME_ROLE:
        $field = get_field(self::ACF_FIELD_KEY_ROLE);
        $link = 'edit.php?post_type=' . self::POST_TYPE_NAME . '&' . self::ADMIN_COLUMN_NAME_ROLE . '=' . $field['value'];
        echo '<a href="' . $link . '">' . $field['label'] . '</a>';
        break;

      default:
        break;
    }
  }

  /**
   * Update the query with custom filters
   */
  public static function pre_get_posts(WP_Query $query): void
  {
    if (!is_admin() || !$query->is_main_query() || $query->query_vars['post_type'] !== self::POST_TYPE_NAME) return;

    $role = $_REQUEST[self::ADMIN_COLUMN_NAME_ROLE] ?? '';
    if ($role) {
      $query->set('meta_key', self::META_KEY_ROLE);
      $query->set('meta_value', $role);
    }
  }

  /**
   * Register post type
   */
  public static function register_post_type(): void
  {
    register_post_type(self::POST_TYPE_NAME, [
      'labels'            => [
        /* translators: General name for the post type, usually plural. Default is ‘Posts’ / ‘Pages’. */
        'name'                     => _x('Players', 'Post type label', 'usm'),
        /* translators: Name for one object of this post type. Default is ‘Post’ / ‘Page’. */
        'singular_name'            => _x('Player', 'Post type label', 'usm'),
        /* translators: Label for adding a new item. Default is ‘Add New Post’ / ‘Add New Page’. */
        'add_new'                  => _x('Add a player', 'Post type label', 'usm'),
        /* translators: Label for adding a new singular item. Default is ‘Add New Post’ / ‘Add New Page’. */
        'add_new_item'             => _x('Add a player', 'Post type label', 'usm'),
        /* translators: Label for editing a singular item. Default is ‘Edit Post’ / ‘Edit Page’. */
        'edit_item'                => _x('Edit player', 'Post type label', 'usm'),
        /* translators: Label for the new item page title. Default is ‘New Post’ / ‘New Page’. */
        'new_item'                 => _x('New player', 'Post type label', 'usm'),
        /* translators: Label for viewing a singular item. Default is ‘View Post’ / ‘View Page’. */
        'view_item'                => _x('View player', 'Post type label', 'usm'),
        /* translators: Label for viewing post type archives. Default is ‘View Posts’ / ‘View Pages’. */
        'view_items'               => _x('View players', 'Post type label', 'usm'),
        /* translators: Label for searching plural items. Default is ‘Search Posts’ / ‘Search Pages’. */
        'search_items'             => _x('Search players', 'Post type label', 'usm'),
        /* translators: Label used when no items are found. Default is ‘No posts found’ / ‘No pages found’. */
        'not_found'                => _x('No player found.', 'Post type label', 'usm'),
        /* translators: Label used when no items are in the Trash. Default is ‘No posts found in Trash’ / ‘No pages found in Trash’. */
        'not_found_in_trash'       => _x('No player found in trash.', 'Post type label', 'usm'),
        /* translators: Label used to prefix parents of hierarchical items. Not used on non-hierarchical post types. Default is ‘Parent Page:’. */
        'parent_item_colon'        => _x('Parent player:', 'Post type label', 'usm'),
        /* translators: Label to signify all items in a submenu link. Default is ‘All Posts’ / ‘All Pages’. */
        'all_items'                => _x('All players', 'Post type label', 'usm'),
        /* translators: Label for archives in nav menus. Default is ‘Post Archives’ / ‘Page Archives’. */
        'archives'                 => _x('Player archives', 'Post type label', 'usm'),
        /* translators: Label for the attributes meta box. Default is ‘Post Attributes’ / ‘Page Attributes’. */
        'attributes'               => _x('Player attributes', 'Post type label', 'usm'),
        /* translators: Label for the media frame button. Default is ‘Insert into post’ / ‘Insert into page’. */
        'insert_into_item'         => _x('Add to player', 'Post type label', 'usm'),
        /* translators: Label for the media frame filter. Default is ‘Uploaded to this post’ / ‘Uploaded to this page’. */
        'uploaded_to_this_item'    => _x('Uploaded to this player', 'Post type label', 'usm'),
        /* translators: Label for the menu name. Default is the same as name. */
        'menu_name'                => _x('Players', 'Post type label', 'usm'),
        /* translators: Label for the table views hidden heading. Default is ‘Filter posts list’ / ‘Filter pages list’. */
        'filter_items_list'        => _x('Filter the list of players', 'Post type label', 'usm'),
        /* translators: Label for the date filter in list tables. Default is ‘Filter by date’. */
        'filter_by_date'           => _x('Filter players by date', 'Post type label', 'usm'),
        /* translators: Label for the table pagination hidden heading. Default is ‘Posts list navigation’ / ‘Pages list navigation’. */
        'items_list_navigation'    => _x('Navigation in the list of players', 'Post type label', 'usm'),
        /* translators: Label for the table hidden heading. Default is ‘Posts list’ / ‘Pages list’. */
        'items_list'               => _x('List of players', 'Post type label', 'usm'),
        /* translators: Label used when an item is published. Default is ‘Post published.’ / ‘Page published.’ */
        'item_published'           => _x('Player published.', 'Post type label', 'usm'),
        /* translators: Label used when an item is published with private visibility. Default is ‘Post published privately.’ / ‘Page published privately.’ */
        'item_published_privately' => _x('Player published privately.', 'Post type label', 'usm'),
        /* translators: Label used when an item is switched to a draft. Default is ‘Post reverted to draft.’ / ‘Page reverted to draft.’ */
        'item_reverted_to_draft'   => _x('Player reverted to draft.', 'Post type label', 'usm'),
        /* translators: Label used when an item is moved to Trash. Default is ‘Post trashed.’ / ‘Page trashed.’ */
        'item_trashed'             => _x('Player moved to trash.', 'Post type label', 'usm'),
        /* translators: Label used when an item is scheduled for publishing. Default is ‘Post scheduled.’ / ‘Page scheduled.’ */
        'item_scheduled'           => _x('Player scheduled.', 'Post type label', 'usm'),
        /* translators: Label used when an item is updated. Default is ‘Post updated.’ / ‘Page updated.’ */
        'item_updated'             => _x('Player updated.', 'Post type label', 'usm'),
        /* translators: Title for a navigation link block variation. Default is ‘Post Link’ / ‘Page Link’. */
        'item_link'                => _x('Player link', 'Post type label', 'usm'),
        /* translators: Description for a navigation link block variation. Default is ‘A link to a post.’ / ‘A link to a page.’ */
        'item_link_description'    => _x('A link to a player.', 'Post type label', 'usm'),
      ],
      'public'            => true,
      'show_in_nav_menus' => false,
      'show_in_rest'      => true,
      'menu_position'     => 5,
      'menu_icon'         => 'dashicons-groups',
      'capability_type'   => ['player', 'players'],
      'map_meta_cap'      => true,
      'supports'          => ['revisions'],
      'taxonomies'        => [Position::TAXONOMY_NAME],
      'delete_with_user'  => false,
    ]);
  }

  /**
   * Add custom filters to admin table
   */
  public static function restrict_manage_posts(string $post_type): void
  {
    if ($post_type !== self::POST_TYPE_NAME) return;

    // Role filter
    echo DOM_Helper::generate_dropdown_HTML([
      'show_option_all' => _x('All roles', 'Role option', 'usm'),
      'name'            => self::ADMIN_COLUMN_NAME_ROLE,
      'options'         => [
        'player' => _x('Player', 'Role option', 'usm'),
        'staff'  => _x('Staff', 'Role option', 'usm'),
      ],
      'selected'        => $_REQUEST[self::ADMIN_COLUMN_NAME_ROLE] ?? '',
    ]);

    // Position filter
    $positionTaxonomy = get_taxonomy(Position::TAXONOMY_NAME);
    if ($positionTaxonomy) {
      wp_dropdown_categories([
        'show_option_all' => $positionTaxonomy->labels->all_items,
        'taxonomy'        => Position::TAXONOMY_NAME,
        'name'            => Position::TAXONOMY_NAME,
        'orderby'         => 'name',
        'value_field'     => 'slug',
        'selected'        => $_REQUEST[Position::TAXONOMY_NAME] ?? '',
      ]);
    }
  }

  /**
   * Update the post title and slug when the last name or the first name is updated
   */
  public static function save_post(int $post_id): void
  {
    $new_last_name = $_POST['acf'][self::ACF_FIELD_KEY_LAST_NAME] ?? '';
    $new_first_name = $_POST['acf'][self::ACF_FIELD_KEY_FIRST_NAME] ?? '';

    if (!$new_last_name || !$new_first_name) return;

    // Prevent infinite loop
    remove_action(self::HOOK_NAME_SAVE_POST, [self::class, 'save_post']);

    $slug = sanitize_title($new_last_name . '-' . $new_first_name);
    $title = strtoupper($new_last_name) . ' ' . $new_first_name;

    wp_update_post(['ID' => $post_id, 'post_name' => $slug, 'post_title' => $title]);

    // Restore action
    add_action(self::HOOK_NAME_SAVE_POST, [self::class, 'save_post']);
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
    $sortable_columns[self::ADMIN_COLUMN_NAME_TAXONOMY] = [
      self::ADMIN_COLUMN_NAME_TAXONOMY,
      false,
      _x('Position', 'Admin column title', 'usm'),
      _x('Table sorted by position.', 'Admin column label', 'usm'),
    ];

    $sortable_columns[self::ADMIN_COLUMN_NAME_ROLE] = [
      self::ADMIN_COLUMN_NAME_ROLE,
      false,
      _x('Role', 'Admin column title', 'usm'),
      _x('Table sorted by role.', 'Admin column label', 'usm'),
    ];

    return $sortable_columns;
  }
}
