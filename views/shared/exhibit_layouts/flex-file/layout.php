<?php
/**
 * The following vars are available:
 * $options
 * $text
 * $block
 * $block_id
 * $exhibit_id
 * $exhibit_title
 * $exhibit_slug
 * $exhibit_page_title
 * $exhibit_page_short_title
 * $exhibit_page_slug
 * $attachments
 */

$files = [];
$captions = [];
$file_images = [];
$item_urls = [];
$plus = [];
$block_items = [];


// Get the page attachments for the current block;
$index = 1;
foreach ($attachments as $key => $attachment) {
  if ($attachment->block_id == $block_id) {
    $block_items[$key]["item"] = $attachment->Item;
    $block_items[$key]["files"] = $attachment->Item->Files;
    $block_items[$key]["caption"] = $attachment->caption;
    $block_items[$key]["item_url"] = current_url() . "/items/{$attachment->Item->id}";
    $block_items[$key]["plus"] =
      count($block_items[$key]["files"]) > 1
        ? count($block_items[$key]["files"]) - 1
        : 0;
    $block_items[$key]["count"] = $index;
    $index++;
  }

}

$total_rows = count($block_items);

// Options for wrapper class.
if (isset($options["align_self"])) {
  $align_self = $options["align_self"];
} else {
  $align_self = "align-self-stretch";
}

// Flex basis for wrapper class.
if (isset($options["flex_direction"])) {
  $flex_direction = $options["flex_direction"];
} else {
  $flex_direction = "flex-column";
}

$class = $block->layout;
$class .= " water-gap col-12 col-xl-" . $options["col_span"];
$class .= " " . $align_self;

// Options for wrapper inline styles.
if (isset($options["background_color"])) {
  $bg = "background-color:" . $options["background_color"] . ";";
} else {
  $bg = "";
}

if (isset($options["color"])) {
  $color = "color:" . $options["color"] . ";";
} else {
  $color = "";
}

$style = ' style="' . $bg . $color . '"';

// Bootstrap class declarations for text div.
if (isset($options["block_padding_x"])) {
  $padding_x = $options["block_padding_x"];
} else {
  $padding_x = "px-4";
}

if (isset($options["block_padding_y"])) {
  $padding_y = $options["block_padding_y"];
} else {
  $padding_y = "px-4";
}

$text_div_class = "text {$padding_x} {$padding_y}";
?>

<div class="<?php echo $class; ?>  d-flex <?php echo $flex_direction; ?> align-self-start gap-3">
<?php foreach ($block_items as $key => $item):
  if (isset($item["files"][0])): ?>
       <div class="item-wrapper <?php echo $align_self .
         " " .
         $padding_x .
         " " .
         $padding_y; ?>"  <?php echo $style; ?>>
       <figure>
          <div class="image-wrapper">
            <?php echo item_image(
              "thumbnail",
              ["class" => "lazy"],
              0,
              $item["item"]
            ); ?>
            <?php if (
              $item["plus"] !== 0
            ): ?><div class="plus">+<?php echo $item[
  "plus"
]; ?></div><?php endif; ?>
            <a class="item-link" href="<?php echo $item[
              "item_url"
            ]; ?>"><span class="visually-hidden">
               <?php echo __("View this item"); ?>
             </span></a>
          </div><!-- end .image-wrapper -->
           <?php if (!empty($item["caption"])): ?><figcaption>
             <?php echo $item["caption"]; ?>
           </figcaption><?php endif; ?>
       </figure><!-- end  -->
       </div><!-- end .item-wrapper -->
 <?php endif;
endforeach; ?>
 </div><!-- end .water-gap -->
