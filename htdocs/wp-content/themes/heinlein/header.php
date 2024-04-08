<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?> id="heinlein" data-owner="heinlein">

<head>
   <title><?php wp_title(" | ", "echo", "right"); ?><?php bloginfo("name"); ?></title>
   <meta charset="<?php bloginfo( 'charset' ); ?>">
   <meta name="viewport" content="width=device-width, initial-scale=1" />
   <meta name="msapplication-TileColor" content="#17254c">
   <meta name="theme-color" content="#17254c">
   <link rel="profile" href="https://gmpg.org/xfn/11">
   <?php wp_head(); ?>
   <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
   <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
   <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
   <link rel="manifest" href="/site.webmanifest">
   <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#fff">

   <link rel="preconnect" href="https://i.vimeocdn.com" crossorigin>
   <link rel="preconnect" href="https://f.vimeocdn.com" crossorigin>
   <link rel="preconnect" href="https://fresnel.vimeocdn.com" crossorigin>
   <link rel="preconnect" href="https://player.vimeo.com" crossorigin>
   <link rel="preconnect" href="https://vimeo.com" crossorigin>
   <link rel="preconnect" href="https://www.google-analytics.com" crossorigin>
   <link rel="preconnect" href="https://www.googletagmanager.com" crossorigin>

   <!-- Google Consent Mode -->
   <!--
   <script data-cookieconsent="ignore">
   window.dataLayer = window.dataLayer || [];

   function gtag() {
      dataLayer.push(arguments)
   }
   gtag("consent", "default", {
      ad_storage: "denied",
      analytics_storage: "denied",
      functionality_storage: "denied",
      personalization_storage: "denied",
      security_storage: "granted",
      wait_for_update: 500
   });
   gtag("set", "ads_data_redaction", true);
   gtag("set", "url_passthrough", true);
   </script>
-->
   <!-- End Google Consent Mode-->

   <!-- Google Tag Manager -->
   <!--
   <script data-cookieconsent="ignore">
   (function(w, d, s, l, i) {
      w[l] = w[l] || [];
      w[l].push({
         'gtm.start': new Date().getTime(),
         event: 'gtm.js'
      });
      var f = d.getElementsByTagName(s)[0],
         j = d.createElement(s),
         dl = l != 'dataLayer' ? '&l=' + l : '';
      j.async = true;
      j.src =
         'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
      f.parentNode.insertBefore(j, f);
   })(window, document, 'script', 'dataLayer', 'GTM-PLHDM7L');
   </script>
-->
   <!-- End Google Tag Manager -->

</head>

<body <?php body_class(); ?>>

   <header class="site-header">
      <div class="container">
         <?php 
            if ( is_active_sidebar( 'header' )):
               dynamic_sidebar( 'header' );
            endif; 
         ?>
         <a href="/" class="site-logo"><img src="/wp-content/themes/heinlein/assets/images/heinlein-plastiktechnik-logo.svg"></a>
      </div>
   </header>