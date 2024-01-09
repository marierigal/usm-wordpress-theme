<?php

namespace USM\Taxonomies;

defined('ABSPATH') || exit;

use USM\Post_Types\Sponsor;
use USM\Traits\Singleton;

class Engagement_Level
{
  use Singleton;

  public const TAXONOMY_NAME = 'engagement_level';

  public static function init(): void
  {
    if (self::has_instance()) return;
    self::get_instance();

    // register taxonomy
    add_action('init', [self::class, 'register_taxonomy']);
  }

  /**
   * Register taxonomy.
   */
  public static function register_taxonomy(): void
  {
    register_taxonomy(self::TAXONOMY_NAME, [Sponsor::POST_TYPE_NAME], [
      'labels'            => [
        /* translators: General name for the taxonomy, usually plural. The same as and overridden by $tax->label. Default 'Tags'/'Categories'. */
        'name'                       => _x('Engagement Levels', 'Taxonomy label', 'usm'),
        /* translators: Singular name for the taxonomy. Default 'Tag'/'Category'. */
        'singular_name'              => _x('Engagement Level', 'Taxonomy label', 'usm'),
        /* translators: Default ‘Search Tags'/'Search Categories’. */
        'search_items'               => _x('Search Engagement Levels', 'Taxonomy label', 'usm'),
        /* translators: Default ‘Popular Tags'/'Popular Categories’. */
        'popular_items'              => _x('Popular Engagement Levels', 'Taxonomy label', 'usm'),
        /* translators: Default ‘All Tags'/'All Categories’. */
        'all_items'                  => _x('All Engagement Levels', 'Taxonomy label', 'usm'),
        /* translators: This label is only used for hierarchical taxonomies. Default ‘Parent Category’. */
        'parent_item'                => _x('Parent Engagement Level', 'Taxonomy label', 'usm'),
        /* translators: The same as parent_item, but with colon : in the end. */
        'parent_item_colon'          => _x('Parent Engagement Level:', 'Taxonomy label', 'usm'),
        /* translators: Default ‘Edit Tag'/'Edit Category’. */
        'edit_item'                  => _x('Edit Engagement Level', 'Taxonomy label', 'usm'),
        /* translators: Default ‘View Tag'/'View Category’. */
        'view_item'                  => _x('View Engagement Level', 'Taxonomy label', 'usm'),
        /* translators: Default ‘Update Tag'/'Update Category’. */
        'update_item'                => _x('Update Engagement Level', 'Taxonomy label', 'usm'),
        /* translators: Default ‘Add New Tag'/'Add New Category’. */
        'add_new_item'               => _x('Add New Engagement Level', 'Taxonomy label', 'usm'),
        /* translators: Default ‘New Tag Name'/'New Category Name’. */
        'new_item_name'              => _x('New Engagement Level Name', 'Taxonomy label', 'usm'),
        /* translators: This label is only used for non-hierarchical taxonomies. Default ‘Separate tags with commas’, used in the meta box. */
        'separate_items_with_commas' => _x('Separate engagement levels with commas', 'Taxonomy label', 'usm'),
        /* translators: This label is only used for non-hierarchical taxonomies. Default ‘Add or remove tags’, used in the meta box when JavaScript is disabled. */
        'add_or_remove_items'        => _x('Add or remove engagement levels', 'Taxonomy label', 'usm'),
        /* translators: This label is only used on non-hierarchical taxonomies. Default ‘Choose from the most used tags’, used in the meta box. */
        'choose_from_most_used'      => _x('Choose from the most used engagement levels', 'Taxonomy label', 'usm'),
        /* translators: Default ‘No tags found'/'No categories found’, used in the meta box and taxonomy list table. */
        'not_found'                  => _x('No engagement levels found.', 'Taxonomy label', 'usm'),
        /* translators: Default ‘No tags'/'No categories’, used in the posts and media list tables. */
        'no_terms'                   => _x('No engagement levels', 'Taxonomy label', 'usm'),
        /* translators: This label is only used for hierarchical taxonomies. Default ‘Filter by category’, used in the posts list table. */
        'filter_by_item'             => _x('Filter by engagement level', 'Taxonomy label', 'usm'),
        /* translators: Label for the table pagination hidden heading. Default 'Tags list navigation'/'Categories list navigation'. */
        'items_list_navigation'      => _x('Engagement Levels list navigation', 'Taxonomy label', 'usm'),
        /* translators: Label for the table hidden heading. Default 'Tags list'/'Categories list'. */
        'items_list'                 => _x('Engagement Levels list', 'Taxonomy label', 'usm'),
        /* translators: Label displayed after a term has been updated. Default '&larr; Go to Tags'/'&larr; Go to Categories' */
        'back_to_items'              => _x('&larr; Go to Engagement Levels', 'Taxonomy label', 'usm'),
        /* translators: Used in the block editor. Title for a navigation link block variation. Default ‘Tag Link'/'Category Link’. */
        'item_link'                  => _x('Engagement Level Link', 'Taxonomy label', 'usm'),
        /* translators: Used in the block editor. Description for a navigation link block variation. Default ‘A link to a tag'/'A link to a category’. */
        'item_link_description'      => _x('A link to an engagement level', 'Taxonomy label', 'usm'),
      ],
      'public'            => true,
      'show_in_menu'      => true,
      'show_in_nav_menus' => false,
      'show_in_rest'      => true,
      'show_tagcloud'     => false,
      'show_admin_column' => true,
      'capabilities'      => [
        'manage_terms' => 'manage_engagement_levels',
        'edit_terms'   => 'manage_engagement_levels',
        'delete_terms' => 'manage_engagement_levels',
        'assign_terms' => 'edit_sponsors',
      ],
      'meta_box_cb'       => false,
    ]);
  }
}
