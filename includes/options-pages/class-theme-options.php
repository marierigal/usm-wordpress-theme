<?php

namespace USM\Options_Pages;

use USM\Traits\Singleton;

defined('ABSPATH') || exit;

class Theme_Options
{
  use Singleton;

  public static function add_options_page(): void
  {
    acf_add_options_page([
      'page_title'      => _x('Theme options', 'Options page title', 'usm'),
      'menu_slug'       => 'theme-options',
      'parent_slug'     => 'options-general.php',
      'position'        => '',
      'redirect'        => false,
      'menu_title'      => _x('Theme options', 'Options page menu title', 'usm'),
      'description'     => _x('Define theme options', 'Options page description', 'usm'),
      'update_button'   => _x('Update', 'Options page update button', 'usm'),
      'updated_message' => _x('Theme options updated', 'Options page updated message', 'usm'),
      'capability'      => 'edit_theme_options',
      'autoload'        => true,
    ]);
  }

  public static function init(): void
  {
    if (self::has_instance()) return;
    self::get_instance();

    // add ACF options page
    add_action('init', [self::class, 'add_options_page']);
  }
}
