=== BuddyForms Form Elements for WooCommerce ===

Contributors: svenl77, konradS, buddyforms, themekraft, gfirem
Tags: buddypress, user, members, profiles, custom post types, taxonomy, frontend posting, frontend editing,
Requires at least: 5.9
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.5.12
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Let your WooCommerce Vendors Manage there Products from the Frontend

== Description ==

This is the BuddyForms Form Elements Extension for WooCommerce. Create powerful frontend management for your vendors. You need the BuddyForms plugin installed for the plugin to work. <a href="http://buddyforms.com" target="_blank">Get BuddyForms now!</a>

This plugin adds a new section to the BuddyForms Form Builder with all WooCommerce fields to create product forms to manage (create/edit) products from the frontend.

###WooCommerce Fields

* Product General Data like Product Type, Price
* Inventory
* Shipping
* Linked Products
* Attributes
* Product Gallery


<b>Keep your User in the Frontend.</b>

Your users can become vendors and are able to manage their WooCommerce products from the front end. If you use BuddyPress, all can be integrated into the members profile with one click.


<b>Create a Marketplace.</b>

Create All Kind of marketplaces and let your user become the vendor.
like classifieds, advertisements, creative markets...


What else do I need to create a marketplace?

BuddyForms WooCommerce Form Elements is build for one purpose, to make it easy for you to manage creating and editing your WooCommerce products. This plugin is a clean, bloat free solution to front end edition of your WooCommerce products.

<strong>Features</strong>:
The plugin generates two different views.

1. For the list of vendor products
2. For the creation and edition screen.

When used with BuddyPress, the members product listing can be displayed publicly to show their products directly within their profile page.

If you wish to integrate WooCommerce with BuddyPress please use our <a href="http://buddyforms.com" title="WooCommerce BuddyPress Integration WordPress Plugin" target="_blank">WooCommerce and BuddyPress Profile synchronization plugin</a>. This plugin makes it very easy to integrate WooCommerce and other WooCommerce plugins directly within the BuddyPress profile pages.

If you need a vendor management you can use any. This is a lot of freedom for you. You can change your vendors extension if you are unhappy, but all the rest will work. We decided to leave the vendor payment management to other plugins.

There are already vendor plugins available from WooThemes and other developers.

Free Vendor Plugins
<ul>
<a href="https://wordpress.org/plugins/wc-vendors/" target="_blank">WP Vendors<a/>
</ul>

Paid Vendor Plugins
<ul>
<a href="http://www.woothemes.com/products/product-vendors/" target="_blank">Product Vendors<a/>
</ul>

for more information please read the documentation on How to Create a Marketplace with WordPress, WooCommerce and BuddyPress.

http://docs.buddyforms.com/article/151-create-a-social-marketplace-with-woocommerce-and-buddypress

== Documentation & Support ==

<h4>Extensive Documentation and Support</h4>

All code is clean and well documented (inline as well as in the documentation).

The BuddyForms documentation with many how-to’s is following now!

If you still get stuck somewhere, our support gets you back on the right track.
You can find all help buttons in your BuddyForms settings panel in your WP dashboard!

 == Installation ==

You can download and install BuddyForms WooCommerce Form Elements by using the built in WordPress plugin installer. If you download BuddyForms WooCommerce Form Elements manually, make sure it is uploaded to "/wp-content/plugins/".

 == Frequently Asked Questions ==

You need the BuddyForms plugin installed for the plugin to work.
<a href="http://buddyforms.com" target="_blank">Get BuddyForms now!</a>

The plugin should work with every theme. (Please let us know if you experience any issues with your theme.)


== Changelog ==
= 1.5.12 - 03 Oct 2026 =
* Required plugins are now declared with the WordPress "Requires Plugins" header instead of the bundled TGM Plugin Activation library.
* Fixed translations: every string now uses the plugin's own text domain.
* Requires WordPress 5.9 and PHP 7.4. Tested up to WordPress 7.1.

= 1.5.11 - 20 Dec 2022 =
* Fixed some CSS glitches.
* Added jquery to hide empty variations price popup.
* Tested up to WordPress 6.1.1

= 1.5.10 - 11 Aug 2022 =
* Fixed trademark issue.

= 1.5.9 - 09 Aug 2022 =
* Fixed issue with remote CSS file call.
* Fixed vulnerability issue.
* Updated plugin name.
* Tested up to WordPress 6.0.1

