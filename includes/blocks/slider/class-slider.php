<?php

namespace USM\Blocks\Slider;

defined('ABSPATH') || exit;

use USM\Theme;
use USM\Traits\Singleton;

class Slider
{
  use Singleton;

  /**
   * Initialization
   */
  public static function init(): void
  {
    if (self::has_instance()) return;
    self::get_instance();

    // register dependencies
    add_action('wp_enqueue_scripts', [self::class, 'register_dependencies']);
  }

  /**
   * Register dependencies
   */
  public static function register_dependencies(): void
  {
    $handle = Theme::DOMAIN . '-slider';
    wp_register_style($handle, 'https://unpkg.com/flickity@2/dist/flickity.min.css', false, '2');
    wp_register_script($handle, 'https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js', false, '2', true);
  }
}
