=== BuddyForms Form Elements for WooCommerce ===

Contributors: svenl77, konradS, buddyforms, themekraft, gfirem
Tags: woocommerce, buddyforms, frontend products, vendors, buddypress
Requires at least: 5.9
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.5.16-beta.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create and edit WooCommerce products from the front end with BuddyForms Pro, and choose which product options each user sees.

== Description ==

BuddyForms Form Elements for WooCommerce lets vendors and users create and edit WooCommerce products from the front end. It adds a WooCommerce section to the BuddyForms form builder, so you can build a product form and decide which product options each user sees. Requires BuddyForms Pro and WooCommerce.

= What users can manage from the front end =

* Product type and prices
* SKU and inventory
* Shipping
* Linked products
* Attributes and variations
* Product gallery

Each option can be visible, hidden or preset with a default value, so users only see what they need.

= Vendors and marketplaces =

Let your users become vendors and manage their own products from the front end. BuddyForms gives each product form two views: a list of a vendor's products, and a screen to create and edit a product. With BuddyPress and the free BuddyForms Members add-on, a member's product list can be shown on their profile.

Vendor payments and commissions are left to other vendor plugins, so you can change vendor plugin later without changing your product forms.

= Compatibility =

* Compatible with WooCommerce High-Performance Order Storage (HPOS)

= Related plugins =

* [WooBuddy](https://wordpress.org/plugins/wc4bp/): moves the WooCommerce customer area into BuddyPress member profiles

== Documentation & Support ==

<h4>Extensive Documentation and Support</h4>

All code is clean and well documented (inline as well as in the documentation).

The BuddyForms documentation with many how-to’s is following now!

If you still get stuck somewhere, our support gets you back on the right track.
You can find all help buttons in your BuddyForms settings panel in your WP dashboard!

== Installation ==

1. Install and activate BuddyForms (with BuddyForms Pro) and WooCommerce.
2. Install this add-on from Plugins > Add New, or upload its folder to /wp-content/plugins/, and activate it.
3. In the BuddyForms form builder, add the WooCommerce section to a form.

== Frequently Asked Questions ==

= Do I need BuddyForms? =

Yes. This add-on needs the BuddyForms plugin and WooCommerce.

= Do I need BuddyForms Pro? =

Yes. WooCommerce products are a custom post type, and creating a custom post type from the front end is a BuddyForms Pro feature. The free version of BuddyForms can only create Posts and Pages.

= Which product options can users edit? =

Product type and prices, SKU and inventory, shipping, linked products, attributes, variations and the product gallery. You choose whether each option is visible, hidden or preset.

= Does it work with my theme? =

It should work with every theme. Please let us know if you run into an issue with yours.


== Changelog ==
= 1.5.15 - 20 Nov 2026 =
* Security: product type, prices and SKU fixed in the form are now applied when the product is saved.

= 1.5.14 - 18 Nov 2026 =
* Fixed submissions that failed without an error on forms that do not create products. The WooCommerce fields can now only be added to forms whose post type is Product, and other forms skip them.
* Fixed the "DOMPurify is not defined" error on front-end product forms, which also kept the "Hide" options of the WooCommerce fields from applying. Forms that kept the default "Hide" for the regular price now hide that field and use the price set in the form builder.
* Fixed products being saved with a sale price of 0, which made them free, when the sale price field was hidden.
* Fixed the "Create value" and "Set regular prices" windows and the variation generation on front-end product forms.
* Fixed a PHP 8.2 deprecation notice in the submissions list.

= 1.5.13 - 05 Oct 2026 =
* Fixed a fatal error in front-end forms with a WooCommerce field (WooCommerce 7.9 and later).
* Compatible with WooCommerce High-Performance Order Storage (HPOS).
* Tested up to WooCommerce 11.1.

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