<?php

namespace USM;

defined('ABSPATH') || exit;

class Dropdown
{
  /**
   * Get dropdown HTML
   *
   * @param array $args
   *
   * @return string
   */
  public static function get_html(array $args): string
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
    $class = $args['class'];
    $options = $args['options'];
    $selected = $args['selected'];

    $html = '<select name="' . $name . '" id="' . $name . '" class="' . $class . '">';

    if ($args['show_option_all']) {
      $html .= '<option value="0">' . $args['show_option_all'] . '</option>';
    }

    foreach ($options as $value => $label) {
      $html .= '<option value="' . $value . '" ' . selected($selected, $value, false) . '>' . $label . '</option>';
    }

    $html .= '</select>';

    return $html;
  }
}
