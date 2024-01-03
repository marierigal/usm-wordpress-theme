<?php

namespace USM\Blocks\Meta_Field;

defined('ABSPATH') || exit;

use USM\Traits\Singleton;

class ACF_Fields implements Meta_Field_Block_Component
{
  use Singleton;

  /**
   * Don't format fields for rest by default
   */
  public static function api_format(): string
  {
    return 'light';
  }

  /**
   * Format button group fields
   */
  public static function format_field_button_group(mixed $value, array $field, int|string $object_id, mixed $raw_value): string
  {
    return self::format_field_radio($value, $field, $object_id, $raw_value);
  }

  /**
   * Format checkbox fields
   */
  public static function format_field_checkbox(mixed $value, array $field, int|string $object_id, mixed $raw_value): string
  {
    if (!$value) return '';
    if (!is_array($value)) return $value;

    // Allow customizing the separator.
    $separator = apply_filters('meta_field_block_acf_field_choice_item_separator', ', ', $value, $field, $object_id);

    $refine_value = array_filter(
      array_map(
        fn($item) => $item ? (is_array($item) ? ($item['value'] ?? '' ? $item['value'] : '') : $item) : '',
        $value
      )
    );

    return $refine_value
      ? '<span class="value-item">' . implode('</span>' . $separator . '<span class="value-item">', $refine_value) . '</span>'
      : '';
  }

  /**
   * Format datetime fields
   */
  public static function format_field_datetime(mixed $value, array $field, int|string $object_id, mixed $raw_value): string
  {
    if (!$value || !$field['type'] || $field['return_format']) return '';

    $rest_format = '';
    $return_format = $field['return_format'];

    switch ($field['type']) {
      case 'date_picker':
        $rest_format = 'Ymd';
        break;

      case 'time_picker':
        $rest_format = 'H:i:s';
        break;

      case 'date_time_picker':
        $rest_format = 'Y-m-d H:i:s';
        break;

      default:
        break;
    }

    if (!$rest_format) return '';

    $date = DateTime::createFromFormat($rest_format, $value);

    return $date ? $date->format($return_format) : '';
  }

  /**
   * Format image field type
   */
  public static function format_field_image(mixed $value, array $field, int|string $object_id, mixed $raw_value): string
  {
    if (!$value) return '';

    $image_id = is_array($value) ? ($value['ID'] ?? 0) : (is_numeric($value) ? $value : $raw_value);
    if (!$image_id) return '';

    return wp_get_attachment_image($image_id, $field['preview_size'] ?? 'full');
  }

  /**
   * Format link field type
   */
  public static function format_field_link(mixed $value, array $field, int|string $object_id, mixed $raw_value): string
  {
    if (!$value) return '';

    if (!is_array($value)) $value = $raw_value;
    if (!is_array($value)) return $value;

    $value = wp_parse_args(
      $value,
      [
        'title'  => '',
        'url'    => '',
        'target' => '',
      ]
    );

    if (empty($value['url'])) return '';

    $value['title'] = !empty($value['title']) ? $value['title'] : $value['url'];
    $rel = '_blank' === $value['target'] ? ' rel="noreferrer noopener"' : '';

    /** @noinspection HtmlUnknownAttribute */
    return sprintf('<a href="%1$s" target="%3$s" %4$s>%2$s</a>', $value['url'], $value['title'], $value['target'], $rel);
  }

  /**
   * Format page_link field type
   */
  public static function format_field_page_link(mixed $value, array $field, int|string $object_id, mixed $raw_value): string
  {
    $value = !is_array($raw_value) ? [$raw_value] : $raw_value;

    $value_markup = array_filter(
      array_map(
        function ($item) {
          if (is_numeric($item)) return self::get_post_link($item);
          if ($item) return sprintf('<a class="post-link" href="%1$s">%1$s</a>', $item);
          return '';
        },
        $value
      )
    );

    if (count($value_markup) === 0) return '';

    return count($value_markup) > 1
      ? '<ul><li>' . implode('</li><li>', $value_markup) . '</li></ul>'
      : $value_markup[0];
  }

  /**
   * Format post_object field type
   */
  public static function format_field_post_object(mixed $value, array $field, int|string $object_id, mixed $raw_value): string
  {
    $post_array = !is_array($value) ? [$value] : $value;

    $post_array_markup = array_filter(
      array_map(
        fn($post) => self::get_post_link($post),
        $post_array
      )
    );

    if (!count($post_array_markup)) return '';

    return count($post_array_markup) > 1
      ? '<ul><li>' . implode('</li><li>', $post_array_markup) . '</li></ul>'
      : $post_array_markup[0];
  }

  /**
   * Format radio fields
   */
  public static function format_field_radio(mixed $value, array $field, int|string $object_id, mixed $raw_value): string
  {
    if (!$value) return '';

    if (!is_array($value)) return $value;

    return $value['value'] ?? '';
  }

