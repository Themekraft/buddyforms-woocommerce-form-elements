<?php
/*
 * @package WordPress
 * @subpackage BuddyPress, Woocommerce, BuddyForms
 * @author ThemKraft Dev Team
 * @copyright 2017, Themekraft
 * @link http://buddyforms.com/downloads/buddyforms-woocommerce-form-elements/
 * @license GPLv2 or later
 */

class bf_woo_elem_requirements {

	public static function is_woocommerce_active() {
		self::load_plugins_dependency();

		return is_plugin_active( 'woocommerce/woocommerce.php' );
	}

	public static function load_plugins_dependency() {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	public static function is_buddypress_active() {
		self::load_plugins_dependency();

		return is_plugin_active( 'buddypress/bp-loader.php' );
	}

	public static function is_buddy_form_active() {
		self::load_plugins_dependency();

		$active_plugins = get_option( 'active_plugins', array() );

		if ( is_multisite() ) {
			$network_active_plugins = get_site_option( 'active_sitewide_plugins', array() );
			$active_plugins         = array_merge( $active_plugins, array_keys( $network_active_plugins ) );
		}

		foreach ( $active_plugins as $basename ) {
			if ( 0 === strpos( strtolower( $basename ), 'buddyforms/' ) ||
				0 === strpos( strtolower( $basename ), 'buddyforms-premium/' )
			) {
				return true;
			}
		}

		return false;
	}
}
