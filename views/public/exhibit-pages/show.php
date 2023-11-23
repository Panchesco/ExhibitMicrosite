<?php echo __FILE__;
echo head();

$request = Zend_Controller_Front::getInstance()->getRequest();

$slug = $request->getParam("slug");
$page_slug_1 = $request->getParam("page_slug_1");
$page_slug_2 = $request->getParam("page_slug_2");
$page_slug_3 = $request->getParam("page_slug_3");
$page_slugs = [];

if ($page_slug_1) {
  $page_slugs[] = $page_slug_1;
}
if ($page_slug_2) {
  $page_slugs[] = $page_slug_2;
}

if ($page_slug_3) {
  $page_slugs[] = $page_slug_3;
}
?>
<?php if (!isset($exhibit)) {
  $exhibit = get_record_by_id("Exhibit", $exhibitPage->exhibit_id);
} ?>
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
  "block" => $block,
  "options" => $options,
  "attachments" => $attachments,
  "text" => $block->text,
  "exhibit" => $exhibit,
  "exhibitPage" => $exhibitPage,
  "request" => $request,
  "slug" => $slug,
  "page_slug_1" => $page_slug_1,
  "page_slug_2" => $page_slug_2,
  "page_slug_3" => $page_slug_3,
  "page_slugs" => $page_slugs,
]);
?>
<?php endforeach; ?>
</div>
<?php echo foot(); ?>
