<?php
/**
 * Plugin Name: WP AI Toolbox
 * Description: Use the OpenAI API to generate content, summarize text, and create images.
 * Author: Moe Loubani
 * Author URI: https://moe.ca
 * Version: 1.0.1
 * Text Domain: wp-ai-toolbox
 */

//Some constants
define('WPAITB_PATH', plugin_dir_path(__FILE__));
define('WPAITB_URL', plugins_url(__FILE__));

//Add required files
require_once('vendor/autoload.php');

//Start the plugin
\WPAIToolbox\Setup::instance()->start();