= 1.5.8 - 17 May 2022 =
* Updated readme.txt

= 1.5.7 - 24 Mar 2022 =
* Fixed issue with dependencies validation.
* Tested up to WordPress 5.9

= 1.5.6 - 27 Sep 2021 =
* Fixed compatibility issue with BF Moderation.
* Tested up with WordPress 5.8

= 1.5.5 - 18 May 2021 =
* Remove unused Composer dependencies.
* Add support for the option "Make this field Hidden" on the Product Type field.

= 1.5.4 - 8 Mar 2021 =
* Tested up with WordPress 5.7

= 1.5.3 - 16 Jun 2020 =
* Fixed to show all the available woocommerce product types.

= 1.5.2 - 16 Jun 2020 =
* Fixed to the issue related to create product always with the status publish.
* Fixed json parse error, clean html tags from the json string.

= 1.5.1 - 28 Jan 2020 =
* Fix woocommerce field style issue.

= 1.5.0 27 Aug 2019 =
* Adding the new Price field.
* Added Regular and Sale Price extending form BF Price field.
* Fix the gallery field validation.
* Update the code to make it compatible with the last version of Woocommerce.

= 1.4.8 26 Apr. 2019 =
* Fixed the behavior of not saving shipping fields.
* Fix: Simple Product Downloadable options remains visible if you change the product type
* Fix: Several Tabs inside the WooCommerce Form doesn't have options
* Fix: WooCommerce Fields are not displayed in the Forms
* Fix: Hide shipping fields option isn't hiding the proper fields
* Fix: Hide fields for Advanced section aren't working
* Fix: Product Type Hidden checkbox doesn't hide the Product Data
* Fix: Hide options for Linked Products sections aren't working
* Fix: Hide Variations Tab option is not working
* Fix: Hide Attributes Tab option isn't hiding
* Fix: Issue with the review allowed checkbox( the last woocommerce update change the id attribute from review_allowed to comment_status)
* Fix: Remove the product type selector filter and replace it for a hard coded array
* Fix: Required Product Gallery section on Form type post is not working as expected
* Fix: Avoid return and exit the function when the post type is bp_group_type

= 1.4.7 21 Jan. 2019 =
* Make sure we executed the validation rules in the right form
* Make sure that the rules of the form will, execute when the page contains more than one form

= 1.4.6 10 December 2018 =
* Fixed the upsell and crossell option to work in the frontend.
* Fixed the Inventory options.
* Add prices related input when they are hidden.
* Add a validation to avoid js errors.
* Fixed input price in the field option.
* Fixed the error on the process to create a product.
* Added Inventory Tab default options and required fields.
* Prevent form submission when are fields required.
* Added custom rules to the required fields and css style to the error messages.
* Move the sku required rule in the right place.
* Fixed the downloadable fields in the back end.
* Hide the regular price, sale price and sale date input option when change product type and the input option is selected to "hidden".

= 1.4.5 5 November 2018 =
* Preparing  to integrate with other new AddOns.
* Fixed the downloadable option.
* Fixed the function to hide the tabs marks to not show and select the first available.

= 1.4.4 28 August 2018 =
* Ready to integrate BF Woocommerce Booking Addon.
* Fixed the style issue related to the Image Gallery.
* Adding the value of this fields to the admin submission list.
* Adding a code to hide the tax fields in the front if is checked as hidden in the settings config.
* Adding a code to handle tax options if they are enabled.
* Adding new options to change the Gallery Title and Button text.

= 1.4.3 13 April 2018 =
* Changing local environment.
* Changing the requirement text to be more generic.
* Adding the script to generate the plugin zip
* Upload Hidden Solved

= 1.4.2 18 December 2017 =
* Changing the requirement text for one generic.

= 1.4.1 27 July 2017 =
* Fixed an issue whit WooCommerce 3.1.1

= 1.4 07.Jun.2017 =

* This is a major update with lots of changes. Not all commits are listed here. For a detailed list of all changes please see the GitHub Commits https://github.com/BuddyForms/BuddyForms-WooCommerce-Form-Elements

Main New Features:
Complete Rewrite of the form elements and the WooCommerce Integration.
Beta Release Post with more details: https://themekraft.com/woocommerce-version-3-0-buddyforms-extensions-beta-test-round/

