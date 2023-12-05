<?php echo __FILE__;
echo head();

/**
 *
 * NOTE: If a native Exhibit Builder default Gallery Block is rendering, and you're seeing a
 * Warning : Undefined array key “imgAttributes”, it's an Omeka Bug:
 * https://forum.omeka.org/t/gallery-block-error-message-undefined-array-key/16146
 *
 * Wrap for non-ems blocks:
 */
?>
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
]);
?>
<?php endforeach; ?>
</div><!-- end .flex-blocks-wrapper -->
<?php echo foot(); ?>
