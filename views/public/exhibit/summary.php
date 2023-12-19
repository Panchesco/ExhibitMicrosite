<?php

set_current_record("exhibit", $exhibit);
echo $this->view->partial("microsite-header.php", [
  "title" => $microsite->options["microsite_title"],
  "subheading" => $microsite->options["microsite_subheading"],
  "theme_options" => $theme_options,
  "exhibit_theme_options" => $exhibit_theme_options,
  "params" => $params,
  "global_nav" => $nav->top_pages_html,
  "microsite" => $microsite,
  "bodyid" => $microsite->route,
  "bodyclass" => "summary",
  "view" => $this->view,
  "refUri" => $refUri,
  "breadcrumb" => $breadcrumb,
]);
?>

<div id="exhibit-content" class="row ">
<div class="col col-lg-12">
<nav id="breadcrumb">
  <?php
//echo $breadcrumb;
?>
</nav>
<div class="row justify-content-around">
<div class="col-lg-8">
<h1><?php
//echo metadata("exhibit", "title");
?></h1>

<?php if (
  $exhibitDescription = metadata("exhibit", "description", [
    "no_escape" => true,
  ])
): ?>
<div class="exhibit-description">
    <?php echo $exhibitDescription; ?>
</div>
<?php endif; ?>
  </div><!-- end col-lg-8 -->
</div><!-- end .row -->

<?php if ($exhibitCredits = metadata("exhibit", "credits")): ?>
<div class="exhibit-credits">
    <h3><?php echo __("Credits"); ?></h3>
    <p><?php echo $exhibitCredits; ?></p>
</div>
<?php endif; ?>
</div><!-- /.col-lg-8 -->
</div><!-- /.row -->
<?php echo foot(); ?>
