<?php

namespace USM;

defined('ABSPATH') || exit;

readonly class Format_Type
{
  const ASSETS_FILENAME = "index.asset.php";
  const OUTPUT_DIRECTORY = "build/format-types";
  const SCRIPT_FILENAME = "index.js";
  const STYLE_FILENAME = "style-index.css";

  public string $output_directory;
  public string $handle;

  public function __construct(string $name)
  {
    $this->output_directory = self::OUTPUT_DIRECTORY . "/$name/";
    $this->handle = "format-$name";
  }

  public function register(): void
  {
    $assets_file = get_theme_file_path($this->output_directory . self::ASSETS_FILENAME);
    if (!file_exists($assets_file)) return;

    $assets = include $assets_file;
    $dependencies = $assets['dependencies'];
    $version = $assets['version'];

    $script_path = $this->output_directory . self::SCRIPT_FILENAME;
    if (file_exists(get_theme_file_path($script_path))) {
      Theme::enqueue_script($this->handle, $script_path, $dependencies, $version, true);
    }

    $style_path = $this->output_directory . self::STYLE_FILENAME;
    if (file_exists(get_theme_file_path($style_path))) {
      Theme::enqueue_style($this->handle, $style_path, [], $version);
    }
  }
}
