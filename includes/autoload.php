<?php

namespace USM;

defined('ABSPATH') || exit;

/**
 * Registers the autoloader for classes
 */
spl_autoload_register(function ($classname) {
  $parts = explode('\\', strtolower(str_replace('_', '-', $classname)));
  $class = array_pop($parts);

  // Remove main namespace
  array_shift($parts);

  $file_path = implode(DIRECTORY_SEPARATOR, [dirname(__FILE__), ...$parts, 'class-' . $class . '.php',]);

  if (file_exists($file_path)) {
    require_once $file_path;
  }
});
