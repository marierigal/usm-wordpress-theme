<?php

namespace USM;

defined('ABSPATH') || exit;

readonly class Block
{
  public function __construct(public string $name)
  {
  }

  /**
   * Register the block
   */
  public function register(): void
  {
    $block_type = register_block_type(get_theme_file_path() . '/build/blocks/' . $this->name . '/block.json');
    if (!$block_type) return;

    foreach ($block_type->editor_script_handles as $handle) {
      wp_set_script_translations($handle, Theme::DOMAIN, get_theme_file_path(Theme::LANGUAGES_DIRECTORY));
    }
  }
}