* Migrate to OOP the forms-builder, forms-elements and form-save
* Create a class to handle the requirements
* Encapsulate all form elements
* Extract all views off the form elements
* Extract all views off the form elements and apply the textdoamin function
* Fixing the path for the form elements. Cleaning the code
* implement how to include all needed assets from woocommerce
* Refactoring the code. Adding OB to show the form woo element
* implement how to include all needed assets from woocommerce
* Improving the method to check if buddyform is loaded
* apply the field elements to the front
* post is not saved with the new version of woocommerce
* Adding a validation if the configuration is not set
* Organizing the options inside the Woocommerce field, better stripe and show/hide sections. Also i update the shipping classes
* Fix the look and feel of the woo tabs in the front
* apply the field elements to the front. Apply logic in the backend for the product type. Changing the product type in the builder to use Element_Select. Organizing the hide/show elements inside the fi
* Fixing the builder select product code. Implementing the default tab
* Fixing woocommerce field, the Linked Product tab is not working
* Fixing and adding more logic into the field builder
* Variation tab is not working even if you create attributes inside the page or from the admin
* Add check to avoid duplicates of the fields
* Fix woocommerce hidden class with boostrap
* Implementing new tab inside the options to handle the execution of third party of tab include into woocommerce
* Improving the architecture to remove the product type when disable the tab. Adding a hook to avoid remove implemented tabs
* Fixing the shipping class to interact better with the form builder
* Adding conditional debug for script
* improve the code
* Implementing tabs to hide and set values to advanced, attributes and variations.
* Adding a div to hide the process.
* Improving the scripts to auto select the first available tabs dynamically.
* Fixing some bug in the shipping tab and the linked product tab.
* Fixing js to call correctly the menu order input
* Added form builder template support
* Adding check for the unhandled tab, to include if the plugin exist.
* improve the front script to hide the elements even if the user don't put any value.

= 1.3.5.1 =
* Fixed and issue with the dependencies check. The function tgmpa does not accepted an empty array.

= 1.3.5 =
* Remove Network: true, buddyforms and all extension needs to get activated in the blog
* Rename buddyforms_add_form_element_to_select to buddyforms_add_form_element_select_option
* Add dependencies management with tgm

= 1.3.4 =
* Make the plugin network compatible
* Check if the class WooCommerce exists to now if the plugin is active

= 1.3.3 =
* use buddyforms_display_field_group_table to display options
* add WooCommerce form elements to the form elements select
* only show form type related form elements
* fixed a bug in the form builder. the WooCommerce form element always jumps to the first position.
* Support for the latest version of WooCommerce.
* New option to display the Sale Price in the WooCommerce form elements but not make it required

= 1.3.2.1 =
* correct merge conflicts

= 1.3.2 =
* Remove a left over xdebug_break()....

= 1.3.1 =
* Reformat code to stay conform with the WordPress coding style guide.
* Fix an issue with the group products

= 1.3 =
* Add new hidden options to the shipping options.
* Fixed a smaller issue with the stock management display options.
* Fixed issue with the product sold individually options. The hidden value none was not recognized by wc
* inline documentation

= 1.2.2 =
* Hide grouped products option did not work
* The WooCommerce gallery was always required thanks to Emmanuel for pointing me on this issue.

= 1.2.1 =
* WooCommerce Version 2.5.0 comes with a new function wc_help_tip. This functions was only loaded in the admin but we need it to work in the front end.

= 1.2 =
* Huge update
* Merged all WoCommerce relevant form elements into one Form Element to avoid conflicts and make it more easy extendable.
* Insert the class-ac-meta-box-data.php into the plugin to save the values
* Remove the chipping option. Its not needed anymore

= 1.1.4 =
* add new hook bf_woocommerce_product_options_general_last to bf-wc-product-general.php
* change the url to buddyforms.com
* start developing variations support

= 1.1.3 =
* forgot to close a b tag

= 1.1.2 =
* Add new options to the inventory form element.
* fixed an issue with the price field if the sales price was set to hidden.
* removed the hide attribute from the price option. It doesn't make sense.

= 1.1.1 =
* add a new function buddyforms_woocommerce_updtae_visibility to add visibility = visible if the post status is set to published during submit.
* fixed a bug in the taxonomies form handling if the taxonomy is used for a product attribute the post meta needs to be updated.

= 1.1 =
* Add support for WooCommerce 2.3
* Update the form fields logic and css for WooCommerce
* Load needed js for the fronted in WooCommerce 2.3

 = 1.0 =
* Initial release 1.0 ;)

 == Screenshots ==
coming soon