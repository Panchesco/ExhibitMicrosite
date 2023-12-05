</div><!-- break from grid for gallery layout -->
<?php
echo __FILE__ . "<p>refUri: " . $refUri . "</p>";
$files = [];
$captions = [];
foreach ($attachments as $key => $row) {
  $files[] = get_record_by_id("File", $row->file_id);
  $captions[] = $row->caption;
}

include EXHIBIT_MICROSITE_PLUGIN_DIR .
  "/includes/layout-options/public/option-defaults.php";
?>
<!---- Begin gallery layout -->
<div id="block-<?php echo $block->id; ?>" class="ems-gallery">
  <div  class="ems-gallery-inner-wrapper"<?php echo $inline_styles; ?>>
    <div class="ems-gallery-inner d-flex align-items-center">
      <?php foreach ($files as $key => $file): ?>
      <div class="ems-gallery-item-wrapper<?php if (
        $key == 0
      ): ?> active<?php endif; ?>">
        <div class="ems-gallery-item">
          <div class="ems-image-wrapper"<?php echo $gallery_item_inline_styles; ?>>
            <?php echo file_image("thumbnail", ["class" => "h-100"], $file); ?>
            </div>
            <div class="ems-gallery-item-caption">
               <?php echo $captions[$key]; ?>
          </div><!-- end .gallery-item-caption -->
          <a class="view-file" href="<?php echo $refUri .
            "/item/" .
            $file->item_id .
            "/" .
            $file->id; ?>"><span class="visually-hidden"><?php echo __(
  "View File"
); ?></span></a>
        </div><!-- end ems-gallery-item -->
      </div><!-- end ems-gallery-item-wrapper -->
      <?php endforeach; ?>
    </div><!-- end ems-gallery-inner -->
  <button class="ems-gallery-prev" type="button">
    <span class="ems-gallery-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="ems-gallery-next" type="button">
    <span class="ems-gallery-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div><!-- end bg-grey-300 -->

<div class="ems-gallery-thumbnails"<?php echo $thumbnails_background_inline; ?>>
<h2><?php echo __("Gallery Items"); ?></h2>
<div id="block-<?php echo $block->id; ?>-thumbnails" class="ems-gallery-thumbnails d-flex flex-wrap"<?php echo $thumbnails_background_inline; ?>>
<?php foreach ($files as $key => $file): ?>
  <a class="<?php if ($key == 0): ?>active<?php endif; ?>" href="<?php echo url(
  [
    "action" => "show",
    "controller" => "item",
    "slug" => $exhibit->slug,
    "item_id" => $file->item_id,
    "file_id" => $file->id,
  ],
  "ems_show_file"
); ?>" onclick="javascript:void(0);"> <?php echo file_image(
  "square_thumbnail",
  ["class" => "h-100"],
  $file
); ?></a>
<?php endforeach; ?>
</div><!-- end ems-gallery-thumbnails -->
</div><!-- end .ems-gallery -->
<?php if (!empty(trim($block->text))): ?>
<div class="d-flex flex-row <?php echo $text_div_class; ?>"<?php echo $flex_file_text_inline_styles; ?>>
  <div class="g-0<?php echo $item_flex_file_text_values; ?>">
  <?php echo $block->text; ?>
  </div>
</div>
<?php endif; ?>
<!-- resume regular handling -->
<div class="flex-blocks-wrapper d-flex flex-wrap g-0">

<!-- Wrap the rest of the page in another container to center all the content. -->


