<?php
/**
 * $title
 * $subheading
 * $bodyid
 * $bodyclass
 * $global_nav
 * $microsite
 * $view
 * $breadcrumb
 * $exhibit_theme_options
*/
?>
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
    // Set some defaults in case they haven't yet been set in the theme config.
    $header_logo = isset($header_logo) ? $header_logo : 12;
    $header_logo_alt = isset($header_logo_alt) ? $header_logo_alt : "";
    $header_layout = isset($header_layout) ? $header_layout : "logo-left";
    ?>
    <style>



    </style>
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
            <div class="identity-wrapper bottom-shadow">
               <div class="header-container-all container g-0 <?php echo $header_layout; ?>">
                     <div class="col-identity-wrapper col-12">
                           <?php if (
                             isset($header_logo) &&
                             !empty($header_logo)
                           ): ?><div class="header-logo-wrapper">
                              <a href="<?php echo $microsite->url; ?>"><img src="<?php echo WEB_FILES .
  "/theme_uploads/" .
  $header_logo; ?>" alt="<?php echo $header_logo_alt; ?>" /></a>
                           </div><!-- end .header-logo-wrapper -->
                           <?php endif; ?>
                           <div class="headings-wrapper">
                              <div class="heading-wrapper">
                                 <?php if (
                                   $microsite->is_exhibit_landing
                                 ): ?><h1><?php echo $microsite->heading; ?></h1><?php else: ?><div class="h1"><?php echo $microsite->heading; ?></div><?php endif; ?>
                              </div>
                              <div class="subheading-wrapper">
                                 <?php echo $microsite->subheading; ?>
                              </div><!-- end .subheading-wrapper -->
                           </div><!-- end .headings-wrapper -->
                     </div><!-- end .col-identity-wrapper -->
               </div><!-- end .header-container-all -->
            </div><!-- end .identity-wrapper -->
<?php echo $view->partial("sitewide/header-nav.php", [
  "global_nav" => $global_nav,
  "microsite" => $microsite,
]); ?>
        </header>
        <main>
          <div id="content" role="main" tabindex="-1">
            <div class="container">


