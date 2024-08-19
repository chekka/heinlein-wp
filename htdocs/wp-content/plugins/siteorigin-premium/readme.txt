=== SiteOrigin Premium ===
Requires at least: 4.7
Tested up to: 6.6.1
Requires PHP: 7.0.0
Stable tag: 1.65.0
Build time: 2024-08-16T22:02:19+02:00
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

= 1.65.0 - 16 August 2024 =
* Social Widgets: Restored existing image icon functionality.
* WooCommerce Template Builder: Added a Single template shortcode insertion option. Insert Single template designs anywhere with ease.
* Updated warning message for addons requiring Page Builder.
* Updater: Cleared cache after an update has been processed.

= 1.64.1 - 08 August 2024 =
* Anchor ID: Fixed Anchor ID `Maximum Number of Simultaneous Open Panels` behavior.
* Anchor ID: Improved Accordion and Tab on load scroll.
* Related Posts: Optimized and improve taxonomy handling.
* WooCommerce Template Builder: Prevented a potential Cart PHP 8 error.

= 1.64.0 - 26 July 2024 =
* Anchor ID: Added repeated hash prevention to the Anything Carousel, Sliders, and Tabs Widget.* Block Animations: Resolved a potential `TypeError` and accounted for elements not setup/loaded.
* WooCommerce Template Builder: Moved After Archive output below pagination.
* WooCommerce Template Builder: Added compatibility for the `TP Product Image Flipper for WC` plugin.
* WooCommerce Template Builder: Added `so_woocommerce_templates_display_before/after_archive` filters.

= 1.63.1 - 22 June 2024 =
* Anchor ID: Improved Anything Carousel performance.
* 404 Page: Restored theme page settings for the Display > Page option.

= 1.63.0 - 17 June 2024 =
* New Addon! 404 Page: Create custom 404 error pages with personalized design and content. Guide your user's website experience even during misdirections.
* Anchor ID: Improved functionality with hash change, Accordion, Tab, and scroll fixes, better placement and loading.
* Author Box: Added `Margin Top` setting to the built-in Recent Posts Widget.
* Block Animations: Added min/max as required to prevent a possible console error.
* Toggle Visibility: Resolved Yoast Open Graph conflict with metabox content block.
* Updated Google Fonts.
* Updated SiteOrigin Installer.

= 1.62.1 - 26 May 2024 =
* Anchor ID: Update to allow for an empty Accordion and Tabs ID field. An ID is recommended.
* Block Animations: Resolved potential TypeError.
* Toggle Visibility: Added `siteorigin_premium_toggle_visibility_metabox_block_message` to adjust logged out message.
* Resolved potential blank addon settings modal.
* Prevented auto-updates if disabled.

= 1.62.0 - 19 May 2024 =
* New Addon! Enhance contact form security with the Cloudflare Turnstile Addon, a user-friendly CAPTCHA alternative that helps prevent spam while maintaining seamless user interaction.
* Anchor ID: Fixed ID detection.
* Author Box: Minor spacing and layout improvements.
* Post Carousel: Added title tag to the link overlay.
* Social Media Buttons: Added a fallback if Network Name field is empty.
* Toggle Visibility: Resolved potential PHP warning.
* WooCommerce Template Builder: Removed Shop Product Loop widget from Product Archive tab.
* Increased required PHP version to PHP 7.

= 1.61.1 - 17 April 2024 =
* Anchor ID Addon: Refactored and centralized management, improving widget coordination and simplifying future maintenance.
* Improved plugin update checker reliability and efficiency by refactoring version information handling.

= 1.61.0 - 12 April 2024 =
* New Addon! Introducing the Author Box Addon. Automatically append author boxes to posts, featuring social links, recent articles, and bios to create engaging author presentations across multiple post types.
* WooCommerce Template Builder: Resolved undefined array key "status" warning.
* Post Carousel: Resolved an issue importing layouts with empty theme data.
* Toggle Visibility: Accounted for a potential migration issue for legacy rows.
* Improvements to automatic updates.
* Improvements to data sanitization.
* Updated SiteOrigin Installer.
* Embed Blocker: Added multi-measurement padding.

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

= 1.55.0 - 05 January 2024 =
* Blog: Optimized supporting JavaScript assets.
* Parallax Sliders: Ensured scripts only loaded when needed.
* WooCommerce Template Builder: Added Genesis, Genesis Connect, and Block Editor compatibility.
* WooCommerce Template Builder: Added Product Meta Widget display options.
* Video Background: Added a Loop Video setting.
* Video Background: Added a Background Video Display setting.
* Video Background: Adjusted secondary settings to conditionally display when a video is added.
* Video Background: Updated the Background Video Opacity setting to conditionally display if a Background Video has been set.

[View full changelog.](/wp-content/plugins/siteorigin-premium/changelog.txt)
