<?php

/**
 *
 * WordMagic AI
 *
 * Plugin Name:       WordMagic Content Writer
 * Plugin URI:        https://gowebsmarty.com
 * Description:       Most advanced content writer and image generator AI based on GPT-3 OpenAI
 * Version:           1.0.0
 * Author:            Go Web Smarty
 * Author URI:        https://gowebsmarty.com
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       wordmagic-ai
 * Domain Path:       /languages
 *
 * @author      Go Web Smarty
 * @category    Plugin
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
 */

//exit on direct access
if (!defined('WPINC')) {
  die();
}

use WORDMAGIC\WMAI_Engine;

/**
 * Definitions
 */

define('WMAI_VERSION', '1.0.0');
define('WMAI_BASENAME', plugin_basename(__FILE__));
define('WMAI_NAME', 'WordMagic AI');
define('WMAI_PATH', plugin_dir_path(__FILE__));
define('WMAI_URL', plugin_dir_url(__FILE__));
define('WMAI_SLUG', 'wordmagic-ai');
define('WMAI_TX',  'wordmagic-ai'); //textdomain

/**
 * Plugin Activator
 */

function wmai_activate()
{
  ///require_once MDEL_PATH . 'inc/mdel-activator.php';
  ///MDEL_Activator::activate();
}

function wmai_deactivate()
{
  ///require_once MDEL_PATH . 'inc/mdel-deactivator.php';
  ///MDEL_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'wmai_activate');
register_deactivation_hook(__FILE__, 'wmai_deactivate');

/**
 * Initiate
 */
require_once WMAI_PATH . 'vendor/autoload.php';
require_once WMAI_PATH . 'classes/init.php';

function wmai_init()
{
  return WMAI_Engine::instance();
}
add_action("plugins_loaded", "wmai_init", 10);
