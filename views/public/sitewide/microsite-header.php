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

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin />
    <!-- We use the full link to the CSS file in the rest of the tags -->

     <link rel="stylesheet"
                href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital@0;1&family=IBM+Plex+Mono" />
    <link rel="stylesheet"
                href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital@0;1&family=IBM+Plex+Mono:wght@200;400&family=Oswald:wght@200;300;400;600;700&display=swap"
                media="print" onload="this.media='all'" />

    <noscript>
            <link rel="stylesheet"
                    href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital@0;1&family=IBM+Plex+Mono:wght@200;400&family=Oswald:wght@200;300;400;600;700&display=swap" />
    </noscript>
    <?php
    queue_css_file(["bootstrap", "default"]);
    echo head_css();
    ?>
    <!-- JavaScripts -->
    <?php
    queue_js_file(["globals"]);
    echo head_js();

    if (isset($exhibit_theme_options) and is_array($exhibit_theme_options)) {
      extract($exhibit_theme_options);
    }

    // Set some defaults in case they weren't set in the theme.
    $header_image_colspan = isset($header_image_colspan)
      ? $header_image_colspan
      : 12;

    $header_wrapper_colspan = isset($header_image_colspan)
      ? $header_image_colspan
      : 12;

    $headings_wrapper_colspan = isset($headins_wrapper_colspan)
      ? $headings_wrapper_colspan
      : 12;

    $heading_colspan = isset($heading_colspan) ? $heading_colspan : 12;

    $suheading_colspan = isset($subheading_colspan) ? $subheading_colspan : 12;

    $header_justify_content = isset($header_justify_content)
      ? $header_justify_content
      : "justify-content-start";

    $header_align_items = isset($header_align_items)
      ? $header_align_items
      : "align-items-center";
    ?>
</head>
 <?php echo body_tag([
   "id" => @$bodyid,
   "class" => @$bodyclass,
 ]); ?>
    <a href="#content" id="skipnav" class="sr-only"><?php echo __(
      "Skip to main content"
    ); ?></a>
    <?php fire_plugin_hook("public_body", ["view" => $this]); ?>
    <?php fire_plugin_hook("public_content_top", ["view" => $this]); ?>
        <header role="banner">
            <?php fire_plugin_hook("public_header", ["view" => $this]); ?>
            <div class="container">
            <div class="row">
            <div class="header-wrapper col-lg-<?php echo $header_wrapper_colspan; ?>">
              <div class="d-lg-flex border border-dark">
                <div id="header-image-wrapper" class="col-lg-<?php echo $header_image_colspan; ?>">
                  <img class="lazy" src="<?php echo WEB_FILES .
                    "/theme_uploads/" .
                    $header_image; ?>" alt="<?php echo $header_image_alt; ?>">
                </div>
              </div><!-- end .d-lg-flex -->
              <div class="d-lg-flex border border-dark">
                <div class="site-title-wrapper">
                 <a href=""><span id="site-title"><?php echo $title; ?></span><br>
                  <span id="identity-subheading" class="subheading h2"><?php echo $subheading; ?></span>
                 </a>
                </div><!-- end .col -->
                </div><!-- end .d-xl-flex -->
              </div><!-- end .header-wrapper -->
            </div><!-- end .row -->
               </div><!-- end .container -->
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
            <nav class="flyouts">
                <div id="nav-collections" class="ems-flyout">
             <?php echo $view->partial("exhibit-pages/index-listing.php", [
               "microsite" => $microsite,
             ]); ?>
                </div><!-- end #nav-collections -->
                <nav class="flyouts">
                    <div id="nav-search" class="ems-flyout">
                 <?php echo $view->partial("sitewide/simple-search.php", [
                   "microsite" => $microsite,
                   "breadcrumb" => $breadcrumb,
                 ]); ?>
                    </div><!-- end #nav-search -->
            </nav>
          <div id="content" role="main" tabindex="-1">
            <div class="container">
               <?php
               print_r("<pre>");
               print_r($exhibit_theme_options);
               print_r("</pre>");
               ?>