  /**
   * Format relationship field type
   */
  public static function format_field_relationship(mixed $value, array $field, int|string $object_id, mixed $raw_value): string
  {
    $post_array = !is_array($value) ? [$value] : $value;

    $post_array_markup = array_filter(
      array_map(
        fn($post) => self::get_post_link($post),
        $post_array
      )
    );

    return $post_array_markup
      ? '<ul><li>' . implode('</li><li>', $post_array_markup) . '</li></ul>'
      : '';
  }

  /**
   * Format select fields
   */
  public static function format_field_select(mixed $value, array $field, int|string $object_id, mixed $raw_value): string
  {
    // Allow customizing the separator.
    $separator = apply_filters('meta_field_block_acf_field_choice_item_separator', ', ', $value, $field, $object_id);

    $value_array = is_array($value) ? (isset($value['value']) ? [$value] : $value) : [$value];

    $refine_value = array_filter(
      array_map(
        function ($item) {
          if (is_array($item)) {
            return $item['label'] ?? $item['value'] ?? '';
          } else {
            return $item ?: '';
          }
        },
        $value_array
      )
    );

    return $refine_value
      ? implode($separator, $refine_value)
      : '';
  }

  /**
   * Format taxonomy field type
   */
  public static function format_field_taxonomy(mixed $value, array $field, int|string $object_id, mixed $raw_value): string
  {
    $term_array = !is_array($value) ? [$value] : $value;

    $term_array_markup = array_filter(
      array_map(
        fn($term) => $term ? sprintf('<a class="term-link" href="%1$s">%2$s</a>', get_term_link($term), get_term($term)->name) : '',
        $term_array
      )
    );

    return $term_array_markup
      ? '<ul><li>' . implode('</li><li>', $term_array_markup) . '</li></ul>'
      : '';
  }

  /**
   * Format true_false fields
   */
  public static function format_field_true_false(mixed $value, array $field, int|string $object_id, mixed $raw_value): string
  {
    $on_text = $field['ui_on_text'] ?? '';
    $off_text = $field['ui_off_text'] ?? '';

    if (empty($on_text)) {
      /* translators: The display text for the "true" value of the true_false ACF field type */
      $on_text = __('Yes', 'usm');
    }

    if (empty($off_text)) {
      /* translators: The display text for the "false" value of the true_false ACF field type */
      $off_text = __('No', 'usm');
    }

    return $value
      ? apply_filters('meta_field_block_acf_field_true_false_on_text', $on_text, $field, $value, $object_id)
      : apply_filters('meta_field_block_acf_field_true_false_off_text', $off_text, $field, $value, $object_id);
  }

  /**
   * Format user field type
   */
  public static function format_field_user(mixed $value, array $field, int|string $object_id, mixed $raw_value): string
  {
    $user_array = is_array($value) ? (isset($value['display_name']) ? [$value] : $value) : [$value];

    $user_array_markup = array_filter(
      array_map(
        function ($user) {
          $user_id = 0;
          $user_display_name = '';

          if (is_object($user)) {
            $user_id = $user->ID;
            $user_display_name = $user->display_name ?? '';
          } elseif (is_numeric($user)) {
            $user_object = get_userdata($user);
            if ($user_object) {
              $user_id = $user_object->ID;
              $user_display_name = $user_object->display_name ?? '';
            }
          } elseif (is_array($user)) {
            $user_id = $user['ID'] ?? 0;
            $user_display_name = $user['display_name'] ?? '';
          }

          return $user_id && $user_display_name
            ? sprintf('<a class="user-link" href="%1$s">%2$s</a>', get_author_posts_url($user_id), $user_display_name)
            : '';
        },
        $user_array
      )
    );

    return $user_array_markup
      ? '<ul><li>' . implode('</li><li>', $user_array_markup) . '</li></ul>'
      : '';
  }

  /**
   * Filter the formatted value for a given field.
   */
  public static function format_value_for_rest(mixed $value, int|string $object_id, array $field, mixed $raw_value): mixed
  {
    $simple_value_formatted = self::render_field($value, $object_id, $field, $raw_value);

    $rest_formatted_value = [
      'simple_value_formatted' => $simple_value_formatted,
      'value_formatted'        => $value,
      'value'                  => $raw_value,
      'field'                  => $field,
    ];

    return apply_filters(
      'meta_field_block_acf_field_format_value_for_rest',
      $rest_formatted_value,
      $object_id
    );
  }

  /**
   * Get field value for front end by object id and field name.
   */
  public static function get_field_value(string $field_name, int|string $object_id, string $object_type = ''): array
  {
    // Get the id with object type.
    if ($object_type && !in_array($object_type, ['post', 'term', 'user'], true)) {
      $object_id_with_type = $object_type;
    } else {
      $object_id_with_type = in_array($object_type, ['term', 'user'], true) ? $object_type . '_' . $object_id : $object_id;
    }

    $field_object = get_field_object($field_name, $object_id_with_type, false);

    if (!$field_object) {
      // Field key cache.
      $cache_key = 'field_key_' . $field_name;

      // Get field key.
      $field_key = wp_cache_get($cache_key, 'usm_mfb');
      if ($field_key === false) {
        $field_key = self::get_field_key($field_name, $object_id_with_type);

        // Update cache.
        if (!empty($field_key)) {
          wp_cache_set($cache_key, $field_key, 'usm_mfb');
        }
      }

      if ($field_key) {
        $field_object = get_field_object($field_key, $object_id_with_type, false);
      }

      if (!$field_object) {
        // Get a dummy field.
        $field_object = acf_get_valid_field(
          [
            'name' => $field_name,
            'key'  => '',
            'type' => '',
          ]
        );

        // Get value for field.
        $field_object['value'] = acf_get_value($object_id_with_type, $field_object);
      }
    }

    // Field with raw value.
    $field = $field_object;

    // Get raw value first.
    $raw_value = $field_object['value'] ?? '';

    // Format it.
    $field_object['value'] = acf_format_value($raw_value, $object_id_with_type, $field_object);

    return [
      'value' => self::render_field($field_object['value'] ?? '', $object_id, $field_object, $raw_value, $object_type),
      'field' => $field,
    ];
  }

