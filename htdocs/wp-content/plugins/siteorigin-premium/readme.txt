=== SiteOrigin Premium ===
Requires at least: 4.7
Tested up to: 6.5.5
Requires PHP: 7.0.0
Stable tag: 1.63.0
Build time: 2024-06-17T14:53:13+01:00
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

[View full changelog.](/wp-content/plugins/siteorigin-premium/changelog.txt)
