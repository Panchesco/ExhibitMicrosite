<?php

/**
 *
 * NOTE: If a native Exhibit Builder default Gallery Block is rendering, and you're seeing a
 * Warning : Undefined array key “imgAttributes”, it's an Omeka Bug:
 * https://forum.omeka.org/t/gallery-block-error-message-undefined-array-key/16146
 *
 * Wrap for non-ems blocks:
 */

echo $this->view->partial("microsite-header.php", [
  "title" => $microsite->options["microsite_title"],
  "subheading" => $microsite->options["microsite_subheading"],
  "theme_options" => $theme_options,
  "global_nav" => $nav->top_pages_html,
  "bodyid" => $microsite->route,
  "bodyclass" => "show",
  "view" => $this->view,
  "refUri" => $refUri,
  "route" => $route,
  "microsite" => $microsite,
  "breadcrumb" => $breadcrumb,
  "exhibit" => $exhibit,
  "exhibit_theme_options" => $exhibit_theme_options,
  "exhibitPage" => $exhibitPage,
]); ?>
<nav id="breadcrumb">
 <?php echo $breadcrumb->html; ?>
</nav>
<h1><?php echo $exhibitPage->title; ?></h1>
<div class="flex-blocks-wrapper d-flex flex-wrap g-0">
<?php foreach ($exhibitPage->ExhibitPageBlocks as $block): ?>
<?php
$layout = $block->getLayout();
$options = $block->getOptions();
$attachments = $block->getAttachments();
set_current_record("exhibit", $exhibit, true);
echo get_view()->partial($layout->getViewPartial(), [
  "attachments" => $attachments,
  "block" => $block,
  "exhibit" => $exhibit,
  "exhibit_theme_options" => $exhibit_theme_options,
  "exhibitPage" => $exhibitPage,
  "item_route" => $item_route,
  "options" => $options,
  "slug" => $microsite->params->paramsArray["slug"],
  "text" => $block->text,
  "refUri" => $refUri,
  "route" => $route,
  "microsite" => $microsite,
  "breadcrumb" => $breadcrumb,
]);
?>
<?php endforeach; ?>
</div><!-- end .flex-blocks-wrapper -->
<?php echo foot(); ?>
