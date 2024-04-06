=== SiteOrigin Premium ===
Requires at least: 4.7
Tested up to: 6.4
Requires PHP: 5.6.20
Stable tag: 1.60.0
Build time: 2024-03-07T23:00:17+00:00
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

A collection of powerful addons that enhance every aspect of SiteOrigin plugins and themes.

== Description ==

SiteOrigin Premium is a collection of powerful addons that enhance Page Builder, Widgets Bundle, SiteOrigin CSS, and our WordPress themes. These addons improve existing features and add entirely new functionality. You'll love all the power they offer you.

We bundle every one of our addons into this single package, which means that as we introduce more addons, you get them free of charge for as long as you have an active license.

Most importantly, we also provide you with fast email support. Our email support is 30 times faster than the free support we offer on our forums. So you'll usually get a reply in just a few short hours.

== Installation ==

The SiteOrigin Premium plugin can be downloaded via the link provided in your order confirmation email. Please, note that the link is only valid for seven days. You can also log in to the [order dashboard](https://siteorigin.com/dashboard/) and download the SiteOrigin Premium plugin at any time. Once you've downloaded the plugin ZIP file, install it from Plugins > Add New > Upload Plugin. Once activated, go to SiteOrigin > Premium License within WordPress to activate your license. Your license key is provided in your order confirmation email.

[Complete installation instructions](https://siteorigin.com/premium-documentation/install-siteorigin-premium/) are available on SiteOrigin.com.

== Documentation ==

[Documentation](https://siteorigin.com/premium-documentation/) is available on SiteOrigin.com.

== Changelog ==

= 1.60.0 - 07 March 2024 =
* New Addon! Introducing the Embed Blocker Addon. Effortlessly make your website GDPR and DSGVO compliant by controlling embeds from platforms like YouTube, Vimeo, Twitter, Instagram, Facebook, Google Maps, Reddit, Soundcloud, Spotify, and TikTok until user consent is given.
* Carousel: Resolved `BuilderType` warning.
* Image Overlay: Prevented error if global settings are empty.

= 1.59.2 - 02 March 2024 =
* Improved automatic update support.
* Google Maps Consent: Updated background color setting to support transparency.
* Lightbox: Resolved a potential PHP 8.2+ related error.
* WooCommerce Template Builder: Resolved an error that can occur when editing the cart page directly.

= 1.59.1 - 24 February 2024 =
* Parallax Sliders: Resolved Jetpack Photon related `Uncaught TypeError`.
* Cross Domain Copy Paste: Update to ensure a default method is set on first install.
* Video Background: Resolved a potential Block Editor related display issue.
* Video Background: Update to prevent `border_radius` warning.

= 1.59.0 - 18 February 2024 =
* Anything Carousel: Removed `Row Layout` from Layout Builder caoursel items.
* Call To Action: New Settings! Background Image and supporting settings, Content Vertical Alignment, and Padding.
* Cross Domain Copy Paste: Added Browser Clipboard textarea to Layout Builders Widgets at Appearance > Widgets. Only the Browser Clipboard method supported at Appearance > Widgets.
* WooCommerce Template Builder: Loaded the saved cart into the session so the user can see it before paying. Applicable for orders marked as "Pending Payment" and accessed via `/checkout/order-pay/ORDERID/`.

= 1.58.2 - 11 February 2024 =
* WooCommerce Template Builder: Added Block Editor Thank You page support.
* Plugin Updater: Bypassed update cache during automatic updates.

= 1.58.1 - 27 January 2024 =
* Added compatibility with the NativeChurch plugin.
* Improved user experience of the Full Page toggle visibility feature by adding a new "Schedule" option and reordering visibility options.

= 1.58.0 - 20 January 2024 =
* Toggle Visibility: Introducing Full Page Visibility! Hide/Show pages or page content. 
Toggle visibility based on logged-in status. Display a message when content is hidden and 
optionally redirect users when a page is hidden. Hide/Show available with date scheduling.

= 1.57.1 - 13 January 2024 =
* Updater: Various improvements.
* Resolved a WCTB warning message "strpos(): Empty needle" by correcting the order of arguments in the `strpos` function.

= 1.57.0 - 11 January 2024 =
* Web Font Selector: Added variation support.
* Updated the Google Fonts array.
* Addon Management: Improved section & addon ID handling.
* Added a check to ensure that the `$current_screen` variable is not empty before calling the `method_exists()` function.
* Added a check to ensure that only users with the appropriate capability can activate the SiteOrigin Premium license.

= 1.56.0 - 07 January 2024 =
* Metabox: Added a General tab to the metabox for reducing the total number of tabs.
* Video Background: Added video display support for widgets.
* Video Background: Introduced support for border-radius in video backgrounds.
* Video Background: Added support for rounding in video backgrounds.
* Various code formatting improvements for better readability and consistency.

= 1.55.0 - 05 January 2023 =
* Blog: Optimized supporting JavaScript assets.
* Parallax Sliders: Ensured scripts only loaded when needed.
* WooCommerce Template Builder: Added Genesis, Genesis Connect, and Block Editor compatibility.
* WooCommerce Template Builder: Added Product Meta Widget display options.
* Video Background: Added a Loop Video setting.
* Video Background: Added a Background Video Display setting.
* Video Background: Adjusted secondary settings to conditionally display when a video is added.
* Video Background: Updated the Background Video Opacity setting to conditionally display if a Background Video has been set.

= 1.54.1 - 13 December 2023 =
* Blog: Hide Load More button when loading posts using Ajax.
* Blog: Update to ensure Next link is hidden when there aren't enough posts load a new page.
* Page Background: Resolved potential notices.
* Improved required plugin and version check.
* Updated Google Fonts array.

= 1.54.0 - 14 November 2023 =
* New Addon! Introducing the Video Background Addon. Add dynamic video backgrounds to any Page Builder row, column, or widget, adding an energetic touch to your site. With support for various video formats like mp4, m4v, mov, wmv, avi, mpg, ogv, and webm, flexibility is at your fingertips. Includes option for a semi-transparent overlay or pattern.
* Blog: Removed pagination page reload if pagination is disabled.
* Blog: Hid pagination links when loading posts using Ajax.
* Blog: Ensured the correct pagination links are used when loading with Ajax.
* Mirror Widgets: Enabled thumbnails.
* Mirror Widgets: Updated permissions to allow the slug to be edited.
* Renamed all "Cell" references to "Columns".
* Updated SiteOrigin Installer.

= 1.53.0 - 29 October 2023 =
* Blog: Added Post Content `None` Option, allowing users to optionally hide the post content. Useful for display related posts.
* Removed reference to "Content Area" in Cross Domain Copy Paste field instructions for clarity.
* Premium Metabox: Fixed a display issue with the Block Editor. Added a condition to check if the parent element has rendered completely before making any changes.

= 1.52.0 - 19 October 2023 =
* Contact Form: Added support for Merge Tags. Merge tags can be added to the Subject, Success, and Auto Responder messages. Merge tags can be referenced by wrapping them in square brackets.
* Blog: Code cleanup and reordering. Added animation settings for blog posts, including options for animation type, screen offset, animation speed, hiding before animation, animation delay, and disabling animation on mobile.
* Cross Domain Copy Paste: Fixed issue where the browser storage iframe was appearing behind the settings. Updated z-index values in the CSS file to ensure the correct permissions modal stacking order.
* Cross Domain Copy Paste: Added and fixed an HTTP alert. The addon now requires a secure connection (https) to function properly.

= 1.51.0 - 26 September 2023 =
* Blog: Resolved potential incorrect Read More pagination button display.
* Cross Domain Copy Paste: Introduced the Browser Clipboard alernative to the Browser Storage Method.
* Plugin License: If connection to license server fails, try one more time before deactivation.

= 1.50.1 - 23 September 2023 =
* Added new banners for Custom Palette, Image Shape, and Page Background addons.
* Updated Google Fonts with new font families and styles.
* Added minimum version header for plugin addons to prevent activation if user doesn't have the required minimum version.
* WCTB: Moved compatibility code for various plugins to dedicated files for better organization and maintainability.
* Prevented the SiteOrigin Premium metabox from appearing for Mirror Widgets and Custom Post Types.
* Fixed a typo in the code comments of the `add_featured_image_fallback` function in the `blog.php` file.
* Prevented potential JavaScript error in the Metabox when setting up tabs.
* License Debugging: Changed UA Bypass to URL Query String for better debugging and flexibility.

= 1.50.0 - 17 August 2023 =
* Blog: Added fallback compatibility with `Skip Post If No Featured Image` setting. This includes preventing repeated processing of fallback image detection and modifying the query for the portfolio template to exclude posts without a featured image fallback.
* WCTB: Added compatibility for the Virtue theme. This includes adding new filters and actions to modify the HTML structure and classes of the product elements.
* Installer: Excluded Standalone Updater from the Installer. This includes updating the subproject commit in the `inc/installer` directory and excluding the `inc/installer/inc/github-plugin-updater.php` file from the copy process.
* Updated "Tested up to" tag to 6.3 in the readme.txt and siteorigin-premium.php files.
* Minor formatting updates to the `woocommerce-templates.php` file for better readability.

= 1.49.0 - 03 August 2023 =
* New Addon! Introducing the Image Shape Addon. Elevate your image designs with an expanded selection of shapes and captivating effects, including shadows and hover shadows.
* Image Overlay: Ensure `responsive_breakpoint` is always set. Resolves a potential display issue if no value is set.
* Parallax Sliders: If Page Builder isn't loading parallax, ensure setup JavaScript is loaded.
* Resolved a potential SiteOrigin Premium admin page asset loading issue.
* Added SiteOrigin Installer setting.

= 1.48.0 - 25 July 2023 =
* New Addon! Introducing the Cross Domain Copy Paste Addon. Build pages faster by copying and pasting Page Builder widgets and rows between websites.

= 1.47.0 - 19 July 2023 =
* Logo Booster: Update to ensure that if a logo is not set in the addon or theme, the site title will be displayed.
* Page Background: Minor code improvements.
* Page Background: Resolved `siteorigin_widgets_get_attachment_image_src` potential error.
* WooCommerce Template Builder: Added compatibility for WPC Smart Compare and WPC Smart Wishlist.
* WooCommerce Template Builder: Added a check to ensure Page Builder is activated before using `SiteOrigin_Panels_Admin`.
* WooCommerce Template Builder: Removed non-WCTB layouts inside of the WCTB section.
* WooCommerce Template Builder: Removed the Vantage prebuilt layout.
* Parallax: Resolve multiple parallax performance issue.
* Resolved potential `$assets_setup` warning.
* Metabox: Various display and operational improvements. Currently used by Page Background and Logo Booster.
* Addons Page: Scroll to top of addons after clicking tag.
* Updated SiteOrigin Installer.

= 1.46.0 - 13 June 2023 =
* New Addon! Introducing the Page Background Addon. Add page specific background images with support for high-pixel-density displays.
* Link Overlay: Added an Accessibility Label setting.
* Addons Page: Improved addon search functionality and prevented potential misalignment when clicking plugin tags.
* Code Formatting: Updated code formatting for the Addons page and related JavaScript.
* Updated the SiteOrigin Installer.

= 1.45.0 - 08 June 2023 =
* Link Overlay: Added `Accessibility Label` setting.
* Logo Booster: Prevented override on archive pages.
* Logo Booster: Improved WooCommerce compatibility. Ensured that the correct ID is detected on the shop page.
* WooCommerce Template Builder: Fixed unintentional Archive attribute query override.
* Removed TGM Plugin Activation library.
* Added the SiteOrigin Installer.
* Updated the Google Fonts list.
* ACF: Removed the SiteOrigin metabox from ACF post types. Added`siteorigin_premium_metabox_excluded_post_types`.

= 1.44.0 - 22 May 2023 =
* Logo Booster: Resolved edge case display errors.
* Logo Booster: Migrated settings to a central metabox below the post content.
* Logo Booster: Added global settings at SiteOrigin > Premium Addons > Logo Booster for Polylang and WPML.
* Lightbox: Updated library from `v2.11.1` to `v2.11.4`.
* Lightbox: Update to prevent duplicate items when added to the Anything Carousel.

= 1.43.1 - 10 May 2023 =
* Mirror Widgets: Excluded the Mirror Widget from the SiteOrigin Widget Block cache.
* WooCommerce Template Builder: Added the ability to clone WooCommerce Templates from `Layouts > Clone: WooCommerce Templates`.
* Developer: Registered Parallax as a common script.

= 1.43.0 - 12 April 2023 =
* New Addon! Introducing the Custom Palette Addon. Create a custom color palette for Page Builder and Widgets Bundle color settings.
* Bulk Addresses: If an address isn't found, prompt for an alternative.
* Lightbox: Resolved 'undefined array key' warning.
* Social Widgets: Added Custom Networks > Wire > Border Color and Border Hover Color settings.
* Web Font Selector: Resolved Beaver Builder `Uncaught TypeError`.

= 1.42.0 - 27 March 2023 =
* Accordion: Added the `Automatically Add Paragraphs` setting to `Content Type: Text`.
* Blog: Added `Animations` settings section.
* Bulk Addresses: Removed empty marker if an address is not found.
* Image Overlay: Added `Hide on Hover` Desktop device trigger.
* Lightbox: Migrated existing settings to the new dedicated settings section.
* TGM Plugin Activation: Resolved potential notice during plugin installations.

= 1.41.1 - 23 March 2023 =
* Bulk Address: Added a Widgets Bundle version check. Widgets Bundle version `1.46.7+` is required.
* TGM Plugin Activation Library: Update to avoid conflict due to third-party plugins and themes incorrectly using the TGM 
framework.
* Developer: Lowered Contact Form autoresponder function priority. Ensures that users who add a redirect using the default priority will still have the autoresponder be sent.

= 1.41.0 - 15 March 2023 =
* New Addon! Introducing the Bulk Address Addon. Add multiple Map Marker addresses in one go to the SiteOrigin Google Maps Widget.
* Lightbox: Migrated settings to a dedicated section.
* PHP coding standards update.
* Improved Block Editor metabox styling.

= 1.40.0 - 04 March 2023 =
* New Addon! Introducing the Logo Booster Addon. Enhance your SiteOrigin theme logo functionality. Add an alternative logo on any page; upload a sticky logo to display on scroll.

= 1.39.0 - 15 February 2023 =
* Blog: Added `Featured Image Fallback` setting.
* Blog: Added `Disabled` Pagination Type option.
* Hero: Prevented all slides with `Frame Display` animation event displaying after setup.
* Mirror Widgets: Removed Mirror Widgets post type from Page Builder list.
* Parallax Sliders: Restored functionality in the event Page Builder is inactive.

= 1.38.1 - 13 January 2023 =
* Blog: Prevented the unintentional override of pagination settings with premium pagination settings.
* Web Font Selector: Ensured the addon JavaScript is loaded in the Widgets Block Editor.

= 1.38.0 - 01 January 2023 =
* New Addon! Introducing the Retina Background Images Addon. Add widget, cell, and row Retina background images for high-pixel-density displays.

= 1.37.1 - 27 December 2022 =
* Custom Row Colors: Added addon banner.

= 1.37.0 - 14 December 2022 =
* Lottie Player: Added an `Animation Mode > Visibility > Visibility Percentage` setting.
* Global Settings: Updated to allow saving using older Widgets Bundle versions.

= 1.36.0 - 11 December 2022 =
* New Addon! Introducing the Custom Row Colors Addon. Organize your Page Builder rows with custom background colors.
* Accordion: Added a `Title Icon Image Size` setting.
* Call To Action: Minor settings reorganization.
* Custom Post Type Builder: Added a `Display Menu Item` setting to control the post type admin menu item visibility.
* Toggle Visibility: Prevented `toggle_scheduling_toggle_display` notice.

= 1.35.2 - 01 November 2022 =
* WooCommerce Template Builder: Added support for NM Gift Registry and Wishlist non-thumbnail single product button positions.

= 1.35.1 - 29 September 2022 =
* Blog: Display excerpt related settings conditionally when `Post Content > Post Excerpt` is chosen.
* Toggle Visibility: Prevented potential scheduling save notice.
* Toggle Visibility: Prevented unwanted scheduling detection.
* Web Font Selector: Ensured admin scripts aren't loaded on site front-end.
* Developer: Introduced the Web Font Selector `siteorigin_web_font_url` filter.

= 1.35.0 - 26 September 2022 =
* Blog: Added `Excerpt Trim Indicator` setting.
* Blog: Added Masonry and Portfolio template Ajax loading refresh to resolve potential post overlap.
* Blog: Accounted for Ajax loader size when positioning.
* Carousel: Set navigation dots font weight to avoid unintentional third-party styling.
* Carousel: Added widget preview support.
* Mirror Widgets: Added Mirror Widget title to the browser tab title when editing.
* Toggle Visibility: Migrated Enable/Disable checkbox to a On/Off setting.
* Developer: Added `siteorigin_premium_web_font_selector_import_fonts` to disable Web Font Selector font import if required.

= 1.34.1 - 04 September 2022 =
* Blog: Fixed Ajax loaded post excerpt display.

= 1.34.0 - 31 August 2022 =
* New Addon! Introducing the Blog Addon. Enhance the SiteOrigin Blog Widget with Ajax Pagination and other useful settings to improve your user's post-viewing experience.
* Accordion: Added heading settings for the active state.

= 1.33.3 - 11 August 2022 =
* Web Font Selector: Resolved a TinyMCE alignment issue introduced by EDD 3.0+.

= 1.33.2 - 04 August 2022 =
* Anchor ID: Resolved a potential Anything Carousel initiation issue.

= 1.33.1 - 27 July 2022 =
* Web Font Selector: Resolved WFS error at `SiteOrigin > Premium Addons` when Black Studio TinyMCE plugin activated.
* WooCommerce Template Builder: Added support for newer versions of [Product Gallery Slider for WooCommerce by codeixer](https://wordpress.org/plugins/woo-product-gallery-slider/).

= 1.33.0 - 15 July 2022 =
* Tabs: Added `Tabs Mobile Position` setting. Conditionally visible when `Tabs position` set to `Right` or `Left`.
* SiteOrigin Premium Updater: Added `Tested up to` version.

= 1.32.2 - 26 June 2022 =
* Anchor ID: Add multi-slider support.
* Anchor ID: Migrated Anything Carousel Anchor ID setting to the Anchor ID Addon.
* Contact Form: Added `siteorigin_widgets_contact_field_attr` filter support.
* Toggle Visibility: Allowed scheduling using only the Date From or Date To field.
* Toggle Visibility: Changed from Unix to the WordPress timezone for scheduling.
* WooCommerce Template Builder: Added support for themes that don't declare WooCommerce support.
* Updated Google Fonts array.
* Licensing: Improved automatic SSL verify detection.
* Developer: Added Anchor ID `siteorigin_premium_anchor_id_scrollto_offset` filter.

= 1.32.1 - 03 June 2022 =
* Anchor ID: Resolved `NaN` added to URL due to Slider autoplay.
* Parallax Sliders: Resolved a potential edge case Legacy Parallax notice.

= 1.32.0 - 25 May 2022 =
* New Addon! Introducing Anchor ID. Link directly to SiteOrigin Slides, Tabs, and Accordion panels.
* WooCommerce Template Builder: Fixed a TinyMCE issue when Black Studio TinyMCE is activated.

= 1.31.2 - 21 May 2022 =
* WooCommerce Template Builder: Fixed non-default template delete loop.

= 1.31.1 - 21 May 2022 =
* Lottie Player: Updated addon banner.
* WooCommerce Template Builder: Added support for the WooCommerce PayPal Payments plugin.
* WooCommerce Template Builder: Removed global `Product Archive` Before/After. Content can now be added per template.
* WooCommerce Template Builder: Fixed potential Single and Archive template duplication on save.
* WooCommerce Template Builder: Added functionality to allow themes to replace WCTB templates.
* WooCommerce Template Builder: Fixed `siteorigin_premium_addon_wc_check_for_woocommerce_file` filter.

= 1.31.0 - 25 April 2022 =
* New Addon! Introducing the SiteOrigin Lottie Player Addon.
* WooCommerce Template Builder: Added Product Archive `Before Product Archive Loop` and `After Product Archive Loop` Layout Builders.
* WooCommerce Template Builder: Added support for the Product Gallery Slider for WooCommerce plugin by codeixer.
* WooCommerce Template Builder: Restored Black Studio Visual Editor Widget compatibility.

= 1.30.2 - 05 April 2022 =
* Toggle Visibility: Resolved an incorrect spacing issue after hidden widgets and rows.
* Web Font Selector: Updated Google fonts list.
* WooCommerce Template Builder: Updated `woocommerce` translation domain instances to `siteorigin-premium`.
* WooCommerce Template Builder: Minor formatting updates.

= 1.30.1 - 28 March 2022 =
* Tooltip: Restored Features Widget support.

= 1.30.0 - 27 March 2022 =
* Carousel: Removed Navigation Dots setting if Default theme is selected.
* Carousel: Prevented potential Overlay theme notice.
* Tooltip: Added Button Widget support.

= 1.29.0 - 05 March 2022 =
* Carousel: New Overlay theme added to the SiteOrigin Post Carousel Widget.

= 1.28.10 - 10 February 2022 =
* Mirror Widgets: Resolved a WP Engine object caching error.
* Licensing: Added automatic SSL verification detection.

= 1.28.9 - 29 January 2022 =
* Updated `Tested up to` tag to `5.9`.
* Set a min-width for the SiteOrigin and Mirror Widgets icon to prevent resizing by MailPoet.
* License Obfuscation: Changed to a password field rather than using stars.
* Developer: Improved license debug functionality.

= 1.28.8 - 16 January 2022 =
* Resolved a PHP 8 specific notice related to the Image Overlay and Tooltip Addon defaults when the Widgets Bundle isn't active.

= 1.28.7 - 10 January 2022 =
* Resolved notice related to the Image Overlay and Tooltip Addon defaults when the Widgets Bundle isn't active.

= 1.28.6 - 09 January 2022 =
* Parallax Sliders: Changed image `loading` attribute from `eager` to `lazy` when AMP plugin is active.
* Developer: Added `siteorigin_premium_web_font_selector_frontend_force_load` filter. Can be used to resolve potential conflicts with third-party plugins that load the TinyMCE on the frontend.

= 1.28.5 - 26 December 2021 =
* Accordion & Tabs: Fixed Panel & Tab icon external URL field.
* Contact Form: Introduced `Current year` and `Next year` options to the `Datepicker max year` setting.
* CPTB: Introduced `siteorigin_premium_cptb_post_type_register` filter. Allows adjustment of the `register_post_type` arguments.

= 1.28.4 - 07 December 2021 =
* Mirror Widgets: Ensured Mirror Widgets are outputted exactly where inserted.
* Toggle Visibility: Minor label adjustments.
* Toggle Visibility: Resolved undefined `style` notice.
* License Management: Improved debugging functionality.
* License Management: Prevented repeated requests to siteorigin.com.
* License Management: Updated license key to be hidden regardless of validity.
* Plugin Updater: Added auto-update support for multi-site installations. Resolved notice.

= 1.28.3 - 02 December 2021 =
* License Management: Improved global notice description.
* License Management: Improved debugging functionality.
* Updated plugin updater.

= 1.28.2 - 23 November 2021 =
* Link Overlay: Add additional widget style check to ensure widget overlay always functions.
* Link Overlay: Removed potential theme link styling.

= 1.28.1 - 17 November 2021 =
* Updated `Tested up to` tag to `5.8.2`.
* Improved license activation notices.
* Improved license activation debugging output.

= 1.28.0 - 06 November 2021 =
* Toggle Visibility: Set the date/time to 23:59. Allows content to be displayed for one day by setting the same To and From date.
* Web Font Selector: Added support for the WordPress Text Widget.

= 1.27.0 - 31 October 2021 =
* New Addon! Multiple Media: Upload multiple images at once to Widgets Bundle Slider and Image Grid type widgets.
* New Addon! Link Overlay: Link an entire Page Builder row, cell, or widget.

= 1.26.2 - 26 October 2021 =
* Web Font Selector: Ensured correct styling when using the Block Editor in Chrome.
* Web Font Selector: Prevented the selector removing unrelated Google Font imports.

= 1.26.1 - 18 October 2021 =
* Toggle Visibility: Updated to function without SiteOrigin Widgets Bundle activated.
* Toggle Visibility: Resolved error when using scheduling in a Layout Builder Widget at Appearance > Widgets.
* WCTB: Ensured templates don't output if WooCommerce or the SiteOrigin Widgets Bundle is deactivated.
* WCTB: Allowed SiteOrigin themes with a Page Title setting to affect WooCommerce Template Builder pages.

= 1.26.0 - 13 October 2021 =
* Contact Form: Prevented date field browser autocomplete.
* Contact Form: Added a new setting to disable the Time field default `Autofill Time Picker With Current Time`.

= 1.25.0 - 11 October 2021 =
* Carousel: Added a `Use item anchor tags in the URL` setting to the Anything Carousel.
* Parallax Sliders: Restored opacity for the Hero and Layout Slider Widgets using legacy parallax type.
* Toggle Visibility: Added a logged in/logged out shortcode. E.g. `[toggle_visibility logged="in"]Only logged in users will see this.[/toggle_visibility]`.
* Toggle Visibility: Added visibility scheduling.

= 1.24.4 - 30 September 2021 =
* Parallax Sliders: Restored opacity for the Hero and Layout Slider Widgets.
* Parallax Sliders: Improved parallax sizing for full-width stretched rows.

= 1.24.3 - 05 September 2021 =
* Block Animations: animate.css upgrade from 3.5.1 to 4.1.1 follow-up. Restored Fade In & Fade In Down animations.
* Web Font Selector: Avoided a potential plugin conflict when used in widget areas.

= 1.24.2 - 26 August 2021 =
* Toggle Visibility: Resolved Hide Row display issue.

= 1.24.1 - 25 August 2021 =
* Web Font Selector: Resolved a widget area, nested field-specific notice.

= 1.24.0 - 17 August 2021 =
* Block Animations: Updated animate.css from 3.5.1 to 4.1.1. Update introduces 20 new animations and improved accessibility!
* Contact Form: Added a new Page Builder Layout Builder field.
* Parallax Sliders: Prevent Jetpack Image Accelerator from replacing Modern Parallax.
* Parallax Sliders: Modern Parallax: Use the image attachment if available.
* Toggle Visibility: Added new visibility options for logged in and logged out users.
* Web Font Selector: Updated Google Fonts list.
* Web Font Selector: Ensured JavaScript only loaded as required.

= 1.23.1 - 20 July 2021 =
* Updated `Tested up to` tag.

= 1.23.0 - 16 July 2021 =
* New Addon! Carousel: Add widgets and layouts to the Anything Carousel items. Additional features and settings to follow!
* Ajax Comments: Ensure comments are enabled before loading addon JavaScript.

= 1.22.0 - 29 June 2021 =
* Parallax Sliders: Added srcset support.
* Custom Post Type Builder: Added a `Display taxonomy column` setting to display the assigned taxonomies for each post in the admin posts list.

= 1.21.5 - 09 June 2021 =
* Image Overlay: Resolved a PHP notice present when previewed before saving.
* Image Overlay: Resolved a JavaScript error present when previewing.
* Parallax Sliders: Resolved a PHP 8 error.

= 1.21.4 - 28 May 2021 =
* Parallax Sliders: Changed fixed background images to scroll on mobile for device compatibility.
* Developer: Added a Contact Form datepicker max year filter `siteorigin_premium_datepicker_max_year`.

= 1.21.3 - 06 May 2021 =
* WCTB: Set the default widget group per template. When adding a new widget the default widget group displayed will match the template.
* WCTB: Improved Product and Archive template deletion processing.
* Mirror Widgets: Fixed documentation link.
* Custom Post Type Builder: Minor updates to various setting descriptions for clarity.
* Premium Addons Page: Improved responsive behavior.

= 1.21.2 - 31 March 2021 =
* WCTB: Resolved `in_array()` notices.

= 1.21.1 - 26 March 2021 =
* WCTB: Restored disassociated templates created prior to version `1.21.0`. 

= 1.21.0 - 22 March 2021 =
* WCTB: New Template! Added a Thank You page template and widgets.
* Image Overlay: Added a new setting: `Animate overlay when image enters screen`.

= 1.20.1 - 02 March 2021 =
* WCTB: Prevented Page Builder from generating unnecessary fallback content. Resolves potential Yoast warning notice.
* WCTB: Added quick and bulk editor support for single product templates at `Products > All Products`.
* Improved addon container resizing on the premium addons page.

= 1.20.0 - 08 February 2021 =
* New Setting! Added a `Display product images` setting to the WCTB Checkout order review widget.
* New Settings! Added a Google Maps `Consent prompt design` settings section at `Plugins > SiteOrigin Widgets > Google Maps: Settings`.
* WooCommerce Template Builder: Fixed template preview button.
* Image Overlay: Reset animations on mobile to account for page resize.
* Parallax Sliders: Prevented `Undefined index` notice.
* WCTB: Only load widgets if the Widgets Bundle is present.
* WCTB: Only attempt to filter product data tabs if tab data exists.
* Web Font Selector: Prevented null values to added @import.
* Developer: Fixed a notice while saving addon settings using PHP 8.

= 1.19.2 - 15 January 2021 =
* Mirror Widgets: Fixed a metabox shortcode typo.
* Developer: Added the option to use legacy parallax via `siteorigin_parallax_sliders_use_new_parallax`.
* Developer: Allowed the datepicker max year to be adjusted `siteorigin_premium_datepicker_max_year`.

= 1.19.1 - 16 December 2020 =
* Loaded Web Font Selector as an external TinyMCE plugin. Prevents possible `Failed to load plugin` notice.
* Developer: Web Font Selector: Don't add null values to `@import`.
* Developer: Added `siteorigin_premium_sslverify` filter.

= 1.19.0 - 09 December 2020 =
* New Setting! Added Mirror Widget shortcodes. Display your Mirror Widgets anywhere using a shortcode.
* New Setting! Added a Google Maps `Center on user's location` setting. Available in the Contact Form location field too.
* Parallax Sliders: Added support for updated Page Builder parallax functionality.
* WooCommerce Template Builder: Resolved a potential issue by checking tabs exist before processing.
* Tooltip: Improved Price Table tooltip display.
* Updated Google Fonts list.
* Developer: Added license key obfuscation.
* Developer: jQuery updates for WordPress 5.6.

= 1.18.0 - 26 October 2020 =
* Lightbox: Allow HTML in the title or caption.
* Lightbox: Add external image support for the Masonry widget.
* Tooltip: Improved tooltip sizing.
* Tooltip: Prevented text shadow in certain widgets.
* Mirror Widgets: Added full Page Builder editing support including the Live Editor.
* Contact Form: Added a location consent prompt.
* Resolved a jQuery Migrate notice on the SiteOrigin > Premium Addons page.
* Block Animations: Added a Disable Animation on Mobile setting.
* WooCommerce Template Builder: Enhanced product data tabs widget to allow for removing, reordering, and renaming.
* Image Overlay: Added a global Touch device width setting.

= 1.17.0 - 20 August 2020 =
* Tooltip: Ensured assets only enqueued once when multiple tooltip widgets in use.
* Tooltip: Resolved tooltip positioning issues when animations are applied to the widget.
* Tooltip: Set a static width to improve display when used with large images.
* Tooltip: Corrected placement in the Simple Masonry widget.
* WooCommerce Template Builder: Added a `Product reviews` widget for use in the single product template.
* WooCommerce Template Builder: Resolved a potential single product Add to Cart button notice.
* WooCommerce Template Builder: Resolved Add to Cart button text saving in the Product archive template.

= 1.16.1 - 22 July 2020 =
* Image Overlay: Improved wrapper filter multi-image support.
* Image Overlay: Correctly check for overlay containers inside of links.

= 1.16.0 - 22 July 2020 =
* WooCommerce Template Builder: Added `widget_before` and `widget_after` arguments. These arguments are used heavily by SiteOrigin Page Builder.
* WooCommerce Template Builder: Added button text settings to the Add to Cart button.
* Block Animations: Changed hide functionality from JavaScript to CSS for improved performance.
* Contact Form: Resolved an issue where the form isn't able to be submitted if a date format other than the default is used.
* Image Overlay: Added overlay container directly inside of widget destination link.
* Image Overlay: Added `siteorigin_premium_image_overlay_wrapper_data` to allow filtering of the image overlay wrapper data.
* Parallax Sliders: Added support for the SiteOrigin Page Builder `Disable Background Parallax On Mobile` global setting. 

= 1.15.1 - 21 June 2020 =
* Contact Form: Resolved datepicker submission issue when field set to required and date format not set to default.

= 1.15.0 - 15 June 2020 =
* Tooltip: Added SiteOrigin Features support.
* Contact Form: Datepicker - Added min/max year settings at `SiteOrigin > Premium Addons > Contact Form: Settings`.
* Contact Form: Datepicker - Added a "Use WordPress date format" setting at `SiteOrigin > Premium Addons > Contact Form: Settings`. Custom format isn't supported.
* Contact Form: Timepicker - Defaulted to the user-defined WordPress time format. The "Use 24h format." setting will override this.

= 1.14.1 - 17 May 2020 =
* Image Overlay: Allow for links in the Title field by applying image container to first parent `div`.

= 1.14.0 - 15 May 2020 =
* WooCommerce Template Builder: Add submenu link immediately after activation.
* Contact Form: Apply field design settings to datepicker field.
* Premium Addons: Added `Manage` buttons to the WCTB, Mirror Widgets and CPT Builder at `SiteOrigin > Premium Addons`.
* Web Font Selector: Ensure TinyMCE is loaded before using it.
* WooCommerce Template Builder: Improved theme compatibility, Shop template also check for `woocommerce.php`.
* WooCommerce Template Builder: Added Product additional information widget.
* WooCommerce Template Builder: Added Product description widget.
* Image Overlay: Ensured that image links are active while the overlay is in use.
* Updated the Chosen select box enhancer library.
* Web Font Selector: Added select field placeholder text for mobile devices.
* Tooltip: Added Icon widget support.
* Tooltip: Removed title attribute when tooltip displays and re-add when tooltip isn't displaying.
* Tooltip: Added SiteOrigin Price Table feature hover text field support.

= 1.13.0 - 07 April 2020 =
* Lightbox: Fixed Simple Masonry title display.
* Block Animations: Added a slide out animation setting to the Layout Slider.

= 1.12.1 - 06 March 2020 =
* WooCommerce Template Builder: Minor admin area design fixes.
* WooCommerce Template Builder: Added template tooltips.
* Lightbox: Slider - account for sliders that only have a foreground image.
* Lightbox: Slider - output image caption or title.
* Lightbox: If image caption exists, use caption over title.
* Lightbox: If captions are enabled prevent XSS.

= 1.12.0 - 30 January 2020 =
* Lightbox: Updated Lightbox2 to v2.11.1.
* Lightbox: Added a Disable Scrolling setting.
* Social Media Buttons: Properly center custom icon images.
* Accordion: Added a custom image icon setting.
* Tabs: Added a custom image icon setting.
* Image Overlay: Added a Desktop Device Trigger setting. Show on hover or always show.
* WooCommerce Template Builder: Fixed action button sizing.

= 1.11.0 - 31 December 2019 =
* Mirror Widgets: Removed user role restriction.
* Lightbox: Added a global setting to disable on tablet and mobile.
* Image Overlay: Fixed alignment when used with full-width stretched rows.
* Tooltip: Fixed line-height when used on the SiteOrigin Image Grid widget.

= 1.10.2 - 29 October 2019 =
* WooCommerce Template Builder: Updated Empty Cart default layout to ensure full-width.
* WooCommerce Template Builder: Ensured WooCommerce notices are output before each template layout.
* WooCommerce Template Builder: Updated Product Default template to include Product Rating widget.
* WooCommerce Template Builder: Return `$template` instead of `$template_name` from `get_woocommerce_template_part` filter.

= 1.10.1 - 19 September 2019 =
* Support older versions of PHP.
* Mirror Widgets: Made post type hidden from public view.
* Ajax Comments: Fix for HTML5 themes.

= 1.10.0 - 6 August 2019 =
* New addon! WooCommerce Templates: Create custom WooCommerce templates using the power of SiteOrigin Page Builder.
* Prevent some undefined index notices in Block Editor.
* Move recommended plugins initialization to `plugins_loaded` action.
* Image Overlay: Add support for Jetpack image lazy loading.
* Tooltip: Add support for Jetpack lazy image loading.
* Animations: Perform 'hide before' initial setup in JS, so it's used by both block animations and hero content animations.
* Web Fonts Selector: Updated Google Fonts.
* Lightbox: Add fallback support for Image and Image Grid.

= 1.9.0 - 15 May 2019 =
* Mirror Widgets: New addon! Create a widget once, use it everywhere. Update it and the changes reflect in all instances of the widget.
* Image Overlay: Changed default overlay opacity to 0.8.
* Image Overlay: Option for when to display overlay on touch devices.
* Image Overlay: Prevent animation if overlay already in requested state.
* Image Overlay: Update on image grid layout complete.
* Fonts Selector: Prevent importing web safe fonts.
* Fonts Selector: Allow use of font variants.
* Add filter to disable background update checks.
* Add filter to disable TGMPA.

= 1.8.0 - 17 April 2019 =
* Image Overlay: New addon! Add a beautiful and customizable text overlay with animations to your images.

= 1.7.1 - 2 April 2019 =
* Fonts Selector: Remove dependency on Page Builder constant.
* Fonts Selector: Ensure $ is defined.
* Fonts Selector: Fix layout styles in different contexts.
* Fonts Selector: Keep focus on the Chosen search input.
* Fonts Selector: Fix for WP 4.9.9.

= 1.7.0 - 26 March 2019 =
* Fonts Selector: New addon feature! Choose from hundreds of beautiful web fonts in the visual editor.
* Properly hide addon settings iframe.
* Contact form location field: Use new location type to trigger missing API key warning.
* SO CSS Fonts Selector: Ensure font value is updated on first change.

= 1.6.0 - 12 March 2019 =
* Map Styles: New addon! Select from a curated set of predefined styles.
* Contact form location field: Removed widget form API key field.
* CPT Builder: Fix undefined index 'hierarchical' issue.

= 1.5.5 - 27 February 2019 =
* Create a more friendly error message for unauthorized errors.
* Switched addon videos in admin to Vimeo.
* Tabs: Ability to set position of tabs.
* Contact form: Add DateTime picker setting to optionally hide disabled times.
* CPT Builder: Made warnings stand out more when editing slugs of post types with existing posts.
* CPT Builder: Allow setting post types as hierarchical.
* Contact form: Add DateTime picker setting for always visible calendar.
* Parallax Sliders: Disable fixed background on mobile.

= 1.5.4 - 30 December 2018 =
* Allow for translation of plugin and addon file headers.
* Contact form fields: Prevent PHP Warning when `$instance` is empty.
* Animations: Only setup animations for widgets when in a preview.

= 1.5.3 - 18 December 2018 =
* Animations: Allow non-repeatable hover animations.
* Show SiteOrigin Page Builder, Widgets Bundle and CSS as recommended plugins.
* Tooltip: Prevent tooltip from always being present after showing in FireFox.
* CPT Builder: Use classic editor for SO custom post types.
* Contact form location field: Use global Google Maps API key if available.
* Animations: Add option to set the final state of an element after it's animation has completed.
* CPT Builder: Register custom post types earlier on `init`.
* Hero animations: Pass selector to front end so Element Enters Screen and Element In Screen triggers work.

= 1.5.2 - 28 November 2018 =
* Contact Form: Changed first day capitlisation.
* Block editor: Ensure scripts are enqueued and run for block editor previews.
* Block editor: Tabs/Accordion: Allow builder fields in the block editor.
* Correct nonce check for addon status change.
* Block animations: Fix missing jQuery.
* Block animations: Added debounce setting to control debounce for 'Element Enters Screen' and 'Element In Screen' events.
* Tooltip: Fix positioning when both tooltip and lightbox are enabled for an image.

= 1.5.1 - 12 October 2018 =
* Avoid 'undefined index' notices for style fields when using 'label' instead of 'name'.

= 1.5.0 - 12 October 2018 =
* Toggle Visibility Addon!
* Social Widgets: Check if icon is selected and use icon_name as name if no name is set.
* Social Widgets: Fix icon color not outputting and icon image repeat if no network name.
* Block Animations: Option to repeat hover animation until mouse leaves triggering element.
* Truncate long EDD filenames to prevent updates failing on Windows.
* Updated to EDD updater 1.6.17

= 1.4.4 - 10 September 2018 =
* Contact datepicker: Format selected date with il8n.
* Contact datepicker: Removed default date to ensure user has to select a date.
* Web font selector: Ensure changes are detected.

= 1.4.3 - 20 August 2018 =
* Parallax Sliders: Added fixed background option for the Hero and Layout Sliders.
* Contact Form: Added a setting for the first day of the week and made the form translation ready.

= 1.4.2 - 18 July 2018 =
* Correct tooltip y position when image overflows container vertically.
* CPT Builder: Fix widget with presets changes not propagating to CPT instances.
* Fix undefined index notice when creating new custom post type.
* Replace post with get

= 1.4.1 - 9 July 2018 =
* Fix fatal error for PHP 5.2. :(

= 1.4.0 - 3 July 2018 =
* New Tooltip addon!
* Added Web Font Selector video link.
* Added Social Widgets video.
* Optimized images.
* Update license status when doing update check. Display admin notices for invalid/expired licenses.

= 1.3.3 - 24 May 2018 =
* Fixed fatal error for PHP <= 5.4. :(

= 1.3.2 - 22 May 2018 =
* Fixed copy issues in a few places.
* Added Hero documentation link and video.
* Changed Social Widgets description.
* Social Widgets: Add button to description.
* Added new admin addon icons.
* Added videos for tabs and testimonials.
* CPTB: Replaced individual permissions with single option to allow editing of layout in post type instances.
* CPTB: Use widgets' content from post type instances and update widgets' content in instances unless edited.
* CPTB: Warn when changing non-editable layouts and there are existing instances of the post type.

= 1.3.1 - 9 April 2018 =
* CPT Builder: Prevent filtering out widgets added to custom post type instances.
* Animations: Only perform animations once.

 = 1.3.0 - 2 April 2018 =
* Contact: Auto responder!
* Hero: Addon to allow animation of Hero frames content!
* Contact: DateTime field has option to use 24h format for times.
* Web Font Selector: IE 11 Compat. Don't use `Array.from`.
* Added missing documentation links for Web Font Selector, Call-to-action and Testimonials addons.
* AJAX Comments: Disable comments form submit button when comment submitted.
* Moved animate JS and CSS to common folders and register in main file.
* Contact: Renamed addon for consistency with other widget addon names.
* Accordion: Option to scroll to a specific panel on load.

= 1.2.1 - 31 January 2018 =
* CPT Builder: Allow customization of available Page Builder features for the custom post type.
* Accordion: Moved presets field to above title field.
* Accordion: updated presets to use 16px for all panels and white font for Rounded preset.
* Fix PHP compatibility error.
* Lightbox: Added documentation link.
* Lightbox: Ensure instance specific settings are applied.
* CPT Builder: Prevent custom post types from showing in Page Builder settings list.
* CPT Builder: Use `widgets_init` action to register custom post types.
* Lightbox: Added global settings for overlay color and opacity.
* Testimonials: Font family and size options.
* CTA: Font family and size options.
* Accordion: Allow item specific title icons.
* CPT Builder: Add option of excluding custom post type from search.
* CPT Builder: Add description to Hierarchical to explain what it does.
* CPT Builder: Taxonomy items use label name in editor.

= 1.2.0 - 7 November 2017 =
* New Tabs Widget addon!
* Accordion: Use new presets field.
* Accordion: Allow for setting panels font family and size.
* Accordion: Allow for setting headings text transform.
* Accordion: Deep linking to single/multiple panels.
* Add rel="noopener noreferrer" for all 3rd party/unknown links.

= 1.1.2 - 20 October 2017 =
* Fix lightbox in slider and layout slider widgets.

= 1.1.1 - 12 October 2017 =
* Fix missing js lib.

= 1.1.0 - 11 October 2017 =
* New Accordion widget addon!
* Contact: Apply field label styles to DateTime field labels too.
* Pass post name through `sanitize_reserved_post_types` before using as post type slug.
* Spacing between addon item buttons.

= 1.0.7 - 19 September 2017 =
* Update to latest EDD updater
* Removed submodules and adding addon files back into main repo.
* Animate hiding/showing Lightbox fields.
* Added global and instance lightbox settings to disable captions.
* Prevent JS error when style attribute is empty string.
* Prevent error in Hero widget when lightbox is active.

= 1.0.6 - 9 September 2017 =
* Removed accidentally included addon folder.

= 1.0.5 - 6 September 2017 =
* Lightbox: Fix image widgets using 'image_set_slug'.
* Lightbox: Removed 'disable_scrolling' option which doesn't appear to work.
* Lightbox: Use `_sow_form_id` as slug for images already in group.
* Lightbox: Image widget fallback to using `_sow_form_id`.
* Lightbox: Use 'full' image sizes for lightbox.
* Lightbox: Use album name instead of image set slug.
* Lightbox: Conditional display of album name input when lightbox enabled.

= 1.0.4 - 7 August 2017 =
* Contact form fields: Don't apply disabled date ranges if parsing fails.
* Contact form fields: Google maps widget and contact form location field working together.
* Web font selector: Allow font family without quotes.
* Web font selector: Correct import URLs.
* Web font selector: Select first variant if no 'regular' variant exists.
* AJAX comments: Account for error handler.
* AJAX comments: Check for existing error before error.
* AJAX comments: Account for encoded text.
* AJAX comments: Correct spacing.
* AJAX comments: Move timer.
* Animations: Add hover event.
* Changed to an autoloader system.
* Move addons to submodules.
* Global settings for addons.
* Lightbox: New lightbox addon!

= 1.0.3 - 28 September 2016 =
* Added Google Font Field addon for SiteOrigin CSS.
* Disable Ajax Comments on WooCommerce to avoid conflict.
* Fixed Contact Form addon Date Picker

= 1.0.2 - 25 August 2016 =
* Various date picket contact form field improvements.
* Fix build script to remove node modules.
* JS fix to get menu working properly on multisite.

= 1.0.1 - 16 August 2016 =
* Fixed license checking and plugin updating.

= 1.0 - 12 August 2016 =
* Initial release.
