<!DOCTYPE html>
<html lang="<?php echo get_html_lang(); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=yes" />
    <?php if ($author = option("author")): ?>
    <meta name="author" content="<?php echo $author; ?>" />
    <?php endif; ?>
    <?php if ($copyright = option("copyright")): ?>
    <meta name="copyright" content="<?php echo $copyright; ?>" />
    <?php endif; ?>
    <?php if ($description = option("description")): ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php endif; ?>
    <?php if (isset($canonicalURL) && !empty($canonicalURL)): ?>
      <link rel="canonical" href="<?php echo $canonicalURL; ?>">
    <?php endif; ?>
    <?php
    if (isset($title)) {
      $titleParts[] = strip_formatting($title);
    }
    $titleParts[] = option("site_title");
    ?>
    <title><?php echo implode(" &middot; ", $titleParts); ?></title>

    <?php echo auto_discovery_link_tags(); ?>

    <!-- Plugin Stuff -->
    <?php fire_plugin_hook("public_head", ["view" => $this]); ?>

    <!-- Stylesheets -->
    <?php
    queue_css_file(["bootstrap", "default"]);
    queue_css_url(
      "https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
    );
    queue_css_url(
      "https://fonts.googleapis.com/css2?family=EB+Garamond:ital@0;1&family=IBM+Plex+Mono:wght@200;400&family=Oswald:wght@200;300;400;600;700&display=swap"
    );
    echo head_css();
    ?>
    <!-- JavaScripts -->
    <?php
    queue_js_file(["globals"]);
    echo head_js();
    ?>
</head>
 <?php echo body_tag(["id" => @$bodyid, "class" => @$bodyclass]); ?>
    <a href="#content" id="skipnav" class="sr-only"><?php echo __(
      "Skip to main content"
    ); ?></a>
    <?php fire_plugin_hook("public_body", ["view" => $this]); ?>
    <?php fire_plugin_hook("public_content_top", ["view" => $this]); ?>
        <header role="banner">
            <?php fire_plugin_hook("public_header", ["view" => $this]); ?>
          <div class="identity">
            <div class="container">
              <div class="d-xl-flex logo-identity-wrapper">
                <div id="logo-wrapper">
                  <a href="<?php echo url(
                    [
                      "controller" => "default",
                      "action" => "show",
                      "slug" => $params->slug,
                    ],
                    "ems_exhibitLanding"
                  ); ?>"><?php echo theme_logo(); ?></a>
                </div>
                <div class="site-title-wrapper">
                 <a href=""><span id="site-title"><?php echo $title; ?></span><br>
                  <span id="identity-subheading" class="subheading h2"><?php echo $subheading; ?></span>
                 </a>
                </div><!-- end .col -->
                </div><!-- end .d-xl-flex -->
                </div><!-- end .container -->
              </div><!-- end .identity -->
            <div class="nav-wrapper">
            <div class="container">
              <div class="d-flex justify-content-end">
                <div id="header-region-two">
                  <nav class="global-nav"><?php echo $global_nav; ?></nav>
                  </div><!-- end header-region-two -->
              </div><!-- end .d-flex -->
            </div><!-- end .container -->
            </div><!-- end .nav-wrapper -->
        </header>
        <main>
          <div id="content" role="main" tabindex="-1">
            <div class="container">

