<?php

namespace WORDMAGIC;

use WORDMAGIC\WMAI_Admin;

class WMAI_Engine
{
  private static $instance;

  private function __construct()
  {
  }

  public static function instance()
  {

    if (!isset(self::$instance) && !(self::$instance instanceof WMAI_Engine)) {
      self::$instance = new WMAI_Engine();

      self::$instance->reg_admin_hooks();
    }

    return self::$instance;
  }

  private function reg_admin_hooks()
  {
    new WMAI_Admin();
  }
}
