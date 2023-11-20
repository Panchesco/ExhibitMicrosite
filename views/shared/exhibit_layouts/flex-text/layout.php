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

// Set default colors and determine if there are inline color styles.
$inlines = [];
if (!isset($options["backgroundColor"])) {
  $options["backgroundColor"] = "inherit";
} else {
  if ($options["backgroundColor"] !== "inherit") {
    $inlines["background-color"] = $options["backgroundColor"];
  }
}

if (!isset($options["color"])) {
  $options["color"] = "inherit";
} else {
  if ($options["color"] !== "inherit") {
    $inlines["color"] = $options["color"];
  }
}

$inlineStyles = inlineStylesString($inlines);

if (isset($options["align_self"]) && !empty($options["align_self"])) {
  $align_self = $options["align_self"];
} else {
  $align_self = "";
}

// Flex basis for wrapper class.
if (isset($options["flex_direction"])) {
  $flex_direction = $options["flex_direction"];
} else {
  $flex_direction = "flex-column";
}

$class = $block->layout;
$class .= " col-12 col-xl-" . $options["col_span"];
$class .= " " . $align_self;

// Bootstrap class declarations for text div.
if (isset($options["block_padding_x"]) && !empty($options["block_padding_x"])) {
  $padding_x = " " . $options["block_padding_x"];
} else {
  $padding_x = "";
}

if (isset($options["block_padding_y"]) && !empty($options["block_padding_y"])) {
  $padding_y = " " . $options["block_padding_y"];
} else {
  $padding_y = "";
}

$text_div_class = "text{$padding_x}{$padding_y}";
?>
<div class="<?php echo $class; ?>"<?php echo $inlineStyles; ?>>
       <div class="d-flex <?php echo $flex_direction; ?> justify-content-start">
          <div class="<?php echo $text_div_class; ?>">
          <?php echo $text; ?>
          </div>
       </div><!-- end .text -->
</div><!-- end .water-gap -->
