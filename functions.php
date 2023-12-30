<?php
/**
 * USM functions and definitions
 *
 * @package USM
 * @since   USM 1.0.0
 */

namespace USM;

/**
 * Prefix
 */
define('PREFIX', sanitize_title(__NAMESPACE__));

/**
 * Assets folder URI
 */
define('ASSETS', esc_url(get_stylesheet_directory_uri()) . '/assets');

/**
 * Set up theme defaults and register various WordPress features.
 */
function setup()
{
  // Load translations.
  load_theme_textdomain('usm', get_template_directory() . '/languages');

  // Enqueue editor styles and fonts.
  add_editor_style('style.css');

  // Remove core block patterns.
  remove_theme_support('core-block-patterns');
}

add_action('after_setup_theme', __NAMESPACE__ . '\setup');

/**
 * Enqueue styles.
 */
function enqueue_stylesheets()
{
  wp_enqueue_style(
    PREFIX . '-style',
    get_template_directory_uri() . '/style.css',
    [],
    wp_get_theme()->get('Version')
  );
}

add_action('wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_stylesheets');

/**
 * Register block styles.
 */
function register_block_styles()
{
  $block_styles = [
    'core/navigation-link' => [
      /* translators: Label for core/navigation-link block arrow-link style */
      'arrow-link' => _x('With arrow', 'Block style name', 'usm'),
    ],
  ];

  // Register block styles variants
  foreach ($block_styles as $block_name => $styles) {
    foreach ($styles as $style_name => $style_label) {
      register_block_style(
        $block_name,
        [
          'name'  => $style_name,
          'label' => $style_label,
        ]
      );
    }
  }

  // Scan our styles folder to locate block styles.
  $styles_path = 'assets/css';
  $files = glob(get_template_directory() . "/{$styles_path}/*.css");
  foreach ($files as $file) {
    // Get the filename and core block name.
    $extension = 'css';
    $filename = basename($file, ".{$extension}");
    $block_name = str_replace('core-', 'core/', $filename);

    wp_enqueue_block_style(
      $block_name,
      [
        'handle' => PREFIX . '-' . $filename,
        'src'    => get_theme_file_uri("{$styles_path}/{$filename}.{$extension}"),
        'path'   => get_theme_file_path("{$styles_path}/{$filename}.{$extension}"),
      ]
    );
  }
}

add_action('init', __NAMESPACE__ . '\register_block_styles');

/**
 * Register pattern categories.
 */
function register_pattern_categories()
{
  $block_pattern_categories = [
    'usm/theme'   => [
      /* translators: Label for usm/theme pattern category */
      'label' => _x('Theme', 'Pattern category name', 'usm'),
    ],
    'usm/header'  => [
      /* translators: Label for usm/header pattern category */
      'label' => _x('Header', 'Pattern category name', 'usm'),
    ],
    'usm/footer'  => [
      /* translators: Label for usm/footer pattern category */
      'label' => _x('Footer', 'Pattern category name', 'usm'),
    ],
    'usm/posts'   => [
      /* translators: Label for usm/posts pattern category */
      'label' => _x('Posts', 'Pattern category name', 'usm'),
    ],
    'usm/sidebar' => [
      /* translators: Label for usm/sidebar pattern category */
      'label' => _x('Sidebar', 'Pattern category name', 'usm'),
    ],
  ];

  foreach ($block_pattern_categories as $name => $properties) {
    register_block_pattern_category($name, $properties);
  }
}

add_action('init', __NAMESPACE__ . '\register_pattern_categories', 9);

/**
 * Manage template part area
 */
function template_part_areas(array $areas): array
{
  $areas[] = [
    'area'        => 'sidebar',
    'area_tag'    => 'section',
    /* translators: Label for sidebar template part area */
    'label'       => _x('Sidebar', 'Part area name', 'usm'),
    /* translators: Description for sidebar template part area */
    'description' => _x('The Sidebar template defines a page area that can be found on the Page (With Sidebar) template.', 'Part area description', 'usm'),
    'icon'        => 'sidebar',
  ];

  return $areas;
}

add_filter('default_wp_template_part_areas', __NAMESPACE__ . '\template_part_areas');

/**
 * Register block styles variants.
 */
function register_block_style_variants(array $block_styles)
{
  foreach ($block_styles as $block_name => $styles) {
    foreach ($styles as $style_name => $style_label) {
      register_block_style(
        $block_name,
        [
          'name'  => $style_name,
          'label' => $style_label,
        ]
      );
    }
  }
}

/**
 * Enqueue core block styles.
 */
function enqueue_core_block_style(string $path)
{
  $files = glob(get_template_directory() . "/{$path}/*.css");
  foreach ($files as $file) {
    // Get the filename and core block name.
    $extension = 'css';
    $filename = basename($file, ".{$extension}");
    $block_name = str_replace('core-', 'core/', $filename);

    wp_enqueue_block_style(
      $block_name,
      [
        'handle' => PREFIX . '-' . $filename,
        'src'    => get_theme_file_uri("{$path}/{$filename}.{$extension}"),
        'path'   => get_theme_file_path("{$path}/{$filename}.{$extension}"),
      ]
    );
  }
}
