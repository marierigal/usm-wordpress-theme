<?php

namespace USM\Blocks\Meta_Field;

defined('ABSPATH') || exit;

use USM\Traits\Singleton;

class Rest_Fields
{
  use Singleton;

  /**
   * Load all available rest fields for public object types
   */
  public static function expose_custom_rest_fields(): void
  {
    global $wp_rest_additional_fields;

    $public_object_types = self::get_public_object_types();

    if (!$public_object_types) return;

    $object_types = array_reduce(
      $public_object_types,
      function ($previous, $object_type) use ($wp_rest_additional_fields) {
        if (!isset($wp_rest_additional_fields[$object_type])) return $previous;

        $field_names = array_filter(
          array_keys($wp_rest_additional_fields[$object_type]),
          // Ignore acf.
          fn($key) => 'acf' !== $key
        );

        if ($field_names) {
          $previous[$object_type] = array_values($field_names);
        }

        return $previous;
      },
      []
    );

    if (!$object_types) return;

    foreach ($object_types as $object_type => $fields) {
      register_rest_field(
        $object_type,
        'usm_rest_fields',
        [
          'get_callback' => fn() => $fields,
          'schema'       => [
            'type' => 'array',
          ],
        ]
      );
    }
  }

  /**
   * Initialization
   */
  public static function init(): void
  {
    // Expose all custom rest fields for public object types.
    add_action('rest_api_init', [self::class, 'expose_custom_rest_fields'], PHP_INT_MAX);
  }

  /**
   * Get all public object types
   */
  protected static function get_public_object_types(): array
  {
    // Try cache.
    $cache_key = 'get_public_object_types';
    $object_types = wp_cache_get($cache_key, 'usm_mfb');

    if ($object_types) return $object_types;

    $post_types = get_post_types(
      [
        'public'       => true,
        'show_in_rest' => true,
      ]
    );

    $object_types = [];
    if (!empty($post_types)) {
      $object_types = array_keys($post_types);
    }

    $other_types = apply_filters('meta_field_block_get_additional_public_types_for_rest', []);

    if (!empty($other_types)) {
      $object_types = array_merge($object_types, $other_types);
    }

    wp_cache_set($cache_key, $object_types, 'usm_mfb');

    return $object_types;
  }
}
