</div><!-- break from grid for gallery layout -->

<?php
$files = [];
$captions = [];
foreach ($attachments as $key => $row) {
  $files[] = get_record_by_id("File", $row->file_id);
  $captions[] = $row->caption;
}
?>

<!---- Begin gallery layout -->
<div id="block-<?php echo $block->id; ?>" class="ems-gallery">
  <div  class="ems-gallery-inner-wrapper bg-grey-300 r">
    <div class="ems-gallery-inner d-flex align-items-center">
      <?php foreach ($files as $key => $file): ?>
      <div class="ems-gallery-item-wrapper<?php if (
        $key == 0
      ): ?> active<?php endif; ?>">
        <div class="ems-gallery-item">
          <div class="ems-image-wrapper">
            <?php echo file_image("thumbnail", ["class" => "h-100"], $file); ?>
            </div>
            <div class="ems-gallery-item-caption">
               <?php echo $captions[$key]; ?>
          </div><!-- end .gallery-item-caption -->
          <a class="view-file" href="<?php echo url(
            [
              "action" => "show",
              "controller" => "item",
              "slug" => $exhibit->slug,
              "item_id" => $file->item_id,
              "file_id" => $file->id,
            ],
            "ems_show_file"
          ); ?>"><span class="visually-hidden"><?php echo __(
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

<div id="block-<?php echo $block->id; ?>-thumbnails" class="ems-gallery-thumbnails d-flex flex-wrap">
<?php foreach ($files as $key => $file): ?>
  <a class="<?php if (
    $key == 0
  ): ?>active<?php endif; ?>" href="#" onclick="javascript:void(0);"> <?php echo file_image(
  "square_thumbnail",
  ["class" => "h-100"],
  $file
); ?></a>
<?php endforeach; ?>
</div><!-- end ems-gallery-thumbnails -->
</div><!-- end .ems-gallery -->
<div class="d-flex flex-row">
  <div class="g-0 pt-3">
  <?php echo $block->text; ?>
  </div>
</div>
<!-- resume regular handling -->
<div class="flex-blocks-wrapper d-flex flex-wrap g-0">

<!-- Wrap the rest of the page in another container to center all the content. -->


