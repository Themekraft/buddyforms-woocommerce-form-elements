<?php
/*
 * Plugin Name: BuddyForms Form Elements for WooCommerce
 * Plugin URI: http://buddyforms.com/downloads/buddyforms-woocommerce-form-elements/
 * Description: This Plugin adds a new section to the BuddyForms Form Builder with all WooCommerce fields to create Product creation forms for the frontend
 * Version: 1.5.12
 * Requires at least: 5.9
 * Requires PHP: 7.4
 * Requires Plugins: woocommerce, buddyforms
 * Author: ThemeKraft
 * Author URI: https://profiles.wordpress.org/svenl77
 * License: GPLv2 or later
 * Text Domain: buddyforms-woocommerce-form-elements
 * Svn: buddyforms-woocommerce-form-elements
 *
 * @package bf_woo_elem
 *
 ******************************************************************************
 * WC requires at least: 3.7.0
 * WC tested up to: 11.1
 *****************************************************************************
 *
 * This script is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 ****************************************************************************
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action(
	'before_woocommerce_init',
	function () {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
);

if ( ! function_exists( 'buddyforms_addon_plugin_dependencies_slug' ) ) {
	/**
	 * Let the running BuddyForms copy (free or premium) satisfy "Requires Plugins: buddyforms".
	 *
	 * @param string $slug Dependency slug.
	 *
	 * @return string
	 */
	function buddyforms_addon_plugin_dependencies_slug( $slug ) {
		if ( 'buddyforms' === $slug && defined( 'BUDDYFORMS_INSTALL_PATH' ) ) {
			return basename( BUDDYFORMS_INSTALL_PATH );
		}

		return $slug;
	}

	add_filter( 'wp_plugin_dependencies_slug', 'buddyforms_addon_plugin_dependencies_slug' );
}

if ( ! class_exists( 'bf_woo_elem' ) ) {

	require_once dirname( __FILE__ ) . '/includes/bf_woo_elem_fs.php';
	new bf_woo_elem_fs();

	class bf_woo_elem {

		/**
		 * Instance of this class
		 *
		 * @var $instance bf_woo_elem
		 */
		protected static $instance = null;

		private function __construct() {
			$this->constants();
			$this->load_plugin_textdomain();
			require_once BF_WOO_ELEM_INCLUDES_PATH . 'bf_woo_elem_requirements.php';

			if ( bf_woo_elem_requirements::is_buddy_form_active() && bf_woo_elem_requirements::is_woocommerce_active() ) {
				require_once BF_WOO_ELEM_INCLUDES_PATH . 'bf_woo_elem_manager.php';
				new bf_woo_elem_manager();
			}
		}

		private function constants() {
			define( 'BF_WOO_ELEM_BASE_NAME', plugin_basename( __FILE__ ) );
			define( 'BF_WOO_ELEM_BASE_NAMEBASE_FILE', trailingslashit( wp_normalize_path( plugin_dir_path( __FILE__ ) ) ) . 'loader.php' );
			define( 'BF_WOO_ELEM_CSS_PATH', plugin_dir_url( __FILE__ ) . 'assets/css/' );
			define( 'BF_WOO_ELEM_JS_PATH', plugin_dir_url( __FILE__ ) . 'assets/js/' );
			define( 'BF_WOO_ELEM_VIEW_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR );
			define( 'BF_WOO_ELEM_TEMPLATES_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR );
			define( 'BF_WOO_ELEM_INCLUDES_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR );
		}

		/**
		 * Return an instance of this class.
		 *
		 * @return object A single instance of this class.
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null === self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'buddyforms-woocommerce-form-elements', false, basename( dirname( __FILE__ ) ) . '/languages' );
		}

	}

	add_action( 'plugins_loaded', array( 'bf_woo_elem', 'get_instance' ), 1 );
}