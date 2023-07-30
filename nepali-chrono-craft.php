<?php

/**
 * @link              https://wprefers.com/
 * @since             1.0.0
 * @package           Nepali_Chrono_Craft
 *
 * @wordpress-plugin
 * Plugin Name:       Nepali Chrono Craft
 * Plugin URI:        https://wordpress.org/plugins/nepali-chrono-craft/
 * Description:       <code><strong>Nepali Chrono Craft for WordPress</strong></code> plugin allows you to Seamlessly convert and display dates between Gregorian and Nepali calendars, celebrating Nepal's rich heritage on your website.</a>
 * Version:           1.0.0
 * Author:            WPRefers
 * Author URI:        https://wprefers.com/
 * Text Domain:       nepali-chrono-craft
 *
 *
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

require_once("vendor/autoload.php");

new \WPRefers\NepaliChronoCraft\NepaliChronoCraft();