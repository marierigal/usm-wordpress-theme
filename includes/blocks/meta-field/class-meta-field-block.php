<?php

namespace USM\Blocks\Meta_Field;

defined('ABSPATH') || exit;

use USM\Traits\Singleton;
use WP_Block;

class Meta_Field_Block
{
  use Singleton;

  /**
   * @var Meta_Field_Block_Component[]
   */
  const CORE_COMPONENTS = [ACF_Fields::class, Rest_Fields::class];

  /**
   * Transform style array to style string.
   */
  public static function array_style_to_string(array $styles): string
  {
    return array_reduce(array_keys($styles), function ($acc, $key) use ($styles) {
      if ($styles[$key] === null) return $acc;
      return $acc . trim($key) . ':' . trim($styles[$key]) . ';';
    }, '');
  }

  /**
   * Get object id by object type.
   */
  public static function get_object_id(string $object_type, array $attributes, WP_Block $block): string
  {
    if (
      in_array($object_type, ['post', 'term', 'user'], true) &&
      ($attributes['isCustomSource'] ?? false) &&
      ($attributes['objectId'] ?? false)
    ) {
      return $attributes['objectId'];
    }

    if (in_array($object_type, ['term', 'user'], true)) {
      // Get queried object id.
      return get_queried_object_id();
    }

    return $block->context['postId'] ?? get_queried_object_id();
  }

  /**
   * Get object type.
   */
  public static function get_object_type(string $field_name, array $attributes, WP_Block $block): string
  {
    // Get object type from meta type.
    $object_type = $attributes['metaType'] ?? '';
    if ($object_type) return $object_type;

    // Cache key.
    $cache_key = 'object_type_' . $field_name;

    // Get object_type from the cache.
    $object_type = wp_cache_get($cache_key, 'usm_mfb');
    if ($object_type) return $object_type;

    $object_type = 'post';
    if (self::is_custom_context($field_name, $block, is_category() || is_tag() || is_tax())) {
      $object_type = 'term';
    } elseif (self::is_custom_context($field_name, $block, is_author())) {
      $object_type = 'user';
    }

    // Update cache.
    wp_cache_set($cache_key, $object_type, 'usm_mfb');

    return $object_type;
  }

  /**
   * Initialization
   */
  public static function init(): void
  {
    if (self::has_instance()) return;
    self::get_instance();

    // register components
    self::register_components();

    add_filter('meta_field_block_get_block_content', function (string $content, string $field_type, array $attributes) {
      switch ($field_type) {
        case 'image':
          $size_slug = $attributes['imageSettings']['sizeSlug'] ?: 'medium';
          $aspect_ratio = $attributes['imageSettings']['aspectRatio'] ?: 'auto';
          $scale = $attributes['imageSettings']['scale'] ?: 'contain';
          $width = $attributes['imageSettings']['width'] ?: '100%';
          $height = $attributes['imageSettings']['height'] ?: '100%';

          $value = get_field($attributes['fieldSettings']['key'] ?? '');
          $image_id = is_array($value) ? ($value['ID'] ?? 0) : (is_numeric($value) ? $value : attachment_url_to_postid($value));
          $image = wp_get_attachment_image($image_id, $size_slug, false, [
            'style' => self::array_style_to_string([
              'aspect-ratio' => $aspect_ratio,
              'object-fit'   => $aspect_ratio && $aspect_ratio !== 'auto' ? $scale : 'contain',
              'width'        => $width,
              'height'       => $height,
            ]),
          ]);

          if ($image) $content = $image;
          break;

        case 'url':
          $url = $content;
          $title = $attributes['urlSettings']['title'] ?? $url;
          $target = $attributes['urlSettings']['targetBlank'] ? '_blank' : '_self';
          $rel = $target === '_blank' ? 'rel="noreferrer noopener"' : '';

          /** @noinspection HtmlUnknownAttribute */
          $content = sprintf('<a href="%1$s" target="%3$s" %4$s>%2$s</a>', $url, $title, $target, $rel);
          break;

        default:
          break;
      }

      return $content;
    }, 10, 3);
  }

  /**
   * Check whether if the block is dynamic of static
   */
  public static function is_dynamic_block(array $attributes): bool
  {
    $field_type = $attributes['fieldType'] ?? '';

    return !($field_type === 'acf' && ($attributes['fieldSettings']['isStatic'] ?? false));
  }

