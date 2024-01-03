<?php

/**
 * The following variables are exposed:
 *
 * @var array    $attributes The block attributes
 * @var string   $content    The block default content
 * @var WP_Block $block      The block instance
 */

use USM\Blocks\Meta_Field\ACF_Fields;
use USM\Blocks\Meta_Field\Meta_Field_Block;

$field_name = $attributes['fieldName'] ?? '';
if (empty($field_name)) return '';

// Get object type.
$object_type = Meta_Field_Block::get_object_type($field_name, $attributes, $block);

// Get object id.
$object_id = Meta_Field_Block::get_object_id($object_type, $attributes, $block);

// Get field type.
$field_type = $attributes['fieldType'] ?? 'rest_field';

// Is dynamic block?
$is_dynamic_block = Meta_Field_Block::is_dynamic_block($attributes);

if ($is_dynamic_block) {
  if ($field_type === 'acf') {
    if (function_exists('get_field_object')) {
      $block_value = ACF_Fields::get_field_value($field_name, $object_id, $object_type);
      $content = $block_value['value'] ?? '';

      $content = apply_filters('_meta_field_block_render_dynamic_block', $content, $block_value, $object_id, $object_type, $attributes, $block);
    } else {
      $content = '<code><em>' . __('This data type requires the ACF plugin installed and activated!', 'usm') . '</em></code>';
    }
  } else {
    if (in_array($object_type, ['post', 'term', 'user'], true)) {
      $get_meta_callback = "get_{$object_type}_meta";

      $content = $get_meta_callback($object_id, $field_name, true);
    } else {
      $content = apply_filters('_meta_field_block_get_field_value', $content, $field_name, $object_id, $object_type, $attributes, $block);
    }
  }
} else {
  $content = apply_filters('_meta_field_block_render_static_block', $content, $field_name, $object_id, $object_type, $attributes, $block);
}

// Additional block classes.
$classes = "is-$field_type-field";

if ($attributes['fieldSettings']['type'] ?? false) {
  $classes .= " is-{$attributes['fieldSettings']['type']}-field";
}

// Get the block markup.
echo Meta_Field_Block::meta_field_block_get_block_markup($content, $attributes, $block, $object_id, $object_type, $classes, $is_dynamic_block);
