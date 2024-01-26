<?php

namespace USM;

defined('ABSPATH') || exit;

class DOM_Helper
{
  /**
   * Transform a styles array to a styles string.
   */
  public static function format_styles_array(array $styles): string
  {
    return array_reduce(array_keys($styles), function ($acc, $key) use ($styles) {
      if ($styles[$key] === null) return $acc;
      return $acc . trim($key) . ':' . trim($styles[$key]) . ';';
    }, '');
  }

  /**
   * Get dropdown HTML
   *
   * @param array $args Dropdown arguments.
   *                    ```
   *                    'show_option_all' => (string)   Text to show as the first dropdown option.
   *                    'name'            => (string)   Name attribute of the select element.
   *                    'class'           => (string)   Class attribute of the select element.
   *                    'options'         => (array)    value => label pairs for the select options.
   *                    'selected'        => (string)   Value of the selected option.
   *                    ```
   *
   * @return string
   */
  public static function generate_dropdown_HTML(array $args): string
  {
    $defaults = [
      'show_option_all' => '',
      'name'            => '',
      'class'           => '',
      'options'         => [],
      'selected'        => '',
    ];

    $args = wp_parse_args($args, $defaults);

    $name = $args['name'];

    $html = "<select id=\"$name\" name=\"$name\" class=\"{$args['class']}\">";

    if ($args['show_option_all']) {
      $html .= "<option value=\"0\">{$args['show_option_all']}</option>";
    }

    foreach ($args['options'] as $value => $label) {
      $selected_attr = selected($args['selected'], $value, false);
      $html .= "<option value=\"$value\" $selected_attr>$label</option>";
    }

    $html .= '</select>';

    return $html;
  }
}
