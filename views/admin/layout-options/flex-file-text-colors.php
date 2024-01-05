<?php
/**
 * flex-file-text-colors.php
 * This include is meant for inclusion in views/shared/exhibit_layouts/[FORMNAME]
 * for setting text and background colors for Waterworks Plugin exhibit blocks for display
 * in the Water theme.
 */
$options["flexFileTextBackgroundColor"] = isset(
  $options["flexFileTextBackgroundColor"]
)
  ? $options["flexFileTextBackgroundColor"]
  : "inherit";
$options["flexFileTextColor"] = isset($options["flexFileTextColor"])
  ? $options["flexFileTextColor"]
  : "inherit";

// Color picker fields need a valid hex value.
if ($options["flexFileTextBackgroundColor"] == "inherit") {
  $options["flexFileTextBackgroundColor_picker"] = "#ffffff";
} else {
  $options["flexFileTextBackgroundColor_picker"] = isset(
    $options["flexFileTextBackgroundColor_picker"]
  )
    ? $options["flexFileTextBackgroundColor_picker"]
    : "#ffffff";
}

if ($options["flexFileTextColor"] == "inherit") {
  $options["flexFileTextColor_picker"] = "#222222";
} else {
  $options["flexFileTextColor_picker"] = isset(
    $options["flexFileTextColor_picker"]
  )
    ? $options["flexFileTextColor_picker"]
    : "#222222";
}
?>
<div class="palettes-preview-wrapper" data-formstem="<?php echo $formStem; ?>" data-block="<?php echo $block->id; ?>">
  <div class="palettes-col">
    <div
        class="palette"
        data-formstem="<?php echo $formStem; ?>"
        data-property="color"
      >
      <?php echo $this->formLabel(
        $formStem . "[options][flexFileTextColor]",
        __("Set the color of the text shown in the block's text area.")
      ); ?>
      <div class="tiles"></div>
      <?php echo $this->formInput(
        $formStem . "[options][flexFileTextColor_picker]",
        $options["flexFileTextColor_picker"],
        ["type" => "color", "data-prop" => "color"]
      ); ?>
      <?php echo $this->formInput(
        $formStem . "[options][flexFileTextColor]",
        $options["flexFileTextColor"],
        ["type" => "hidden", "class" => "color-choice"]
      ); ?>
      <label for="no-inline-color_<?php echo $block->id; ?>">
      <?php echo $this->formInput(
        $formStem . "[options][flexFileTextColor]",
        "inherit",
        [
          "type" => "checkbox",
          "class" => "no-inline no-inline-color",
        ]
      ); ?> <?php echo __("Do not set"); ?>
      </label>
      </div>
      <!-- end text color palette -->
      <div
        class="palette"
        data-formstem="<?php echo $formStem; ?>"
        data-property="flexFileTextBackgroundColor"
      >
      <?php echo $this->formLabel(
        $formStem . "[options][flexFileTextBackgroundColor]",
        __("Set the block's text area background color.")
      ); ?>
      <div class="tiles"></div>
      <?php echo $this->formInput(
        $formStem . "[options][flexFileTextBackgroundColor_picker]",
        $options["flexFileTextBackgroundColor_picker"],
        ["type" => "color", "data-prop" => "backgroundColor"]
      ); ?>
      <?php echo $this->formInput(
        $formStem . "[options][flexFileTextBackgroundColor]",
        $options["flexFileTextBackgroundColor"],
        ["type" => "hidden", "class" => "color-choice"]
      ); ?>
      <label for="no-inline-flexFileTextBackgroundColor_<?php echo $block->id; ?>">
      <?php echo $this->formInput(
        $formStem . "[options][flexFileTextBackgroundColor]",
        "inherit",
        [
          "type" => "checkbox",
          "class" => "no-inline no-inline-flexFileTextBackgroundColor",
          "id" => "no-inline-flexFileTextBackgroundColor_" . $block->id,
        ]
      ); ?> <?php echo __("Do not set"); ?>
      </label>
      </div>
        <!-- end background color palette -->
    </div>
    <!-- end .palettes-col -->
    <div class="preview-wrapper">
      <label for="preview-<?php echo $formStem; ?>"
        ><?php echo __("Preview Selected Colors"); ?></label
      >
      <div class="preview" id="preview_<?php echo $block->id; ?>"
      style="background-color:<?php echo $options[
        "flexFileTextBackgroundColor"
      ]; ?>;color:<?php echo $options[
  "flexFileTextColor"
]; ?>">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut pretium eget elit quis varius. Curabitur posuere varius luctus. Duis lobortis hendrerit vehicula. Duis dapibus fermentum mauris, eget tempor velit interdum nec. Morbi fermentum, ante vitae semper eleifend, neque nisl placerat turpis, in finibus enim dolor ut risus. Mauris feugiat lectus at auctor bibendum. Donec porttitor pretium tortor ac lobortis. Phasellus cursus leo at ultricies rutrum.
<br><br>Fusce elementum, mauris at iaculis finibus, enim est sagittis mi, gravida dapibus sapien nibh eget est. Proin sagittis est at efficitur mollis. Duis laoreet tempus neque, quis efficitur neque. Nulla volutpat, elit vitae pharetra venenatis, ipsum nibh mollis dui, at lacinia tellus nisi eget ipsum. Integer non cursus diam. Nam aliquet ornare neque. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Duis suscipit sem eget urna accumsan pellentesque. Maecenas pharetra dolor sit amet diam porttitor dapibus.
    </div>
  </div>
</div>
<!-- end .palettes-preview-wrapper -->
