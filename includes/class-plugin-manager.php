<?php

namespace USM;

defined('ABSPATH') || exit;

class Plugin_Manager
{
  /**
   * Generate admin notice if some recommended plugins are not installed and/or active
   *
   * @param string[] $recommended_plugins The list of recommended plugins
   *                                      (plugin-folder/plugin-file.php => Plugin name)
   */
  public static function handle_admin_notice(array $recommended_plugins): void
  {
    if (!is_admin()) return;

    $missing_plugins = self::get_missing_plugins(array_keys($recommended_plugins));
    if (!$missing_plugins) return;

    $missing_plugins_list = '<ul style="list-style:inherit;padding-left:2rem;">' . implode(
        '',
        array_map(
          fn(string $plugin_key) => self::generate_plugin_HTML($plugin_key, $recommended_plugins[$plugin_key]),
          $missing_plugins,
        )
      ) . '</ul>';

    Admin_Notice::warning(
      sprintf(
      /* translators: Recommended plugins admin notice message where %s is the list of missing plugins */
        _x('The following plugins are not installed and/or active but are recommended for this theme: %s', 'Admin notice', 'usm'),
        $missing_plugins_list
      ),
    );
  }

  /**
   * Generate plugin activation link
   *
   * @param string $plugin_key Plugin key (plugin-folder/plugin-file.php)
   */
  protected static function generate_activate_link(string $plugin_key): string
  {
    return wp_nonce_url(
      self_admin_url("plugins.php?action=activate&plugin=$plugin_key"),
      "activate-plugin_$plugin_key"
    );
  }

  /**
   * Generate plugin install link
   *
   * @param string $plugin_key Plugin key (plugin-folder/plugin-file.php)
   */
  protected static function generate_install_link(string $plugin_key): string
  {
    $plugin_slug = self::get_slug_from_key($plugin_key);
    return wp_nonce_url(
      self_admin_url("update.php?action=install-plugin&plugin=$plugin_slug"),
      "install-plugin_$plugin_slug"
    );
  }

  protected static function generate_plugin_HTML(string $plugin_key, string $plugin_name): string
  {
    $link = self::is_plugin_installed($plugin_key)
      ? self::generate_activate_link($plugin_key)
      : self::generate_install_link(self::get_slug_from_key($plugin_key));

    $link_label = self::is_plugin_installed($plugin_key)
      ? _x('activate', 'Plugin action', 'usm')
      : _x('install', 'Plugin action', 'usm');

    return sprintf(
      '<li><strong>%1$s</strong> (<a href="%2$s">%3$s</a>)</li>',
      $plugin_name, $link, $link_label
    );
  }

  /**
   * Get missing plugins
   *
   * @param string[] $recommended_plugins_keys The list of recommended plugin keys
   *                                           (plugin-folder/plugin-file.php)
   */
  protected static function get_missing_plugins(array $recommended_plugins_keys): array
  {
    return array_filter($recommended_plugins_keys, fn($plugin) => !self::is_plugin_active($plugin));
  }

  /**
   * Get plugin slug from plugin key
   */
  protected static function get_slug_from_key(string $plugin_key): string
  {
    return explode('/', $plugin_key)[0];
  }

  /**
   * Check if plugin is active
   *
   * @param string $plugin_key Plugin key (plugin-folder/plugin-file.php)
   */
  protected static function is_plugin_active(string $plugin_key): bool
  {
    if (!function_exists('is_plugin_active')) {
      require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    return is_plugin_active($plugin_key);
  }

  /**
   * Check if plugin is installed
   *
   * @param string $plugin_key Plugin key (plugin-folder/plugin-file.php)
   */
  protected static function is_plugin_installed(string $plugin_key): bool
  {
    if (!function_exists('get_plugins')) {
      require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    return array_key_exists($plugin_key, get_plugins());
  }
}
