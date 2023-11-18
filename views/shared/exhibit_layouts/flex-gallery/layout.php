<?php
$files = [];
$first = SCWaterBlockFirstFile($attachments, $block_id);
$class = $block->layout;
$attachments = $block->getAttachments();
$items = [];
$files = [];
$gallery = [];
$captions = [];
$file_urls = [];
$item_links = [];

// We need information for the gallery items and thumbnails.
foreach ($attachments as $key => $row) {
  $items[] = get_record_by_id("Item", $row->item_id);
  $item_links[] = "/items/show/" . $row->item_id;
  $files[] = get_record_by_id("File", $row->file_id);
  $captions[] = $row->caption;
  $file_urls[] = file_display_url($files[$key], "fullsize");
  $gallery[] = [
    "square_thumbnail" => item_image("square_thumbnail", [], 0, $items[$key]),
    "thumbnail" => item_image("thumbnail", [], 0, $items[$key]),
    "fullsize" => item_image("fullsize", [], 0, $items[$key]),
  ];
}

$gallery_container_background_color = isset(
  $options["gallery_container_background_color"]
)
  ? "bg-" . str_replace("#", "", $options["gallery_container_background_color"])
  : "bg-transparent";

$gallery_file_size = isset($options["gallery_file_size"])
  ? $options["gallery_file_size"]
  : "fullsize";
?>
<div class="container">
<div class="<?php echo $class; ?>">
<?php if ($options["gallery_layout"] == "full_width"): ?>
  <div class="col-12">
    <div id="gallery-<?php echo $block->id; ?>" class="gallery">
    <div class="stage">
      <div class="items-wrapper">
      <?php foreach ($gallery as $key => $row): ?>
        <div class="gallery-item">
          <a href="<?php echo $item_links[$key]; ?>"><?php echo $row[
  "thumbnail"
]; ?></a>
 </div><!-- . end .gallery-item -->
      <?php endforeach;
  // end attachments loop
  ?>


  </div><!-- end .items  -->

          <div class="prev">
            <a href="#prev"
              ><span class="material-symbols-outlined">
                chevron_left
              </span></a
            >
          </div>
          <div class="next">
            <a href="#next"
              ><span class="material-symbols-outlined"
                >chevron_right</span
              ></a
            >
          </div>

        <!-- end .prev-next -->
        <!-- end .items-wrapper -->
      </div><!-- end .items-wrapper -->
    </div><!-- end .stage -->
    <div class="captions-container">
      <div class="captions-wrapper">
          <?php foreach ($attachments as $attachment): ?>
            <div class="caption-wrapper">
            <div class="caption">
            <?php echo $attachment->caption; ?>
            </div><!-- end .caption-wrapper -->
            </div><!-- end .caption -->
          <?php endforeach; ?>
      </div>
      <!-- end .captions-wrapper -->
    </div>
    <!-- end .captions-container -->
      <div class="thumbs-wrapper">
<?php foreach ($gallery as $key => $row): ?>
        <a class="thumb" href="<?php echo $file_urls[$key]; ?>"><?php echo $row[
  "square_thumbnail"
]; ?></a>
<?php endforeach; ?>
    </div><!-- end .thumbs-wrapper -->
    </div><!-- end .gallery -->
  </div><!-- end .col-12 -->
  <?php if (!empty($text)): ?><div class="col-12 py-4">
    <?php echo $text; ?>
  </div><?php endif; ?>
</div><!-- end .container -->
<?php endif;
// end gallery_layout branch
?>
<!-- end gallery layout -->