  /**
   * Build block markup.
   */
  public static function meta_field_block_get_block_markup(
    string     $content,
    array      $attributes,
    WP_Block   $block,
    int|string $post_id,
    string     $object_type = '',
    string     $classes = '',
    bool       $is_dynamic = true,
    array      $args = []
  ): string
  {
    $field_type = $attributes['fieldSettings']['type'] ?? false;

    // Allow third-party plugins to alter the content.
    $content = apply_filters(
      'meta_field_block_get_block_content',
      $content, $field_type, $attributes, $block, $post_id, $object_type
    );

    $content = is_array($content) || is_object($content)
      ? '<code><em>' . __('This data type is not supported!', 'usm') . '</em></code>'
      : $content;

    if (!$is_dynamic) return $content;

    if (trim($content) === '') {
      // Hide the block.
      if ($attributes['hideEmpty'] ?? false) return '';

      $content = $attributes['emptyMessage'] ?? '';
    }

    $tag_name = $attributes['tagName'] ?? 'div';
    $inner_tag = $tag_name === 'div' ? 'div' : 'span';

    // Build allowed html tags.
    $allowed_html_tags = self::meta_field_block_get_allowed_html_tags();

    // Escape content.
    $content = wp_kses($content, $allowed_html_tags);

    $prefix = $attributes['prefix'] ?? '';

    if (!$prefix && ($attributes['labelAsPrefix'] ?? false)) {
      $field_label = $args['label'] ?? '';
      $prefix = $field_label ?? self::meta_field_block_get_field_label($attributes, $post_id);
    }

    $prefix = $prefix ? sprintf('<%2$s class="prefix">%1$s</%2$s>', wp_kses($prefix, $allowed_html_tags), $inner_tag) : '';
    $suffix = $attributes['suffix'] ?? '';
    $suffix = $suffix ? sprintf('<%2$s class="suffix">%1$s</%2$s>', wp_kses($suffix, $allowed_html_tags), $inner_tag) : '';

    if (!empty($attributes['displayLayout'])) {
      $classes .= " is-display-{$attributes['displayLayout']}";
    }

    if (isset($attributes['textAlign'])) {
      $classes .= " has-text-align-{$attributes['textAlign']}";
    }

    $wrapper_attributes = get_block_wrapper_attributes([
      'class' => trim($classes),
      'style' => $field_type === 'image' ? self::array_style_to_string([
        'display'         => 'flex',
        'align-items'     => 'center',
        'justify-content' => 'center',
        'margin'          => 0,
      ]) : '',
    ]);

    return sprintf('<%3$s %1$s>%2$s</%3$s>', $wrapper_attributes, $prefix . $content . $suffix, $tag_name);
  }

  /**
   * Find MFB not within a core query from an array of blocks
   */
  protected static function find_mfb(string $field_name, array $blocks): bool
  {
    $found = false;

    foreach ($blocks as $block) {
      $block_name = $block['blockName'] ?? '';
      if ('core/query' === $block_name) continue;

      if ('usm/meta-field-block' === $block_name) {
        if ($field_name === ($block['attrs']['fieldName'] ?? '')) return true;
      } elseif (!empty($block['innerBlocks'])) {
        $found = self::find_mfb($field_name, $block['innerBlocks']);
        if ($found) break;
      }
    }

    return $found;
  }

  /**
   * Is the field is in a custom context?
   */
  protected static function is_custom_context(string $field_name, WP_Block $block, bool $condition): bool
  {
    if (!$condition) return false;

    if (!isset($block->context['postId'])) return true;

    global $_wp_current_template_id, $_wp_current_template_content;

    if (!$_wp_current_template_id || !$_wp_current_template_content) return false;

    // Cache key for the blocks of template.
    $cache_key = 'blocks_by_' . str_replace('//', '__', $_wp_current_template_id);

    // Get from the cache.
    $blocks = wp_cache_get($cache_key, 'usm_mfb');
    if (!$blocks) {
      $blocks = parse_blocks($_wp_current_template_content);

      // Update cache.
      if (!empty($blocks)) {
        wp_cache_set($cache_key, $blocks, 'usm_mfb');
      }
    }

    return self::find_mfb($field_name, $blocks);
  }

