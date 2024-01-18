<?php

namespace USM;

defined('ABSPATH') || exit;

use USM\Traits\Singleton;
use WP_Term;

class Taxonomies
{
  use Singleton;

  /**
   * Get terms.
   *
   * @param WP_Term[] $terms The terms to filter.
   *
   * @return WP_Term[] The filtered terms.
   */
  public static function get_terms(array $terms): array
  {
    // sort terms by the 'order' metadata
    usort($terms, function ($a, $b) {
      $a_order = (int)get_term_meta($a->term_id ?? $a, 'order', true) ?? 0;
      $b_order = (int)get_term_meta($b->term_id ?? $b, 'order', true) ?? 0;
      return $a_order - $b_order;
    });

    return $terms;
  }

  public static function init(): void
  {
    if (self::has_instance()) return;
    self::get_instance();

    // register taxonomy
    add_action('get_terms', [self::class, 'get_terms']);
  }
}
