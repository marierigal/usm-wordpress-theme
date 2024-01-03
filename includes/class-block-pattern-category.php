<?php

namespace USM;

defined('ABSPATH') || exit;

readonly class Block_Pattern_Category
{
  public function __construct(public string $name, public string $label)
  {
  }

  /**
   * Register block pattern category
   */
  public function register(): void
  {
    register_block_pattern_category($this->name, ['label' => $this->label]);
  }
}
