<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/*
 * @package WordPress
 * @subpackage BuddyPress, Woocommerce, BuddyForms
 * @author ThemKraft Dev Team
 * @copyright 2017, Themekraft
 * @link http://buddyforms.com/downloads/buddyforms-woocommerce-form-elements/
 * @license GPLv2 or later
 */


// todo: I created this function in buddyforms/includes/functions.php But it is not available. Some priority issues
function buddyforms2_is_gutenberg_page() {
	if ( function_exists( 'is_gutenberg_page' ) &&
		 is_gutenberg_page()
	) {
		// The Gutenberg plugin is on.
		return true;
	}

	require_once ABSPATH . 'wp-admin/includes/screen.php';
	require_once ABSPATH . 'wp-admin/includes/admin.php';
	$current_screen = get_current_screen();
	if ( method_exists( $current_screen, 'is_block_editor' ) &&
		 $current_screen->is_block_editor()
	) {
		// Gutenberg page on 5+.
		return true;
	}

	return false;
}


class bf_woo_elem_form_element {
	private $current_post_id;

	public function __construct() {
		// check if block and get out of here for now
		// @todo Add Block Support if possible
		add_filter( 'buddyforms_create_edit_form_display_element', array( $this, 'buddyforms_woocommerce_create_new_form_builder' ), 1, 2 );

		$this->helpTip();
		add_filter( 'woocommerce_product_type_query', array( $this, 'on_woocommerce_product_type_query' ), 10, 2 );
		add_filter( 'woocommerce_process_product_meta', array( $this, 'on_woocommerce_product_type_query' ), 10, 2 );
		add_filter( 'buddyforms_set_post_id_for_draft', array( $this, 'post_id_for_draft' ), 10, 3 );
		add_filter( 'buddyforms_js_parameters', array( $this, 'buddyforms_js_parameters' ), 10, 2 );

	}

	public function buddyforms_js_parameters( $js_parameters, $current_form_slug ) {
		if ( ! empty( $js_parameters ) && isset( $js_parameters[ $current_form_slug ] ) ) {
			$fields = isset( $js_parameters[ $current_form_slug ]['form_fields'] ) ? $js_parameters[ $current_form_slug ]['form_fields'] : array();
			foreach ( $fields as $field_id => $field ) {
				switch ( $field['slug'] ) {
					case '_gallery':
						$js_parameters[ $current_form_slug ]['form_fields'][ $field_id ]['slug'] = 'product_image_gallery';
						break;
				}
			}
		}
		return $js_parameters;
	}

	public function helpTip() {
		if ( ! is_admin() && ! function_exists( 'wc_help_tip' ) ) {

			/**
			 * Display a WooCommerce help tip.
			 *
			 * @since 2.5.0
			 *
			 * @param  string $tip Help tip text
			 * @param  bool   $allow_html Allow sanitized HTML if true or escape
			 *
			 * @return string
			 */
			function wc_help_tip( $tip, $allow_html = false ) {
				if ( $allow_html ) {
					$tip = wc_sanitize_tooltip( $tip );
				} else {
					$tip = esc_attr( $tip );
				}

				return '<span class="woocommerce-help-tip" data-tip="' . $tip . '"></span>';
			}
		}
	}

	public function on_woocommerce_product_type_query( $override, $product_id ) {
		if ( isset( $product_id ) ) {
			if ( $product_id === $this->current_post_id ) {
				$override = 'simple';
			}
		}

		return $override;
	}

