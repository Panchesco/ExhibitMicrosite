<?php
/**
 * flex-file-text-margin.inc
 * This include is meant for inclusion in views/shared/exhibit_layouts/[FORMNAME]
 * for setting Bootstrap flex grid column options.
 * @param string $formStem this is the ExhibitBuilder block formstem
 * @param array $options options saved with the block in an exhibit page record.
 */
$options["flex_file_text_margin"] = isset($options["flex_file_text_margin"])
  ? $options["flex_file_text_margin"]
  : ""; ?>

<div class="flex_file_text_margin">
  <?php echo $this->formLabel(
    $formStem . "[options][flex_file_text_margin]",
    __("Set a margin between the item/s and the text?")
  ); ?>
  <?php echo $this->formSelect(
    $formStem . "[options][flex_file_text_margin]",
    @$options["flex_file_text_margin"]
      ? @$options["flex_file_text_margin"]
      : "inherit-margin",
    "inherit-margin",
    [
      "inherit-margin" => __("Do not set"),
      "mb-1" => __(".25 rem"),
      "mb-2" => __(".50 rem"),
      "mb-3" => __("1 rem"),
      "mb-4" => __("1.5 rem"),
      "mb-5" => __("3 rem"),
      "auto" => "Auto",
    ]
  ); ?>
</div>
