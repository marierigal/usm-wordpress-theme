<?php

namespace USM;

defined('ABSPATH') || exit;

class Admin_Notice
{
  /**
   * Display an error admin notice
   */
  public static function error(string $message, bool $is_dismissible = true): void
  {
    self::display($message, 'error', $is_dismissible);
  }

  /**
   * Display an info admin notice
   */
  public static function info(string $message, bool $is_dismissible = true): void
  {
    self::display($message, 'info', $is_dismissible);
  }

  /**
   * Display a success admin notice
   */
  public static function success(string $message, bool $is_dismissible = true): void
  {
    self::display($message, 'success', $is_dismissible);
  }

  /**
   * Display a warning admin notice
   */
  public static function warning(string $message, bool $is_dismissible = true): void
  {
    self::display($message, 'warning', $is_dismissible);
  }

  /**
   * Display an admin notice
   *
   * @param string $message        The message to display (HTML allowed)
   * @param string $type           The type of notice ('info', 'success', 'warning', 'error')
   *                               Default: 'info'
   * @param bool   $is_dismissible Whether the notice is dismissible or not
   */
  protected static function display(string $message, string $type = 'info', bool $is_dismissible = true): void
  {
    add_action('admin_notices', function () use ($message, $type, $is_dismissible) {
      ?>
      <div
        class="notice notice-<?php echo $type; ?> <?php if ($is_dismissible) echo 'is-dismissible' ?>"
      >
        <p><?php echo $message; ?></p>
      </div>
      <?php
    });
  }

}