	public function post_id_for_draft( $post_id, $args, $customfields ) {
		if ( ! empty( $args ) && ! empty( $customfields ) && is_array( $customfields ) && empty( $post_id ) ) {
			$exist = false;
			foreach ( $customfields as $field ) {
				if ( $field['slug'] === '_woocommerce' ) {
					$exist = true;

					break;
				}
			}
			if ( $exist ) {
				if ( ! empty( $_GET['post'] ) ) {
					$this->current_post_id = intval( wp_unslash( $_GET['post'] ) );
				} else {
					if ( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ) ) === 'xmlhttprequest' ) {
						exit;
					}
				}
				if ( empty( $this->current_post_id ) ) {
					$post    = get_default_post_to_edit( 'product', true );
					$post_id = $this->current_post_id = $post->ID;
				} else {
					$post_id = $this->current_post_id;
				}
			}
		}

		return $post_id;
	}

	/**
	 * @param Form  $form
	 * @param array $form_args
	 *
	 * @return mixed
	 */
	public function buddyforms_woocommerce_create_new_form_builder( $form, $form_args ) {
		extract( $form_args );

		if ( ! isset( $customfield['type'] ) ) {
			return $form;
		}
		if ( ( $customfield['type'] === 'woocommerce' || $customfield['type'] === 'product-gallery' ) && is_user_logged_in() ) {

			// hack to make the blocks work in WordPress 5
			// todo: this is just a quick solution. we need real Gutenberg support!
			// if(! has_blocks($post)) {
				// return $form;
			// }

			if ( ! empty( $form_args['post_id'] ) ) {
				$product_post = get_post( $form_args['post_id'] );
			} else {
				$product_post          = get_default_post_to_edit( 'product', true );
				$this->current_post_id = $product_post->ID;
			}

			$id    = 'woocommerce-product-data';
			$title = __( 'Product data', 'buddyforms-woocommerce-form-elements' );
			if ( $customfield['type'] === 'product-gallery' ) {
				$id    = 'woocommerce-product-images';
				$title = isset( $customfield['name'] ) ? $customfield['name'] : __( 'Product gallery', 'buddyforms-woocommerce-form-elements' );
			}
			$post             = get_post( $form_args['post_id'] );
			$update_post_type = array(
				'ID'        => $form_args['post_id'],
				'post_name' => $post->post_title,
				'post_type' => 'product',

			);
			wp_update_post( $update_post_type, true );

			$this->add_scripts( $product_post );
			$this->add_styles();
			ob_start();
			echo '<div id="postbox-container" class="woo_elem_container">';
			echo '<div id="' . esc_attr( $id ) . '" class="postbox" >' . "\n";
			echo "<h2 class='hndle bf_woo'><span class='woo_element_span'>{" . esc_html( $title ) . "}</span></h2>\n";
			echo '<div class="inside">' . "\n";
			switch ( $customfield['type'] ) {
				case 'woocommerce':
					WC_Meta_Box_Product_Data::output( $product_post );
					$this->add_general_settings_option( $customfield );
					break;
				case 'product-gallery':
					WC_Meta_Box_Product_Images::output( $product_post );
					echo '<span class="help-inline">' . wp_kses_post( $customfield['description'] ) . '</span>';
					$this->add_product_gallery_option( $customfield );
					break;
			}
			echo "</div>\n";
			echo "</div>\n";
			echo "</div>\n";
			$get_contents = ob_get_contents();
			ob_clean();

			$form->addElement( new Element_HTML( $get_contents ) );
		}

		return $form;
	}

	public static function add_scripts( $post ) {
		global $wp_query;
		ob_start();
		require_once ABSPATH . 'wp-admin/includes/screen.php';

		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';
		$suffix    = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		// Register scripts
		wp_register_script(
			'woocommerce_admin',
			WC()->plugin_url() . '/assets/js/admin/woocommerce_admin' . $suffix . '.js',
			array(
				'jquery',
				'jquery-blockui',
				'jquery-ui-sortable',
				'jquery-ui-widget',
				'jquery-ui-core',
				'jquery-tiptip',
			),
			WC_VERSION
		);
		wp_register_script( 'jquery-blockui', WC()->plugin_url() . '/assets/js/jquery-blockui/jquery.blockUI' . $suffix . '.js', array( 'jquery' ), '2.70', true );
		wp_register_script( 'jquery-tiptip', WC()->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip' . $suffix . '.js', array( 'jquery' ), WC_VERSION, true );
		wp_register_script( 'round', WC()->plugin_url() . '/assets/js/round/round' . $suffix . '.js', array( 'jquery' ), WC_VERSION );
		wp_register_script(
			'wc-admin-meta-boxes',
			WC()->plugin_url() . '/assets/js/admin/meta-boxes' . $suffix . '.js',
			array(
				'jquery',
				'jquery-ui-datepicker',
				'jquery-ui-sortable',
				'accounting',
				'round',
				'wc-enhanced-select',
				'plupload-all',
				'stupidtable',
				'jquery-tiptip',
			),
			WC_VERSION
		);
		wp_register_script( 'zeroclipboard', WC()->plugin_url() . '/assets/js/zeroclipboard/jquery.zeroclipboard' . $suffix . '.js', array( 'jquery' ), WC_VERSION );
		wp_register_script( 'qrcode', WC()->plugin_url() . '/assets/js/jquery-qrcode/jquery.qrcode' . $suffix . '.js', array( 'jquery' ), WC_VERSION );
		wp_register_script( 'stupidtable', WC()->plugin_url() . '/assets/js/stupidtable/stupidtable' . $suffix . '.js', array( 'jquery' ), WC_VERSION );
		wp_register_script( 'serializejson', WC()->plugin_url() . '/assets/js/jquery-serializejson/jquery.serializejson' . $suffix . '.js', array( 'jquery' ), '2.8.1' );
		wp_register_script( 'flot', WC()->plugin_url() . '/assets/js/jquery-flot/jquery.flot' . $suffix . '.js', array( 'jquery' ), WC_VERSION );
		wp_register_script(
			'flot-resize',
			WC()->plugin_url() . '/assets/js/jquery-flot/jquery.flot.resize' . $suffix . '.js',
			array(
				'jquery',
				'flot',
			),
			WC_VERSION
		);
		wp_register_script(
			'flot-time',
			WC()->plugin_url() . '/assets/js/jquery-flot/jquery.flot.time' . $suffix . '.js',
			array(
				'jquery',
				'flot',
			),
			WC_VERSION
		);
		wp_register_script(
			'flot-pie',
			WC()->plugin_url() . '/assets/js/jquery-flot/jquery.flot.pie' . $suffix . '.js',
			array(
				'jquery',
				'flot',
			),
			WC_VERSION
		);
		wp_register_script(
			'flot-stack',
			WC()->plugin_url() . '/assets/js/jquery-flot/jquery.flot.stack' . $suffix . '.js',
			array(
				'jquery',
				'flot',
			),
			WC_VERSION
		);
		wp_register_script(
			'wc-settings-tax',
			WC()->plugin_url() . '/assets/js/admin/settings-views-html-settings-tax' . $suffix . '.js',
			array(
				'jquery',
				'wp-util',
				'underscore',
				'backbone',
				'jquery-blockui',
			),
			WC_VERSION
		);
		wp_register_script(
			'wc-backbone-modal',
			WC()->plugin_url() . '/assets/js/admin/backbone-modal' . $suffix . '.js',
			array(
				'underscore',
				'backbone',
				'wp-util',
			),
			WC_VERSION
		);
		wp_register_script(
			'wc-shipping-zones',
			WC()->plugin_url() . '/assets/js/admin/wc-shipping-zones' . $suffix . '.js',
			array(
				'jquery',
				'wp-util',
				'underscore',
				'backbone',
				'jquery-ui-sortable',
				'wc-enhanced-select',
				'wc-backbone-modal',
			),
			WC_VERSION
		);
		wp_register_script(
			'wc-shipping-zone-methods',
			WC()->plugin_url() . '/assets/js/admin/wc-shipping-zone-methods' . $suffix . '.js',
			array(
				'jquery',
				'wp-util',
				'underscore',
				'backbone',
				'jquery-ui-sortable',
				'wc-backbone-modal',
			),
			WC_VERSION
		);
		wp_register_script(
			'wc-shipping-classes',
			WC()->plugin_url() . '/assets/js/admin/wc-shipping-classes' . $suffix . '.js',
			array(
				'jquery',
				'wp-util',
				'underscore',
				'backbone',
			),
			WC_VERSION
		);
		wp_register_script( 'wc-clipboard', WC()->plugin_url() . '/assets/js/admin/wc-clipboard' . $suffix . '.js', array( 'jquery' ), WC_VERSION );
		wp_register_script( 'select2', WC()->plugin_url() . '/assets/js/select2/select2.full' . $suffix . '.js', array( 'jquery' ), '4.0.3' );
		wp_register_script( 'selectWoo', WC()->plugin_url() . '/assets/js/selectWoo/selectWoo.full' . $suffix . '.js', array( 'jquery' ), '1.0.4' );
		wp_register_script(
			'wc-enhanced-select',
			WC()->plugin_url() . '/assets/js/admin/wc-enhanced-select' . $suffix . '.js',
			array(
				'jquery',
				'selectWoo',
			),
			WC_VERSION
		);
		wp_localize_script(
			'wc-enhanced-select',
			'wc_enhanced_select_params',
			array(
				'i18n_no_matches'           => _x( 'No matches found', 'enhanced select', 'buddyforms-woocommerce-form-elements' ),
				'i18n_ajax_error'           => _x( 'Loading failed', 'enhanced select', 'buddyforms-woocommerce-form-elements' ),
				'i18n_input_too_short_1'    => _x( 'Please enter 1 or more characters', 'enhanced select', 'buddyforms-woocommerce-form-elements' ),
				'i18n_input_too_short_n'    => _x( 'Please enter %qty% or more characters', 'enhanced select', 'buddyforms-woocommerce-form-elements' ),
				'i18n_input_too_long_1'     => _x( 'Please delete 1 character', 'enhanced select', 'buddyforms-woocommerce-form-elements' ),
				'i18n_input_too_long_n'     => _x( 'Please delete %qty% characters', 'enhanced select', 'buddyforms-woocommerce-form-elements' ),
				'i18n_selection_too_long_1' => _x( 'You can only select 1 item', 'enhanced select', 'buddyforms-woocommerce-form-elements' ),
				'i18n_selection_too_long_n' => _x( 'You can only select %qty% items', 'enhanced select', 'buddyforms-woocommerce-form-elements' ),
				'i18n_load_more'            => _x( 'Loading more results&hellip;', 'enhanced select', 'buddyforms-woocommerce-form-elements' ),
				'i18n_searching'            => _x( 'Searching&hellip;', 'enhanced select', 'buddyforms-woocommerce-form-elements' ),
				'ajax_url'                  => admin_url( 'admin-ajax.php' ),
				'search_products_nonce'     => wp_create_nonce( 'search-products' ),
				'search_customers_nonce'    => wp_create_nonce( 'search-customers' ),
				'search_categories_nonce'   => wp_create_nonce( 'search-categories' ),
			)
		);

		// Accounting
		wp_localize_script(
			'accounting',
			'accounting_params',
			array(
				'mon_decimal_point' => wc_get_price_decimal_separator(),
			)
		);

		// WooCommerce admin pages

		wp_enqueue_script( 'woocommerce_admin' );
		wp_enqueue_script( 'wc-enhanced-select' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-ui-autocomplete' );

		$locale  = localeconv();
		$decimal = isset( $locale['decimal_point'] ) ? $locale['decimal_point'] : '.';

		$params = array(
			'i18n_decimal_error'                => sprintf( /* translators: %s: decimal separator. */ __( 'Please enter in decimal (%s) format without thousand separators.', 'buddyforms-woocommerce-form-elements' ), $decimal ),
			'i18n_mon_decimal_error'            => sprintf( /* translators: %s: decimal separator. */ __( 'Please enter in monetary decimal (%s) format without thousand separators and currency symbols.', 'buddyforms-woocommerce-form-elements' ), wc_get_price_decimal_separator() ),
			'i18n_country_iso_error'            => __( 'Please enter in country code with two capital letters.', 'buddyforms-woocommerce-form-elements' ),
			'i18n_sale_less_than_regular_error' => __( 'Please enter in a value less than the regular price.', 'buddyforms-woocommerce-form-elements' ),
			'decimal_point'                     => $decimal,
			'mon_decimal_point'                 => wc_get_price_decimal_separator(),
			'strings'                           => array(
				'import_products' => __( 'Import', 'buddyforms-woocommerce-form-elements' ),
				'export_products' => __( 'Export', 'buddyforms-woocommerce-form-elements' ),
			),
			'urls'                              => array(
				'import_products' => esc_url_raw( admin_url( 'edit.php?post_type=product&page=product_importer' ) ),
				'export_products' => esc_url_raw( admin_url( 'edit.php?post_type=product&page=product_exporter' ) ),
			),
		);

		wp_localize_script( 'woocommerce_admin', 'woocommerce_admin', $params );

		// Products
		if ( in_array( $screen_id, array( 'edit-product' ), true ) ) {
			wp_register_script(
				'woocommerce_quick-edit',
				WC()->plugin_url() . '/assets/js/admin/quick-edit' . $suffix . '.js',
				array(
					'jquery',
					'woocommerce_admin',
				),
				WC_VERSION
			);
			wp_enqueue_script( 'woocommerce_quick-edit' );
		}
		// Meta boxes
		wp_enqueue_media();
		wp_register_script( 'wc-admin-product-meta-boxes', WC()->plugin_url() . '/assets/js/admin/meta-boxes-product' . $suffix . '.js', array( 'jquery' ), WC_VERSION );
		wp_register_script(
			'wc-admin-variation-meta-boxes',
			WC()->plugin_url() . '/assets/js/admin/meta-boxes-product-variation' . $suffix . '.js',
			array(
				'wc-admin-meta-boxes',
				'serializejson',
				'media-models',
			),
			WC_VERSION
		);

		wp_enqueue_script( 'wc-admin-product-meta-boxes' );
		wp_enqueue_script( 'wc-admin-variation-meta-boxes' );
		$params = array(
			'post_id'                             => isset( $post->ID ) ? $post->ID : '',
			'plugin_url'                          => WC()->plugin_url(),
			'ajax_url'                            => admin_url( 'admin-ajax.php' ),
			'woocommerce_placeholder_img_src'     => wc_placeholder_img_src(),
			'add_variation_nonce'                 => wp_create_nonce( 'add-variation' ),
			'link_variation_nonce'                => wp_create_nonce( 'link-variations' ),
			'delete_variations_nonce'             => wp_create_nonce( 'delete-variations' ),
			'load_variations_nonce'               => wp_create_nonce( 'load-variations' ),
			'save_variations_nonce'               => wp_create_nonce( 'save-variations' ),
			'bulk_edit_variations_nonce'          => wp_create_nonce( 'bulk-edit-variations' ),
			'i18n_link_all_variations'            => esc_js( sprintf( /* translators: %d: maximum number of variations per run. */ __( 'Are you sure you want to link all variations? This will create a new variation for each and every possible combination of variation attributes (max %d per run).', 'buddyforms-woocommerce-form-elements' ), defined( 'WC_MAX_LINKED_VARIATIONS' ) ? WC_MAX_LINKED_VARIATIONS : 50 ) ),
			'i18n_enter_a_value'                  => esc_js( __( 'Enter a value', 'buddyforms-woocommerce-form-elements' ) ),
			'i18n_enter_menu_order'               => esc_js( __( 'Variation menu order (determines position in the list of variations)', 'buddyforms-woocommerce-form-elements' ) ),
			'i18n_enter_a_value_fixed_or_percent' => esc_js( __( 'Enter a value (fixed or %)', 'buddyforms-woocommerce-form-elements' ) ),
			'i18n_delete_all_variations'          => esc_js( __( 'Are you sure you want to delete all variations? This cannot be undone.', 'buddyforms-woocommerce-form-elements' ) ),
			'i18n_last_warning'                   => esc_js( __( 'Last warning, are you sure?', 'buddyforms-woocommerce-form-elements' ) ),
			'i18n_choose_image'                   => esc_js( __( 'Choose an image', 'buddyforms-woocommerce-form-elements' ) ),
			'i18n_set_image'                      => esc_js( __( 'Set variation image', 'buddyforms-woocommerce-form-elements' ) ),
			'i18n_variation_added'                => esc_js( __( 'variation added', 'buddyforms-woocommerce-form-elements' ) ),
			'i18n_variations_added'               => esc_js( __( 'variations added', 'buddyforms-woocommerce-form-elements' ) ),
			'i18n_no_variations_added'            => esc_js( __( 'No variations added', 'buddyforms-woocommerce-form-elements' ) ),
			'i18n_remove_variation'               => esc_js( __( 'Are you sure you want to remove this variation?', 'buddyforms-woocommerce-form-elements' ) ),
			'i18n_scheduled_sale_start'           => esc_js( __( 'Sale start date (YYYY-MM-DD format or leave blank)', 'buddyforms-woocommerce-form-elements' ) ),
			'i18n_scheduled_sale_end'             => esc_js( __( 'Sale end date (YYYY-MM-DD format or leave blank)', 'buddyforms-woocommerce-form-elements' ) ),
			'i18n_edited_variations'              => esc_js( __( 'Save changes before changing page?', 'buddyforms-woocommerce-form-elements' ) ),
			'i18n_variation_count_single'         => esc_js( __( '%qty% variation', 'buddyforms-woocommerce-form-elements' ) ),
			'i18n_variation_count_plural'         => esc_js( __( '%qty% variations', 'buddyforms-woocommerce-form-elements' ) ),
			'variations_per_page'                 => absint( apply_filters( 'woocommerce_admin_meta_boxes_variations_per_page', 15 ) ),
		);

		wp_localize_script( 'wc-admin-variation-meta-boxes', 'woocommerce_admin_meta_boxes_variations', $params );

		if ( in_array( str_replace( 'edit-', '', $screen_id ), wc_get_order_types( 'order-meta-boxes' ) ) ) {
			$default_location = wc_get_customer_default_location();

			wp_enqueue_script( 'wc-admin-order-meta-boxes', WC()->plugin_url() . '/assets/js/admin/meta-boxes-order' . $suffix . '.js', array( 'wc-admin-meta-boxes', 'wc-backbone-modal', 'selectWoo', 'wc-clipboard' ), WC_VERSION );
			wp_localize_script(
				'wc-admin-order-meta-boxes',
				'woocommerce_admin_meta_boxes_order',
				array(
					'countries'              => wp_json_encode( array_merge( WC()->countries->get_allowed_country_states(), WC()->countries->get_shipping_country_states() ) ),
					'i18n_select_state_text' => esc_attr__( 'Select an option&hellip;', 'buddyforms-woocommerce-form-elements' ),
					'default_country'        => isset( $default_location['country'] ) ? $default_location['country'] : '',
					'default_state'          => isset( $default_location['state'] ) ? $default_location['state'] : '',
					'placeholder_name'       => esc_attr__( 'Name (required)', 'buddyforms-woocommerce-form-elements' ),
					'placeholder_value'      => esc_attr__( 'Value (required)', 'buddyforms-woocommerce-form-elements' ),
				)
			);
		}

		if ( in_array( $screen_id, array( 'shop_coupon', 'edit-shop_coupon' ) ) ) {
			wp_enqueue_script( 'wc-admin-coupon-meta-boxes', WC()->plugin_url() . '/assets/js/admin/meta-boxes-coupon' . $suffix . '.js', array( 'wc-admin-meta-boxes' ), WC_VERSION );
			wp_localize_script(
				'wc-admin-coupon-meta-boxes',
				'woocommerce_admin_meta_boxes_coupon',
				array(
					'generate_button_text' => esc_html__( 'Generate coupon code', 'buddyforms-woocommerce-form-elements' ),
					'characters'           => apply_filters( 'woocommerce_coupon_code_generator_characters', 'ABCDEFGHJKMNPQRSTUVWXYZ23456789' ),
					'char_length'          => apply_filters( 'woocommerce_coupon_code_generator_character_length', 8 ),
					'prefix'               => apply_filters( 'woocommerce_coupon_code_generator_prefix', '' ),
					'suffix'               => apply_filters( 'woocommerce_coupon_code_generator_suffix', '' ),
				)
			);
		}

		$post_id  = isset( $post->ID ) ? $post->ID : '';
		$currency = '';
		$order    = wc_get_order( $post_id );
		$currency = $order ? $order->get_currency() : '';
		$params   = array(
			'remove_item_notice'            => __( 'Are you sure you want to remove the selected items? If you have previously reduced this item\'s stock, or this order was submitted by a customer, you will need to manually restore the item\'s stock.', 'buddyforms-woocommerce-form-elements' ),
			'i18n_select_items'             => __( 'Please select some items.', 'buddyforms-woocommerce-form-elements' ),
			'i18n_do_refund'                => __( 'Are you sure you wish to process this refund? This action cannot be undone.', 'buddyforms-woocommerce-form-elements' ),
			'i18n_delete_refund'            => __( 'Are you sure you wish to delete this refund? This action cannot be undone.', 'buddyforms-woocommerce-form-elements' ),
			'i18n_delete_tax'               => __( 'Are you sure you wish to delete this tax column? This action cannot be undone.', 'buddyforms-woocommerce-form-elements' ),
			'remove_item_meta'              => __( 'Remove this item meta?', 'buddyforms-woocommerce-form-elements' ),
			'remove_attribute'              => __( 'Remove this attribute?', 'buddyforms-woocommerce-form-elements' ),
			'name_label'                    => __( 'Name', 'buddyforms-woocommerce-form-elements' ),
			'remove_label'                  => __( 'Remove', 'buddyforms-woocommerce-form-elements' ),
			'click_to_toggle'               => __( 'Click to toggle', 'buddyforms-woocommerce-form-elements' ),
			'values_label'                  => __( 'Value(s)', 'buddyforms-woocommerce-form-elements' ),
			'text_attribute_tip'            => __( 'Enter some text, or some attributes by pipe (|) separating values.', 'buddyforms-woocommerce-form-elements' ),
			'visible_label'                 => __( 'Visible on the product page', 'buddyforms-woocommerce-form-elements' ),
			'used_for_variations_label'     => __( 'Used for variations', 'buddyforms-woocommerce-form-elements' ),
			'new_attribute_prompt'          => __( 'Enter a name for the new attribute term:', 'buddyforms-woocommerce-form-elements' ),
			'calc_totals'                   => __( 'Calculate totals based on order items, discounts, and shipping?', 'buddyforms-woocommerce-form-elements' ),
			'calc_line_taxes'               => __( 'Calculate line taxes? This will calculate taxes based on the customers country. If no billing/shipping is set it will use the store base country.', 'buddyforms-woocommerce-form-elements' ),
			'copy_billing'                  => __( 'Copy billing information to shipping information? This will remove any currently entered shipping information.', 'buddyforms-woocommerce-form-elements' ),
			'load_billing'                  => __( 'Load the customer\'s billing information? This will remove any currently entered billing information.', 'buddyforms-woocommerce-form-elements' ),
			'load_shipping'                 => __( 'Load the customer\'s shipping information? This will remove any currently entered shipping information.', 'buddyforms-woocommerce-form-elements' ),
			'featured_label'                => __( 'Featured', 'buddyforms-woocommerce-form-elements' ),
			'prices_include_tax'            => esc_attr( get_option( 'woocommerce_prices_include_tax' ) ),
			'tax_based_on'                  => esc_attr( get_option( 'woocommerce_tax_based_on' ) ),
			'round_at_subtotal'             => esc_attr( get_option( 'woocommerce_tax_round_at_subtotal' ) ),
			'no_customer_selected'          => __( 'No customer selected', 'buddyforms-woocommerce-form-elements' ),
			'plugin_url'                    => WC()->plugin_url(),
			'ajax_url'                      => admin_url( 'admin-ajax.php' ),
			'order_item_nonce'              => wp_create_nonce( 'order-item' ),
			'add_attribute_nonce'           => wp_create_nonce( 'add-attribute' ),
			'save_attributes_nonce'         => wp_create_nonce( 'save-attributes' ),
			'calc_totals_nonce'             => wp_create_nonce( 'calc-totals' ),
			'get_customer_details_nonce'    => wp_create_nonce( 'get-customer-details' ),
			'search_products_nonce'         => wp_create_nonce( 'search-products' ),
			'grant_access_nonce'            => wp_create_nonce( 'grant-access' ),
			'revoke_access_nonce'           => wp_create_nonce( 'revoke-access' ),
			'add_order_note_nonce'          => wp_create_nonce( 'add-order-note' ),
			'delete_order_note_nonce'       => wp_create_nonce( 'delete-order-note' ),
			'calendar_image'                => WC()->plugin_url() . '/assets/images/calendar.png',
			'post_id'                       => isset( $post->ID ) ? $post->ID : '',
			'base_country'                  => WC()->countries->get_base_country(),
			'currency_format_num_decimals'  => wc_get_price_decimals(),
			'currency_format_symbol'        => get_woocommerce_currency_symbol( $currency ),
			'currency_format_decimal_sep'   => esc_attr( wc_get_price_decimal_separator() ),
			'currency_format_thousand_sep'  => esc_attr( wc_get_price_thousand_separator() ),
			'currency_format'               => esc_attr(
				str_replace(
					array( '%1$s', '%2$s' ),
					array(
						'%s',
						'%v',
					),
					get_woocommerce_price_format()
				)
			), // For accounting JS
			'rounding_precision'            => wc_get_rounding_precision(),
			'tax_rounding_mode'             => WC_TAX_ROUNDING_MODE,
			'product_types'                 => array_unique(
				array_merge(
					array(
						'simple',
						'grouped',
						'variable',
						'external',
					),
					array_keys( wc_get_product_types() )
				)
			),
			'i18n_download_permission_fail' => __( 'Could not grant access - the user may already have permission for this file or billing email is not set. Ensure the billing email is set, and the order has been saved.', 'buddyforms-woocommerce-form-elements' ),
			'i18n_permission_revoke'        => __( 'Are you sure you want to revoke access to this download?', 'buddyforms-woocommerce-form-elements' ),
			'i18n_tax_rate_already_exists'  => __( 'You cannot add the same tax rate twice!', 'buddyforms-woocommerce-form-elements' ),
			'i18n_product_type_alert'       => __( 'Your product has variations! Before changing the product type, it is a good idea to delete the variations to avoid errors in the stock reports.', 'buddyforms-woocommerce-form-elements' ),
			'i18n_delete_note'              => __( 'Are you sure you wish to delete this note? This action cannot be undone.', 'buddyforms-woocommerce-form-elements' ),
		);

		wp_localize_script( 'wc-admin-meta-boxes', 'woocommerce_admin_meta_boxes', $params );
		ob_clean();

	}

	public static function add_styles() {
		global $wp_scripts;
		require_once ABSPATH . 'wp-admin/includes/screen.php';
		$screen         = get_current_screen();
		$screen_id      = $screen ? $screen->id : '';
		$jquery_version = isset( $wp_scripts->registered['jquery-ui-core']->ver ) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.11.4';

		// Register admin styles
		wp_register_style( 'woocommerce_admin_menu_styles', WC()->plugin_url() . '/assets/css/menu.css', array(), WC_VERSION );
		wp_register_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );
		wp_register_style( 'jquery-ui-style', BF_WOO_ELEM_CSS_PATH . 'jquery-ui.css', array(), $jquery_version );

		// Sitewide menu CSS
		wp_enqueue_style( 'woocommerce_admin_menu_styles' );

		// Admin styles for WC pages only
		wp_enqueue_style( 'woocommerce_admin_styles' );
		wp_enqueue_style( 'jquery-ui-style' );

		/**
		 * @deprecated 2.3
		 */
		if ( has_action( 'woocommerce_admin_css' ) ) {
			do_action( 'woocommerce_admin_css' );
			_deprecated_function( 'The woocommerce_admin_css action', '2.3', 'admin_enqueue_scripts' );
		}

		wp_enqueue_style( 'buddyforms-woocommerce', BF_WOO_ELEM_CSS_PATH . 'buddyforms-woocommerce.css' );
	}

	public function add_product_gallery_option( $option ) {
		wp_enqueue_script( 'product_gallery', BF_WOO_ELEM_JS_PATH . 'bf_woo_product_gallery.js', array( 'jquery' ), null, true );
		wp_localize_script( 'product_gallery', 'product_gallery_param', $option );
	}

	public function add_general_settings_option( $option ) {
		$product_data_tabs_unhandled = bf_woo_elem_manager::get_unhandled_tabs();
		$product_data_tabs           = array_keys( apply_filters( 'woocommerce_product_data_tabs', array_merge( $product_data_tabs_unhandled, array() ) ) );
		if ( ! empty( $product_data_tabs ) ) {
			$product_data_tabs_implemented = apply_filters( 'bf_woo_element_woo_implemented_tab', array() );
			if ( ! empty( $product_data_tabs_implemented ) ) {
				$product_data_tabs = array_diff( $product_data_tabs, $product_data_tabs_implemented );
			}
			if ( ! empty( $product_data_tabs ) ) {
				$option['disable_tabs'] = $product_data_tabs;
			}
		}
		$option['debug']        = SCRIPT_DEBUG;
		$option['debug_hidden'] = false;
		wp_enqueue_script( 'general_settings', BF_WOO_ELEM_JS_PATH . 'bf_woo_general_settings.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'hooks_settings', BF_WOO_ELEM_JS_PATH . 'bf_woo_hooks.js', array( 'jquery' ), null, true );
		wp_localize_script( 'general_settings', 'general_settings_param', $option );
	}
}
