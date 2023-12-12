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
  "params" => $params,
  "global_nav" => $nav->top_pages_html,
]); ?>
<nav id="breadcrumb">
 <?php echo $breadcrumb; ?>
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
  "exhibitPage" => $exhibitPage,
  "item_route" => $item_route,
  "options" => $options,
  "page_slug_1" => $params->page_slug_1,
  "page_slug_2" => $params->page_slug_2,
  "page_slug_3" => $params->page_slug_3,
  "page_slugs" => $params->page_slugs,
  "slug" => $params->slug,
  "text" => $block->text,
  "params" => $params,
  "refUri" => $refUri,
]);
?>
<?php endforeach; ?>
</div><!-- end .flex-blocks-wrapper -->
<?php echo foot(); ?>