  /**
   * Initialization
   */
  public static function init(): void
  {
    if (self::has_instance()) return;
    self::get_instance();

    // Don't format fields for rest.
    add_filter('acf/settings/rest_api_format', [self::class, 'api_format']);

    // Format special fields for rest.
    add_filter('acf/rest/format_value_for_rest', [self::class, 'format_value_for_rest'], 10, 4);
  }

  /**
   * Display value for ACF fields
   */
  public static function render_acf_field(mixed $value, int|string $object_id, array $field, mixed $raw_value): string
  {
    $field_value = $value;
    $field_type = $field['type'] ?? '';

    if (!$field_type) return $field_value;

    // Not flexible item.
    $format_func = 'format_field_' . $field_type;

    if (is_callable([self::class, $format_func])) {
      $field_value = self::{$format_func}($value, $field, $object_id, $raw_value);
    } elseif (in_array($field_type, ['date_picker', 'time_picker', 'date_time_picker'], true)) {
      $field_value = self::format_field_datetime($value, $field, $object_id, $raw_value);
    }

    return is_array($field_value) || is_object($field_value)
      ? '<code><em>' . __('This data type is not supported! Please contact the author for help.', 'usm') . '</em></code>'
      : $field_value;
  }

  /**
   * Render the field
   */
  public static function render_field(mixed $value, int|string $object_id, array $field, mixed $raw_value, string $object_type = ''): mixed
  {
    // Get the value for rendering.
    $field_value = self::render_acf_field($value, $object_id, $field, $raw_value);

    return apply_filters('meta_field_block_get_acf_field', $field_value, $object_id, $field, $raw_value, $object_type);
  }

  /**
   * Get all ACF fields
   */
  protected static function get_all_fields(): array
  {
    // Try cache.
    $cache_key = 'get_all_acf_fields';
    $cached_fields = wp_cache_get($cache_key, 'usm_mfb');

    if ($cached_fields) return $cached_fields;

    // Query posts.
    $posts = get_posts(
      [
        'posts_per_page'         => -1,
        'post_type'              => 'acf-field',
        'orderby'                => 'menu_order',
        'order'                  => 'ASC',
        'suppress_filters'       => true, // DO NOT allow third-party to modify the query.
        'cache_results'          => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
        'post_status'            => ['publish'],
      ]
    );

    // Loop over posts and populate array of fields.
    $fields = [];

    foreach ($posts as $post) {
      // Deserialize post_content.
      $field = (array)maybe_unserialize($post->post_content);

      // Update attributes.
      $field['ID'] = $post->ID;
      $field['key'] = $post->post_name;
      $field['label'] = $post->post_title;
      $field['name'] = $post->post_excerpt;
      $field['menu_order'] = $post->menu_order;
      $field['parent'] = $post->post_parent;

      $fields[] = $field;
    }

    // Update cache.
    if (!empty($fields)) {
      wp_cache_set($cache_key, $fields, 'usm_mfb');
    }

    return $fields;
  }

  /**
   * Get field key by name.
   */
  protected static function get_field_key(string $field_name, int|string $object_id): bool|string
  {
    $fields = self::get_all_fields();

    $filtered_fields = array_filter(
      $fields,
      fn($field_value) => $field_name === $field_value['name'] ?? ''
    );

    switch (count($filtered_fields)) {
      case 0:
        return false;

      case 1:
        return current($filtered_fields)['key'] ?? '';

      default:
        break;
    }

    // More than 1 item.
    $field_groups_ids = [];
    $field_groups = acf_get_field_groups(['post_id' => $object_id]);

    foreach ($field_groups as $field_group) {
      $field_groups_ids[] = $field_group['ID'];
    }

    // Check if field is part of one of the field groups, return the first one.
    foreach ($filtered_fields as $field) {
      if (in_array($field['parent'] ?? 0, $field_groups_ids, true)) {
        return $field['key'] ?? '';
      }
    }

    return false;
  }

  /**
   * Render a post as link
   */
  protected static function get_post_link(WP_Post|int $post): string
  {
    if (!$post) return '';

    $url = esc_url(get_permalink($post));

    return sprintf(
      '<a class="post-link" href="%1$s" rel="bookmark">%2$s</a>',
      $url,
      esc_html(get_the_title($post))
    );
  }
}
