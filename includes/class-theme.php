<?php

namespace USM;

defined('ABSPATH') || exit;

class Theme
{
  const ASSETS_DIRECTORY = 'assets';
  const DOMAIN = 'usm';
  const LANGUAGES_DIRECTORY = 'languages';

  /**
   * Enqueue Admin scripts and styles
   *
   * @param string[] $styles  Styles to enqueue
   * @param string[] $scripts Scripts to enqueue
   *
   * @example
   * ```
   * Theme::admin_enqueue_scripts(
   *   ['my-admin-style' => '/css/admin.css'],
   *   ['my-admin-script' => '/js/admin.js'],
   * );
   * ```
   */
  public static function admin_enqueue_scripts(array $styles = [], array $scripts = []): void
  {
    add_action('admin_enqueue_scripts', function () use ($styles, $scripts): void {
      foreach ($styles as $handle => $path) {
        self::enqueue_style($handle, $path);
      }

      foreach ($scripts as $handle => $path) {
        self::enqueue_script($handle, $path);
      }
    });
  }

  /**
   * Enqueue a script file
   */
  public static function enqueue_script(string $handle, string $path = '', array $deps = [], bool|null|string $version = null, array|bool $args = []): void
  {
    wp_enqueue_script(
      self::DOMAIN . '-' . sanitize_title($handle),
      self::get_file_uri($path),
      $deps,
      $version ?? self::get_version(),
      $args
    );
  }

  /**
   * Enqueue admin and front scripts and styles
   *
   * @param string[] $styles  Styles to enqueue
   * @param string[] $scripts Scripts to enqueue
   *
   * @example
   *  ```
   *  Theme::enqueue_scripts(
   *    ['my-style' => '/css/style.css'],
   *    ['my-script' => '/js/script.js'],
   *  );
   *  ```
   */
  public static function enqueue_scripts(array $styles = [], array $scripts = []): void
  {
    add_action('wp_enqueue_scripts', function () use ($styles, $scripts): void {
      foreach ($styles as $handle => $path) {
        self::enqueue_style($handle, $path);
      }

      foreach ($scripts as $handle => $path) {
        self::enqueue_script($handle, $path);
      }
    });
  }

  /**
   * Enqueue a stylesheet
   */
  public static function enqueue_style(string $handle, string $path = '', array $deps = [], bool|null|string $version = null, string $media = 'all'): void
  {
    wp_enqueue_style(
      self::DOMAIN . '-' . sanitize_title($handle),
      self::get_file_uri($path),
      $deps,
      $version ?? self::get_version(),
      $media
    );
  }

  /**
   * Get theme version
   */
  public static function get_version(): string
  {
    return wp_get_theme()->get('Version');
  }

  /**
   * Register block styles
   *
   * @param Block_Style[] $block_styles
   */
  public static function register_block_styles(array $block_styles): void
  {
    add_action('init', function () use ($block_styles): void {
      self::register_block_style_variants($block_styles);
      self::enqueue_block_styles();
    });
  }

  /**
   * Register blocks
   *
   * @params Block[] $blocks
   */
  public static function register_blocks(array $blocks): void
  {
    add_action('init', function () use ($blocks): void {
      foreach ($blocks as $block) {
        $block->register();
      }
    });
  }

  /**
   * Register format types
   *
   * @param Format_Type[] $format_types
   */
  public static function register_format_types(array $format_types): void
  {
    add_action('enqueue_block_editor_assets', function () use ($format_types): void {
      foreach ($format_types as $format_type) {
        $format_type->register();
      }
    });
  }

  /**
   * Register block pattern categories
   *
   * @param Block_Pattern_Category[] $block_pattern_categories
   */
  public static function register_pattern_categories(array $block_pattern_categories): void
  {
    add_action('init', function () use ($block_pattern_categories): void {
      foreach ($block_pattern_categories as $category) {
        $category->register();
      }
    });
  }

  /**
   * Add template part areas
   *
   * @param Template_Part_Area[] $template_part_areas
   */
  public static function register_template_part_areas(array $template_part_areas): void
  {
    add_filter('default_wp_template_part_areas', function (array $areas) use ($template_part_areas): array {
      return array_merge(
        $areas,
        array_map(fn($area) => $area->get_properties(), $template_part_areas)
      );
    });
  }

  /**
   * Set up theme defaults and register various WordPress features
   *
   * @example "Theme::setup([['plugin-folder/plugin-file.php' => 'Plugin name']]);"
   */
  public static function setup(array $recommended_plugins = []): void
  {
    add_action('after_setup_theme', function () use ($recommended_plugins): void {
      if (!empty($recommended_plugins)) {
        // Display an admin notice if some recommended plugins are not installed and/or active.
        Plugin_Manager::handle_admin_notice($recommended_plugins);
      }

      // Load translations.
      load_theme_textdomain(self::DOMAIN, get_theme_file_path(self::LANGUAGES_DIRECTORY));

      // Enqueue editor styles and fonts.
      add_editor_style(self::ASSETS_DIRECTORY . '/css/style.css');

      // Remove core block patterns.
      remove_theme_support('core-block-patterns');
    });
  }

  /**
   * Enqueue core block styles
   */
  private static function enqueue_block_styles(): void
  {
    $extension = '.block.css';
    $files = glob(get_theme_file_path(self::ASSETS_DIRECTORY . "/css/*$extension"));
    foreach ($files as $file) {
      // Get the block name.
      $filename = basename($file, $extension);
      $block_name = str_replace('_', '/', $filename);

      // Enqueue the block style.
      wp_enqueue_block_style($block_name, [
        'handle' => self::DOMAIN . '-' . sanitize_title($block_name),
        'src'    => get_theme_file_uri(self::ASSETS_DIRECTORY . "/css/$filename$extension"),
        'path'   => get_theme_file_path(self::ASSETS_DIRECTORY . "/css/$filename$extension"),
      ]);
    }
  }

  /**
   * Get file URI
   */
  private static function get_file_uri(string $path): string
  {
    if (preg_match('/^https?:\/\//', $path)) return $path;

    return get_theme_file_uri($path);
  }

  /**
   * Register block styles variants
   *
   * @param Block_Style[] $block_styles
   *
   * @example
   * ```
   * Theme::register_block_style_variants(
   *   [
   *    'block_name' => 'core/navigation-link',
   *    'name'       => 'arrow-link',
   *    'label'      => __('Arrow link', 'domain'),
   *   ],
   * );
   * ```
   * @see Block_Style::register()
   */
  private static function register_block_style_variants(array $block_styles): void
  {
    foreach ($block_styles as $block_style) {
      $block_style->register();
    }
  }
}
