<?php
/**
 * padding.inc
 * This include is meant for inclusion in views/shared/exhibit_layouts/[FORMNAME]
 * for setting Bootstrap flex grid column options.
 * @param string $formStem this is the ExhibitBuilder block formstem
 * @param array $options options saved with the block in an exhibit page record.
 */
$options["flex_file_text_block_padding_x"] = isset(
  $options["flex_file_text_block_padding_x"]
)
  ? $options["flex_file_text_block_padding_x"]
  : "";
$options["flex_file_text_block_padding_y"] = isset(
  $options["flex_file_text_block_padding_y"]
)
  ? $options["flex_file_text_block_padding_y"]
  : "";
?>

<div class="flex_file_text_block_padding_x">
  <?php echo $this->formLabel(
    $formStem . "[options][flex_file_text_block_padding_x]",
    __("Block Text Area Padding Sides")
  ); ?>
  <?php echo $this->formSelect(
    $formStem . "[options][flex_file_text_block_padding_x]",
    @$options["flex_file_text_block_padding_x"]
      ? @$options["flex_file_text_block_padding_x"]
      : "px-4",
    [],
    [
      "inherit-padding" => __("Do not set"),
      "px-1" => __(".25"),
      "px-2" => __(".50"),
      "px-3" => __("1"),
      "px-4" => __("1.5"),
      "px-5" => __("3"),
      "auto" => "Auto",
    ]
  ); ?>
</div>

<div class="flex_file_text_block_padding_y">
  <?php echo $this->formLabel(
    $formStem . "[options][flex_file_text_block_padding_y]",
    __("Block Text Area Padding Top and Bottom")
  ); ?>
  <?php echo $this->formSelect(
    $formStem . "[options][flex_file_text_block_padding_y]",
    @$options["flex_file_text_block_padding_y"]
      ? @$options["flex_file_text_block_padding_y"]
      : "py-4",
    [],
    [
      "inherit-padding" => __("Do not set"),
      "py-1" => __(".25"),
      "py-2" => __(".50"),
      "py-3" => __("1"),
      "py-4" => __("1.5"),
      "py-5" => __("3"),
      "auto" => "Auto",
    ]
  ); ?>
</div>
