/*
 * @package WordPress
 * @subpackage BuddyPress, Woocommerce, BuddyForms
 * @author ThemKraft Dev Team
 * @copyright 2017, Themekraft
 * @link http://buddyforms.com/downloads/buddyforms-woocommerce-form-elements/
 * @license GPLv2 or later
 */
jQuery('#woocommerce-product-data').prepend(
    jQuery('<div>', {
        'class': 'woo_general_loader'
    }).text('loading...')
);
jQuery(".bf-submit").on('click',function(event){

    var validation_message = '';
    var continue_submit = true;
    var visible_tabs = jQuery('ul.wc-tabs li:visible');
    visible_tabs.each(function (index, value) {
        visible_tabs.removeClass('active');
        jQuery(value).addClass('active').find('a').click();

        jQuery('input,textarea,select').filter('[required]:visible').each(function(i, requiredField){

            if(jQuery(requiredField).val()=='')
            {
                //This is to garantee that the rules of the form will
                //execute when the page contains more than one form

                var formSlug = jQuery('input[name="form_slug"]');
                if (formSlug.length > 0 && formSlug.val()) {
                    var buddyformsForm = jQuery("form[id='buddyforms_form_" + formSlug.val() + "']");
                    var validator = jQuery( buddyformsForm ).validate();
                    validator.form();
                }
                continue_submit = false;
                return false;
            }
        });

        if(!continue_submit){
            return false;
        }

    });
    if(!continue_submit){
        return false;
    }

return true;
})

