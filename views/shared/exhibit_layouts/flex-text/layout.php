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
 */
// Options for wrapper class.
if(isset($options["align_self"])) {
  $align_self = $options['align_self'];
} else {
  $align_self =  "align-self-stretch";
}

// Flex basis for wrapper class.
if(isset($options["flex_direction"])) {
  $flex_direction = $options['flex_direction'];
} else {
  $flex_direction =  "flex-column";
}

$class = $block->layout;
$class.= " water-gap col-12 col-xl-" .  $options["col_span"];
$class.= " " . $align_self;

// Options for wrapper inline styles.
if(isset($options["background_color"])) {
  $bg =  "background-color:" . $options["background_color"] . ";";
} else {
  $bg =  "";
}

if(isset($options["color"])) {
  $color =  "color:" . $options["color"] . ";";
} else {
  $color =  "";
}

$style = ' style="' . $bg . $color . '"';

// Bootstrap class declarations for text div.
if(isset($options["block_padding_x"])) {
  $padding_x = $options["block_padding_x"];
} else {
  $padding_x = "px-4";
}

if(isset($options["block_padding_y"])) {
  $padding_y = $options["block_padding_y"];
} else {
  $padding_y = "px-4";
}

$text_div_class = "text {$padding_x} {$padding_y}";


?>
<div class="<?php echo $class; ?>" <?php echo $style; ?>>
       <div class="d-flex <?php echo $flex_direction;?> justify-content-start align-self-stretch">
          <div class="<?php echo $text_div_class;?>">
          <?php echo $text; ?>
          </div>
       </div><!-- end .text -->
</div><!-- end .water-gap -->
