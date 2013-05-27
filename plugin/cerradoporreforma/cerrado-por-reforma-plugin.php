<?php
/**
 * @package   cerrado-por-reforma-plugin
 * @author    Lostium Project <found@lostium.com>
 * @license   GPL-2.0+
 * @link      http://cerradoporreforma.com
 * @copyright 2013 Lostium Project
 *
 * @wordpress-plugin
 * Plugin Name: Cerrado Por Reforma
 * Plugin URI:  http://lostiumproject.com
 * Description: Plugin encargado de gestionar toda la infraestructura adicional de cerradoporreforma.com
 * Version:     1.0.0
 * Author:      Lostium Project
 * Author URI:  http://lostiumproject.com
 * Text Domain: cerrado-por-reforma-locale
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'CerradoPorReformaClass.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'CerradoPorReforma', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'CerradoPorReforma', 'deactivate' ) );

CerradoPorReforma::get_instance();