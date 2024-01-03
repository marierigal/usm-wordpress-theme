<?php

namespace USM\Traits;

defined('ABSPATH') || exit;

trait Singleton
{

  protected static array $instance = [];

  /**
   * Protected class constructor to prevent direct object creation.
   */
  protected function __construct()
  {
  }

  /**
   * To return new or existing Singleton instance of the class from which it is called.
   * As it sets to final it can't be overridden.
   */
  final public static function get_instance(): static
  {
    // Returns name of the class the static method is called in.
    $called_class = get_called_class();

    if (!static::has_instance()) {
      static::$instance[$called_class] = new $called_class();
    }

    return static::$instance[$called_class];
  }

  final protected static function has_instance(): bool
  {
    $called_class = get_called_class();
    return isset(static::$instance[$called_class]);
  }

  /**
   * Prevent object cloning
   */
  final protected function __clone()
  {
  }
}