  /**
   * Get allowed html tags
   */
  protected static function meta_field_block_get_allowed_html_tags(): array
  {
    // Build allowed html tags from $allowedposttags .
    $allowed_html_tags = wp_kses_allowed_html('post');

    // Allow displaying iframe.
    $allowed_html_tags['iframe'] = [
      'src'             => true,
      'srcdoc'          => true,
      'id'              => true,
      'name'            => true,
      'width'           => true,
      'height'          => true,
      'title'           => true,
      'loading'         => true,
      'allow'           => true,
      'allowfullscreen' => true,
      'frameborder'     => true,
      'class'           => true,
      'style'           => true,
    ];

    // SVG.
    $svg_core_attributes = [
      'id'       => true,
      'tabindex' => true,
      'class'    => true,
      'style'    => true,
    ];

    $svg_presentation_attributes = [
      'clip-path'           => true,
      'clip-rule'           => true,
      'color'               => true,
      'color-interpolation' => true,
      'cursor'              => true,
      'display'             => true,
      'fill'                => true,
      'fill-opacity'        => true,
      'fill-rule'           => true,
      'filter'              => true,
      'mask'                => true,
      'opacity'             => true,
      'pointer-events'      => true,
      'shape-rendering'     => true,
      'stroke'              => true,
      'stroke-dasharray'    => true,
      'stroke-dashoffset'   => true,
      'stroke-linecap'      => true,
      'stroke-linejoin'     => true,
      'stroke-miterlimit'   => true,
      'stroke-opacity'      => true,
      'stroke-width'        => true,
      'transform'           => true,
      'vector-effect'       => true,
      'visibility'          => true,
    ];

    // Allow common attributes of SVG images.
    $allowed_html_tags['svg'] = array_merge(
      [
        'viewbox'             => true,
        'xmlns'               => true,
        'preserveaspectratio' => true,
        'width'               => true,
        'height'              => true,
        'x'                   => true,
        'y'                   => true,
        'title'               => true,
        'name'                => true,
        'role'                => true,
        'aria-hidden'         => true,
        'aria-labelledby'     => true,
      ],
      $svg_core_attributes,
      $svg_presentation_attributes
    );

    $allowed_html_tags['g'] = array_merge(
      $svg_core_attributes,
      $svg_presentation_attributes
    );

    $allowed_html_tags['path'] = array_merge(
      [
        'd'          => true,
        'pathLength' => true,
      ],
      $svg_core_attributes,
      $svg_presentation_attributes
    );

    $allowed_html_tags['line'] = array_merge(
      [
        'x1'         => true,
        'y1'         => true,
        'x2'         => true,
        'y2'         => true,
        'pathLength' => true,
      ],
      $svg_core_attributes,
      $svg_presentation_attributes
    );

    $allowed_html_tags['rect'] = array_merge(
      [
        'x'          => true,
        'y'          => true,
        'rx'         => true,
        'ry'         => true,
        'width'      => true,
        'height'     => true,
        'pathLength' => true,
      ],
      $svg_core_attributes,
      $svg_presentation_attributes
    );

    $allowed_html_tags['circle'] = array_merge(
      [
        'cx'         => true,
        'cy'         => true,
        'r'          => true,
        'pathLength' => true,
      ],
      $svg_core_attributes,
      $svg_presentation_attributes
    );

    $allowed_html_tags['ellipse'] = array_merge(
      [
        'cx'         => true,
        'cy'         => true,
        'rx'         => true,
        'ry'         => true,
        'pathLength' => true,
      ],
      $svg_core_attributes,
      $svg_presentation_attributes
    );

    $allowed_html_tags['polygon'] = array_merge(
      [
        'points'     => true,
        'pathLength' => true,
      ],
      $svg_core_attributes,
      $svg_presentation_attributes
    );

    $allowed_html_tags['polyline'] = array_merge(
      [
        'points'     => true,
        'pathLength' => true,
      ],
      $svg_core_attributes,
      $svg_presentation_attributes
    );

    $allowed_html_tags['text'] = array_merge(
      [
        'x'            => true,
        'y'            => true,
        'dx'           => true,
        'dy'           => true,
        'rotate'       => true,
        'lengthAdjust' => true,
        'textLength'   => true,
      ],
      $svg_core_attributes,
      $svg_presentation_attributes
    );

    // Allow third-party to change it.
    return apply_filters('meta_field_block_kses_allowed_html', $allowed_html_tags);
  }

  /**
   * Get field label.
   */
  protected static function meta_field_block_get_field_label(array $attributes, int|string $post_id): string
  {
    $field_label = '';

    $field_type = $attributes['fieldType'] ?? 'meta';
    $field_name = $attributes['fieldName'] ?? '';

    if ('acf' === $field_type && function_exists('get_field_object')) {
      $field = get_field_object($field_name, $post_id);

      if ($field) $field_label = $field['label'] ?? '';
    }

    return $field_label;
  }

  /**
   * Load core components
   */
  protected static function register_components(): void
  {
    $components = apply_filters('meta_field_block_get_components', self::CORE_COMPONENTS);

    foreach ($components as $component) {
      $component::init();
    }
  }
}
