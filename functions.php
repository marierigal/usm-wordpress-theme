<?php
/**
 * USM functions and definitions
 *
 * @package USM
 * @since   USM 1.0.0
 */

namespace USM;

defined('ABSPATH') || exit;

include_once 'includes/autoload.php';

/**
 * Assets directory
 */
define('USM_ASSETS', esc_url(get_stylesheet_directory_uri()) . '/' . Theme::ASSETS_DIRECTORY);

/**
 * Theme setup
 */
Theme::setup([
  'advanced-custom-fields-pro/acf.php'               => 'Advanced Custom Fields PRO',
  'font-awesome/index.php'                           => 'Font Awesome',
  'capability-manager-enhanced/capsman-enhanced.php' => 'PublishPress Capabilities',
]);

/**
 * Enqueue admin scripts and styles
 */
Theme::admin_enqueue_scripts(['admin' => 'assets/css/admin.css']);

/**
 * Enqueue front scripts and styles
 */
Theme::enqueue_scripts(['style' => 'assets/css/style.css']);

/**
 * Register blocks
 */
Theme::register_blocks([
  new Block('custom-sidebar'),
  new Block('match'),
  new Block('meta-field-block'),
  new Block('player-thumbnail'),
  new Block('sponsors-slider'),
  new Block('short-and-socks-tag'),
]);

Blocks\Meta_Field\Meta_Field_Block::init();
Blocks\Slider\Slider::init();

/**
 * Register format types
 */
Theme::register_format_types([
  new Format_Type('icon'),
]);

/**
 * Register block styles
 */
Theme::register_block_styles([
  /* translators: Label for core/navigation-link block arrow-link style */
  new Block_Style('core/navigation-link', 'arrow-link', _x('Arrow link', 'Block style name', 'usm')),
]);

/**
 * Register pattern categories
 */
Theme::register_pattern_categories([
  /* translators: Label for sidebar pattern category */
  new Block_Pattern_Category('sidebar', _x('Sidebar', 'Pattern category name', 'usm')),
]);

/**
 * Register template part areas
 */
Theme::register_template_part_areas([
  new Template_Part_Area(
    'sidebar',
    'section',
    /* translators: Label for sidebar template part area */
    _x('Sidebar', 'Part area name', 'usm'),
    /* translators: Description for sidebar template part area */
    _x('The Sidebar template defines a page area that can be found on the Page (With Sidebar) template.', 'Part area description', 'usm'),
    'sidebar',
  ),
]);

/**
 * Custom post types
 */
Post_Types\Event::init();
Post_Types\Player::init();
Post_Types\Sidebar::init();
Post_Types\Sponsor::init();

/**
 * Custom taxonomies
 */
Taxonomies::init();
Taxonomies\Competition::init();
Taxonomies\Engagement_Level::init();
Taxonomies\Position::init();
Taxonomies\Season::init();

/**
 * Options pages
 */
Options_Pages\Theme_Options::init();