jQuery(document).ready(function ($) {


    $('form').trigger("reset");


    var select_product_type = jQuery('select#product-type'),
        virtual = jQuery('#_virtual'),
        downloadable = jQuery('#_downloadable'),
        regular_price = jQuery('#_regular_price'),
        sale_price = jQuery('#_sale_price'),
        sale_price_dates_from = jQuery('#_sale_price_dates_from'),
        sale_price_dates_to = jQuery('#_sale_price_dates_to'),
        sku = jQuery('#_sku'),
        sold_individually = $("#_sold_individually"),
        manage_stock = $("#_manage_stock"),
        stock_status = $("#_stock_status"),
        stock = $("#_stock"),
        backorders = $("#_backorders"),
        weight = $("#_weight"),
        width = $("input[name='_width']"),
        height = $("input[name='_height']"),
        length = $("input[name='_length']"),
        shipping_class = $("#product_shipping_class"),
        tabs_hided = [],//Array of classes of the tabs to be hide
        attribute_tab = $('.attribute_tab'),
        attribute_container = $('#product_attributes'),
        variations_tab = $('.variations_tab'),
        variations_container = $('#variable_product_options'),
        advanced_tab = jQuery('.advanced_tab'),
        advanced_container = jQuery('#advanced_product_data'),
        purchase_note = $('#_purchase_note'),
        menu_order_input = $('#menu_order'),
        reviews_allowed = $('#comment_status'),
        main_container = jQuery('#woocommerce-product-data'),
        val
    ;



    /**
     * Determine wish tab will be default
     */
    function determine_default_tab() {
        var visible_tabs = jQuery('ul.wc-tabs li:visible');
        visible_tabs.removeClass('active')
            .first()
            .addClass('active')
            .find('a')
            .click();
        if (visible_tabs.length === 0) {
            //Hide the entire container
            jQuery('#woocommerce-product-data').hide();
        }
    }

    /**
     * Hide the tabs in the array and his container
     * @param tabs
     */
    function hideTabs(tabs) {
        jQuery.each(tabs, function (i, val) {
            var tabLi = jQuery('.' + val),
                tabTarget = tabLi.find('a').attr('href');
            tabLi.hide();
            jQuery(tabTarget).hide();
        });
    }

    function determine_when_is_required(current_type) {
        set_default_required();
        switch (current_type) {
            case 'simple':
            case 'external':
                //REGULAR PRICE
                if (general_settings_param.product_regular_price) {
                    var regular_price_opt = general_settings_param.product_regular_price;
                    if (regular_price_opt === "required") {
                        regular_price.attr("required", true);
                        $(regular_price).rules("add",{required:true,messages: {
                            required: "Required Field",

                        }});
                        if (general_settings_param.debug) console.log('Regular Price is required now');
                    }
                }

                //SALES PRICE
                if (general_settings_param.product_sales_price) {
                    var sales_price_opt = general_settings_param.product_sales_price;
                    if (sales_price_opt === "required") {
                        sale_price.attr("required", true);
                        $(sale_price).rules("add",{required:true,messages: {
                            required: "Required Field",

                        }});
                        if (general_settings_param.debug) console.log('Sales Price is required now');
                    }
                }

                //SALES PRICE DATE
                if (general_settings_param.product_sales_price_dates) {
                    var sales_price_date_opt = general_settings_param.product_sales_price_dates;
                    if (sales_price_date_opt === "required") {
                        sale_price_dates_from.attr("required", true);
                        sale_price_dates_to.attr("required", true);
                        jQuery(sale_price_dates_from).rules("add",{required:true,messages: {
                            required: "Required Field",

                        }});

                        jQuery(sale_price_dates_to).rules("add",{required:true,messages: {
                            required: "Required Field",

                        }});


                        $(".cancel_sale_schedule").hide();
                        $(".sale_schedule").hide();
                        $(".sale_price_dates_fields").show();
                        if (general_settings_param.debug) console.log('Sales Price Date is required now and showed');
                    }
                }

                break;
        }

        //SKU
        if (general_settings_param.product_sku) {
            var sku_option = general_settings_param.product_sku;
            if (sku_option === "required") {
                sku.attr("required", true);
                jQuery(sku).rules("add",{required:true,messages: {
                    required: "Required Field",

                }});

                if (general_settings_param.debug) console.log('SKU is required');
            }
        }

    }

    function set_default_required() {
        regular_price.removeAttr('required');
        sale_price.removeAttr('required');
        sale_price_dates_from.removeAttr('required');
        sale_price_dates_to.removeAttr('required');
        sku.removeAttr('required');
        if (general_settings_param.debug) console.log('Set to default all required fields');
    }

    function set_default_option() {
        select_product_type.val('simple').change();
        virtual.attr('checked', false).change();
        downloadable.attr('checked', false).change();
        sold_individually.attr('checked', false).change();
        manage_stock.attr('checked', false).change();
        stock_status.val('instock').change();
        stock.val('');
        backorders.find("option:selected").removeAttr('selected');
        backorders.find("option[value='no']").prop('selected', 'selected');
        weight.val('');
        width.val('');
        height.val('');
        length.val('');
        shipping_class.val('-1').change();
        purchase_note.val('').change();
        menu_order_input.val('').change();
        reviews_allowed.attr('checked', false).change();
    }

    set_default_option();

    /**
     * This function is to keep compatibility with bootstrap theme in the front
     */
    function remove_hidden() {
        jQuery('#general_product_data,#inventory_product_data,#shipping_product_data,#linked_product_data,#product_attributes,#advanced_product_data,#variable_product_options').each(function () {
            jQuery(this).removeClass('hidden');
            jQuery(this).find('.hidden').removeClass('hidden');
        });
    }

    //Trigger if the product type if changed
    jQuery(document).on('woocommerce-product-type-change', function (obj, select_val) {
        determine_when_is_required(select_val);
        if (general_settings_param.debug) console.log('Product Type: ' + select_val);
    });

    remove_hidden();
    determine_when_is_required(select_product_type.val());

    //Remove not implemented tabs
    if (general_settings_param.disable_tabs) {
        jQuery.each(general_settings_param.disable_tabs, function (key, value) {
            if (general_settings_param[value] && general_settings_param[value][0] === 'hide_remove') {
                jQuery.each(jQuery('.woo_element_span').find('#product-type').find('option'), function () {
                    if (value.indexOf(jQuery(this).val()) !== -1) {
                        jQuery(this).remove();
                    }
                });
                jQuery('.product_data_tabs.wc-tabs').find('.' + value + '_options.' + value + '_tab').remove();
                jQuery('.product_data_tabs.wc-tabs').find('.' + value + '_tab').remove();
                jQuery('#' + value).remove();
            }
        });
    }

    //region General Tab

    if (general_settings_param.product_regular_price && general_settings_param.product_sales_price && general_settings_param.product_sales_price_dates !== undefined) {

        var hide_general_proudct_type,hide_general_proudct_option_downloadable, hide_general_tax_field, hide_general_regular_price, hide_general_sales_price,
            hide_general_price_date = false;
        var product_type_default = null;
        if (general_settings_param.wc_tax_option_disabled) {
            hide_general_tax_field = true;

        } else {
            //Set Product Tax if it is hidden
            if (general_settings_param.product_tax_hidden && general_settings_param.product_tax_hidden[0] &&
                general_settings_param.product_tax_hidden[0] === 'hidden') {
                hide_general_tax_field = true;
                $('._tax_class_field').hide();
                $('._tax_status_field').hide();

                $('#_tax_status').val(general_settings_param.product_tax_status_default).change();
                $('#_tax_class').val(general_settings_param.product_tax_class_default).change();

            }
        }

        //REGULAR PRICE
        if (general_settings_param.product_regular_price) {

            var regularPrice = general_settings_param.product_regular_price;
            if (regularPrice === "hidden") {
                hide_general_regular_price = true;
                if(general_settings_param.regular_price_amount){
                    $('#_regular_price').val(general_settings_param.regular_price_amount);
                }
                regular_price.hide();
                regular_price.parent().hide();
            }
        }

        //SALES PRICE
        if (general_settings_param.product_sales_price) {
            var salesPrice = general_settings_param.product_sales_price;
            if (salesPrice === "hidden") {
                hide_general_sales_price = true;
                // The builder saves 0 as the default amount. A sale price of 0 makes the product free,
                // so only use the amount when the admin set a real one.
                if (general_settings_param.sales_price_amount && parseFloat(general_settings_param.sales_price_amount) > 0) {
                    $('#_sale_price').val(general_settings_param.sales_price_amount);
                }
                sale_price.hide();
                sale_price.parent().hide();
            }
        }

        //SALES PRICE DATE
        if (general_settings_param.product_sales_price_dates) {
            var sales_price_date_opt = general_settings_param.product_sales_price_dates;
            if (sales_price_date_opt === "hidden") {
                hide_general_price_date = true;
                if(general_settings_param.product_sales_start_date &&general_settings_param.product_sales_end_date){
                    $('#_sale_price_dates_from').val(general_settings_param.product_sales_start_date);
                    $('#_sale_price_dates_to').val(general_settings_param.product_sales_end_date);
                }

                sale_price_dates_from.parent().hide();
                $('.sale_schedule').parent().hide();
            }
        }

        //Set Product Type if they are hidden
        if (general_settings_param.product_type_hidden && general_settings_param.product_type_hidden[0] &&
            general_settings_param.product_type_hidden[0] === 'hide_product_type') {
            hide_general_proudct_type = true;
            //Set the prodcut type
            if (general_settings_param.product_type_default) {
                product_type_default = general_settings_param.product_type_default;

                select_product_type.val(general_settings_param.product_type_default).change();
                select_product_type.hide();
            }
            //Set if is virtual or downloadable
            if (general_settings_param.product_type_options) {
                var virtual_val = (general_settings_param.product_type_options['_virtual'] !== undefined);
                var downloadable_val = (general_settings_param.product_type_options['_downloadable'] !== undefined);

                if (virtual_val) {
                    virtual.prop('checked', true);
                    virtual.attr('checked', true).change();

                }
                if (downloadable_val) {
                    downloadable.prop('checked', true);
                    downloadable.attr('checked', true).change();
                }else{
                    hide_general_proudct_option_downloadable = true;
                }
            }
        } else {
            hide_general_proudct_type = false;
            $('span.type_box.hidden').removeClass('hidden');
        }

        var general_tab_hidden_fields = [];
        switch (product_type_default) {

            case 'simple':
                general_tab_hidden_fields.push(hide_general_proudct_type);
                general_tab_hidden_fields.push(hide_general_tax_field);
                general_tab_hidden_fields.push(hide_general_regular_price);
                general_tab_hidden_fields.push(hide_general_sales_price);
                general_tab_hidden_fields.push(hide_general_price_date);
                general_tab_hidden_fields.push(hide_general_proudct_option_downloadable);
                break;
            case 'booking':
                general_tab_hidden_fields.push(hide_general_proudct_type);
                general_tab_hidden_fields.push(hide_general_tax_field);
                break;
            default:
                break;
        }

        var general_tab_hidden = [];
        if (BF_Woo_Element_Hook) {
            general_tab_hidden = BF_Woo_Element_Hook.apply_filters('bf_woo_element_general_tab_filter', general_tab_hidden_fields, product_type_default);
        }

        var hidde_general_tab = true;
        if (general_tab_hidden.length > 0) {
            for (var i = 0; i < general_tab_hidden.length; i++) {
                var option_value = general_tab_hidden[i];
                if (option_value !== true) {
                    hidde_general_tab = false;
                    break;
                }
            }

            if (hidde_general_tab) {
                jQuery('.general_options').hide();
                jQuery("#general_product_data").hide();
                tabs_hided.push('general_tab');
            }
        }
    }


    var hide_sku, hide_manage_stock, hide_stock, hide_stock_status, hide_sold_individually = false;

    //SKU
    if (general_settings_param.product_sku) {
        var sku_option = general_settings_param.product_sku;
        if (sku_option === "hidden") {
            if(general_settings_param.sku_value){
                jQuery("#_sku").val(general_settings_param.sku_value).change();
            }

            jQuery(".form-field._sku_field ").hide();
            hide_sku= true;
        }
        else if(sku_option === "required"){
            sku.attr("required", true);
        }
    }

    //endregion

    //region Inventory tab
    //Manage stock
    if (general_settings_param.product_manage_stock && general_settings_param.product_manage_stock[0] && general_settings_param.product_manage_stock[0] !== undefined) {

            manage_stock_opt = general_settings_param.product_manage_stock[0];
        if (manage_stock_opt === "manage") {
            manage_stock.attr('checked', true).change();
            manage_stock.prop('checked',true);
            //Stock Quantity
            if (general_settings_param.product_manage_stock_qty) {
               jQuery('#_stock').val(general_settings_param.product_manage_stock_qty);
            }

            //Stock Low Quantity
            if (general_settings_param.product_low_stock_qty) {
                jQuery('#_low_stock_amount').val(general_settings_param.product_low_stock_qty);
            }
            //Allow backorders
            if (general_settings_param.product_allow_backorders) {
                jQuery('#_backorders').val(general_settings_param.product_allow_backorders);
            }



            jQuery(".form-field._manage_stock_field").removeClass("show_if_simple");
            jQuery(".form-field._manage_stock_field").removeClass("show_if_variable");

            jQuery(".form-field._manage_stock_field").hide();
            jQuery(".form-field._stock_field").hide();
            jQuery(".form-field._backorders_field").hide();
            jQuery(".form-field._low_stock_amount_field").hide();

            hide_manage_stock= true;
            hide_stock_status = true;

        }
    }
    //Sold individually
    if (general_settings_param.product_sold_individually_options) {
        var sold_individually_opt = general_settings_param.product_sold_individually_options;
        if (sold_individually_opt === "hidden") {

            if (general_settings_param.product_sold_individually && general_settings_param.product_sold_individually === 'yes') {
                sold_individually.attr('checked', true).change();
                sold_individually.prop('checked', true);
            }

            jQuery(".form-field._sold_individually_field").removeClass("show_if_simple");
            jQuery(".form-field._sold_individually_field").removeClass("show_if_variable");

            jQuery(".form-field._sold_individually_field").hide();

            hide_sold_individually = true;

        }
    }
    //Stock Status
    if (general_settings_param.product_stock_status_options ) {
        var in_stock_opt = general_settings_param.product_stock_status_options;
        if (in_stock_opt === "hidden") {

            if (general_settings_param.product_stock_status) {
                jQuery('#_stock_status').val(general_settings_param.product_stock_status).change();
            }
            jQuery("._stock_status_field").removeClass("hide_if_variable");
            jQuery("._stock_status_field").removeClass("hide_if_external");
            jQuery("._stock_status_field").removeClass("hide_if_grouped");

            jQuery('.stock_status_field.form-field._stock_status_field').hide();
        }
    }

    if (hide_sku === true && hide_manage_stock === true && hide_sold_individually === true) {
        $('.inventory_options').removeClass('show_if_simple show_if_variable show_if_grouped show_if_external').hide();
        $('#inventory_product_data').hide();
        tabs_hided.push('inventory_tab');
    }
    //endregion

    //region Inventory tab
    // PRODUCT SHIPPING
    if (general_settings_param.product_shipping_hidden && general_settings_param.product_shipping_hidden[0] && general_settings_param.product_shipping_hidden !== undefined) {
        var shipp_hide_parent_1, shipp_hide_parent_2, shipp_hide_parent_3, shipp_hide_parent_4, shipp_hide_parent_5,
            shipp_hide_parent_6 = false,
            shipping_opt = general_settings_param.product_shipping_hidden[0];
        if (shipping_opt === "hide_shipping") {
            //Hide the entire tab
            $('.shipping_options').addClass('hide_if_simple hide_if_auction hide_if_variable').hide();
            $('#shipping_product_data').hide();
            tabs_hided.push('shipping_tab');

            if (general_settings_param.product_shipping_hidden_weight) {
                weight.val(general_settings_param.product_shipping_hidden_weight).change();
                weight.parent().hide();
            }
            else {
                weight.val('');
            }

            if (general_settings_param.product_shipping_hidden_dimension_length) {
                length.val(general_settings_param.product_shipping_hidden_dimension_length).change();
                length.hide();
            }
            else {
                length.val('');
            }

            if (general_settings_param.product_shipping_hidden_dimension_width) {
                var dimension_width = general_settings_param.product_shipping_hidden_dimension_width;
                width.val(general_settings_param.product_shipping_hidden_dimension_width).change();
                width.hide();
            }
            else {
                width.val('');
            }

            if (general_settings_param.product_shipping_hidden_dimension_height) {
                height.val(general_settings_param.product_shipping_hidden_dimension_height).change();
                height.hide();
            }
            else {
                height.val('');
            }

            if (general_settings_param.product_shipping_hidden_shipping_class) {
                shipping_class.val(general_settings_param.product_shipping_hidden_shipping_class).change();
                shipping_class.parent().parent().hide();
            }
        }
    }
    //endregion

    //region Linked Products
    //Up Sell
    var linked_parent_hide_1 = false, linked_parent_hide_2 = false, linked_parent_hide_3 = false;
    if (general_settings_param.product_up_sales && general_settings_param.product_up_sales[0] && general_settings_param.product_up_sales !== undefined) {
        var up_sales_opt = general_settings_param.product_up_sales[0];
        if (up_sales_opt === "hide_up_sales") {
            $("#upsell_ids").parent().remove();
            linked_parent_hide_1 = true;
        }
    }
    //Cross Sales
    if (general_settings_param.product_cross_sales && general_settings_param.product_cross_sales[0] && general_settings_param.product_cross_sales !== undefined) {
        var crosssell_opt = general_settings_param.product_cross_sales[0];
        if (crosssell_opt === "hide_cross_sales") {
            $("#crosssell_ids").parent().remove();
            linked_parent_hide_2 = true;
        }
    }
    //Grouped
    if (general_settings_param.product_grouping && general_settings_param.product_grouping[0] && general_settings_param.product_grouping !== undefined) {
        var grouped_opt = general_settings_param.product_grouping[0];
        if (grouped_opt === "hide_grouping") {
            $("#grouped_products").parent().remove();
            linked_parent_hide_3 = true;
        }
    }

    if (linked_parent_hide_1 === true && linked_parent_hide_2 === true && linked_parent_hide_3 === true) {
        $('.linked_product_options').hide();
        $('#linked_product_data').hide();
        tabs_hided.push('linked_product_tab');
    }
    //endregion

    //region Attributes
    if (general_settings_param.attributes_hide_tab && general_settings_param.attributes_hide_tab[0]
        && general_settings_param.attributes_hide_tab[0] === 'hide_attributes') {
        attribute_container.hide();
        attribute_tab.hide();
        tabs_hided.push('attribute_tab');
    }
    //endregion

    //region Variations
    if (general_settings_param.variations_hide_tab && general_settings_param.variations_hide_tab[0]
        && general_settings_param.variations_hide_tab[0] === 'hide_variations') {
        variations_container.hide();
        variations_tab.removeClass('show_if_variable').hide();
        tabs_hided.push('variations_tab');
    }
    //endregion

    //region Advance
    var hide_advance_parent1 = false, hide_advance_parent2 = false, hide_advance_parent3 = false;
    if (general_settings_param.hide_purchase_notes && general_settings_param.hide_purchase_notes[0]
        && general_settings_param.hide_purchase_notes[0] === 'hide_advanced') {
        purchase_note.parent().hide();
        hide_advance_parent1 = true;
        if (general_settings_param.purchase_notes && general_settings_param.purchase_notes !== "") {
            purchase_note.val(general_settings_param.purchase_notes).change();
        }
    }
    if (general_settings_param.hide_menu_order && general_settings_param.hide_menu_order[0]
        && general_settings_param.hide_menu_order[0] === 'hide_menu_order') {
        menu_order_input.parent().hide();
        hide_advance_parent2 = true;
        if (general_settings_param.menu_order && general_settings_param.menu_order !== "") {
            menu_order_input.val(general_settings_param.menu_order).change();
        }
    }
    if (general_settings_param.hide_enable_review_orders && general_settings_param.hide_enable_review_orders[0]
        && general_settings_param.hide_enable_review_orders[0] === 'hide_review_order') {
        reviews_allowed.parent().hide();
        hide_advance_parent3 = true;
        if (general_settings_param.enable_review_orders === 'yes') {
            reviews_allowed.attr('checked', true).change();
        }
    }

    if (hide_advance_parent1 === true && hide_advance_parent2 === true && hide_advance_parent3 === true) {
        advanced_tab.hide();
        advanced_container.hide();
        tabs_hided.push('advanced_tab');
    }

    //endregion


    // Save attributes and update variations.
    $('.save_attributes').on('click', function () {

        $('#woocommerce-product-data').block({
            message: null,
            overlayCSS: {
                background: '#fff',
                opacity: 0.6
            }
        });

        var data = {
            post_id: woocommerce_admin_meta_boxes.post_id,
            product_type: $('#product-type').val(),
            data: $('.product_attributes').find('input, select, textarea').serialize(),
            action: 'woocommerce_save_attributes',
            security: woocommerce_admin_meta_boxes.save_attributes_nonce
        };

        $.post(woocommerce_admin_meta_boxes.ajax_url, data, function () {
            // Reload variations panel.
            var this_page = window.location.toString();
            this_page = this_page + '?post=' + woocommerce_admin_meta_boxes.post_id + '&action=edit&';
            // Load variations panel.
            $('#variable_product_options').load(this_page + ' #variable_product_options_inner', function () {
                $('#variable_product_options').trigger('reload');
            });
        });

    });

    //Execute other addOns customization
    BF_Woo_Element_Hook.do_action('bf_woo_element_ready');

    //Hide tabs in the array
    hideTabs(tabs_hided);

    if (general_settings_param.debug) {
        console.log(tabs_hided);
    }
    if (general_settings_param.debug_hidden) {
        jQuery('#woocommerce-product-data input:hidden, #woocommerce-product-data div:hidden, #woocommerce-product-data li:hidden, #woocommerce-product-data select:hidden, #woocommerce-product-data p:hidden').show();
        jQuery('span.type_box.hidden')
            .removeClass('hidden')
            .find('label:hidden, select:hidden, input:hidden').show();
    }
    main_container.find('.woo_general_loader').remove();
    determine_default_tab();

    jQuery( ".wc-backbone-modal, .wc-backbone-modal-backdrop" ).remove();
});
