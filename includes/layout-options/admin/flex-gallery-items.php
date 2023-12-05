<?php
/**
 * gallery-item-height.php
 * This include is meant for inclusion in views/shared/exhibit_layouts/[FORMNAME]
 * for setting text and background colors for ExhibitMicrosite Plugin exhibit blocks.
 */
$options["gallery_item_height"] = isset($options["gallery_item_height"])
  ? $options["gallery_item_height"]
  : ""; ?>
<div class="ems-gallery-item-height-wrapper" data-formstem="<?php echo $formStem; ?>" data-block="<?php echo $block->id; ?>">


    <div
        class="ems-gallery-item-height"
        data-formstem="<?php echo $formStem; ?>"
      >
      <?php echo $this->formLabel(
        $formStem . "[options][gallery_item_height]",
        __(
          "Set a height in pixels for the images in the gallery. This will override the stylesheet default."
        )
      ); ?>
      <?php echo $this->formtext(
        $formStem . "[options][gallery_item_height]",
        @$options["gallery_item_height"]
          ? @$options["gallery_item_height"]
          : "",
        []
      ); ?>
  </div>
</div>
<!-- end .ems-gallery-item-height-wrapper -->
