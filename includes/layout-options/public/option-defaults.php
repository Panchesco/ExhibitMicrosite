<?php

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

$flex_file_text_inlines = [];
if (!isset($options["flexFileTextBackgroundColor"])) {
  $options["flexFileTextBackgroundColor"] = "inherit";
} else {
  if ($options["flexFileTextBackgroundColor"] !== "inherit") {
    $flex_file_text_inlines["background-color"] =
      $options["flexFileTextBackgroundColor"];
  }
}

if (!isset($options["flexFileTextColor"])) {
  $options["flexFileTextColor"] = "inherit";
} else {
  if ($options["flexFileTextColor"] !== "inherit") {
    $flex_file_text_inlines["color"] = $options["flexFileTextColor"];
  }
}

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
$class .= " col-12 col-lg-" . $options["col_span"];
$class .= " " . $align_self; // Bootstrap class declarations for text div.

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
$inline_styles = inlineStylesString($inlines);
$flex_file_text_inline_styles = inlineStylesString($flex_file_text_inlines);

$block_flex_values = "";

$block_flex_values .=
  isset($options["flex_direction"]) && !empty($options["flex_direction"])
    ? " " . $options["flex_direction"]
    : "";

$block_flex_values .=
  isset($options["col_span"]) && !empty($options["col_span"])
    ? " col-lg-" . $options["col_span"]
    : "";

$block_flex_file_text_values = "";

$block_flex_file_text_values .= " flex-column";

$block_flex_file_text_values .=
  isset($options["col_span"]) && !empty($options["col_span"])
    ? " col-lg-" . $options["col_span"]
    : "";

$item_flex_values = "";
$item_flex_values .=
  isset($options["align_self"]) && !empty($options["align_self"])
    ? " " . $options["align_self"]
    : "";
$item_flex_values .=
  isset($options["block_padding_x"]) && !empty($options["block_padding_x"])
    ? " " . $options["block_padding_x"]
    : "";
$item_flex_values .=
  isset($options["block_padding_y"]) && !empty($options["block_padding_y"])
    ? " " . $options["block_padding_y"]
    : "";

$item_flex_values .=
  isset($options["flex_file_text_margin"]) &&
  !empty($options["flex_file_text_margin"])
    ? " " . $options["flex_file_text_margin"]
    : "";

$item_flex_file_text_values = "";
$item_flex_file_text_values .=
  isset($options["flex_file_text_block_padding_x"]) &&
  !empty($options["flex_file_text_block_padding_x"])
    ? " " . $options["flex_file_text_block_padding_x"]
    : "";
$item_flex_file_text_values .=
  isset($options["flex_file_text_block_padding_y"]) &&
  !empty($options["flex_file_text_block_padding_y"])
    ? " " . $options["flex_file_text_block_padding_y"]
    : "";

$item_flex_file_text_values = str_replace(
  ["  ", "inherit-padding"],
  "",
  $item_flex_file_text_values
);

$item_flex_file_text_values = trim($item_flex_file_text_values);
$item_flex_file_text_values = !empty($item_flex_file_text_values)
  ? " " . $item_flex_file_text_values
  : "";
