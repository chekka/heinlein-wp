<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?> id="heinlein" data-owner="heinlein">

<head>
  <title><?php wp_title(" | ", "echo", "right"); ?><?php bloginfo("name"); ?></title>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta http-equiv="ScreenOrientation" content="autoRotate:disabled">
  <meta name="viewport" content="width=device-width, initial-scale=1" />   
  <meta name="msapplication-TileColor" content="#17254c">
  <meta name="theme-color" content="#17254c">
  <link rel="profile" href="https://gmpg.org/xfn/11">
  <?php wp_enqueue_script("jquery"); ?>
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
</head>

<body <?php body_class(); ?>>

  <header class="site-header" id="top">
    
      <?php 
        function contains_partial_class($partial) {
          $body_classes = get_body_class();
          foreach ($body_classes as $class) {
            if (strpos($class, $partial) !== false) {
              return true;
            }
          }
          return false;
        }
        
        // Usage example
        if (!contains_partial_class('landingpage')) {
          if ( is_active_sidebar( 'header' )):
            dynamic_sidebar( 'header' );
          endif;
        }
      ?>
      <a href="/" class="site-logo">
        <picture>
          <source srcset="/wp-content/themes/heinlein/assets/images/heinlein-plastiktechnik-signet.svg" media="(max-width: 580px)" width="80" height="110">
          <img src="/wp-content/themes/heinlein/assets/images/heinlein-plastiktechnik-logo.svg" alt="Heinlein Plastik-Technik Logo" width="240" height="90" loading="eager" decoding="sync">
        </picture>
      </a>         
    
  </header>