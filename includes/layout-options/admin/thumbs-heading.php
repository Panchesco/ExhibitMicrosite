<?php
/**
 * thumbs-heading.php
 * This include is meant for inclusion in views/shared/exhibit_layouts/[FORMNAME]
 * for setting text and background colors for ExhibitMicrosite Plugin exhibit blocks.
 */
$options["thumbs_heading"] = isset($options["thumbs_heading"])
  ? $options["thumbs_heading"]
  : ""; ?>
<div>
 <div class="ems-thumbs-heading-wrapper" data-formstem="<?php echo $formStem; ?>" data-block="<?php echo $block->id; ?>">
     <?php echo $this->formLabel(
       $formStem . "[options][thumbs_heading]",
       __(
         "Enter the heading, if any to use above the thumbnails for this gallery or item with multiple files."
       )
     ); ?>
     <?php echo $this->formtext(
       $formStem . "[options][thumbs_heading]",
       @$options["thumbs_heading"] ? @$options["thumbs_heading"] : "",
       []
     ); ?>
 </div>
</div>
<!-- end .ems-thumbs-heading -->
