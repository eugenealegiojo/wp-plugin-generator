<?php
/**
 * Plugin Name: Ten Plugin
 * Plugin URI: 
 * Description: Generated plugin description.
 * Version: 1.0.0
 * Author: me
 * Author URI: 
 * License: GPL
 * Text Domain: ten-plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Composer class autoloader
 */
require_once __DIR__ . '/vendor/autoload.php';

\TenPlugin\Plugin::instance();
