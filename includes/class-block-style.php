<?php

namespace USM;

defined('ABSPATH') || exit;

readonly class Block_Style
{
  public function __construct(public string $block_name, public string $name, public string $label)
  {
  }

  /**
   * Register block style
   */
  public function register(): void
  {
    register_block_style(
      $this->block_name,
      [
        'name'  => $this->name,
        'label' => $this->label,
      ]
    );
  }
}
