<?php
/**
 * single-thumbnail.inc
 * This include is meant for inclusion in views/shared/exhibit_layouts/[FORMNAME]
 * for setting gallery options for Waterworks Plugin exhibit blocks for display
 * in the Water theme.
 * @param string $formStem this is the ExhibitBuilder block formstem
 * @param array $options options saved with the block in an exhibit page record.
 */
?>
<div class="gallery-file-size">
  <?php echo $this->formLabel( $formStem . "[options][gallery-file-size]",
  __("Block Item Image Filesize") ); ?>
  <?php
    $defaultFileSize = "fullsize";
    echo $this->formSelect( $formStem . "[options][gallery-file-size]",
  @$options["gallery-file-size"] ? @$options["gallery-file-size"] :
  $defaultFileSize, [], [ "square_thumbnail" => __("Square Thumbnail"),
  "thumbnail" => __("Thumbnail"),"fullsize" => __("Fullsize (recommended)") ] );
  ?>
</div>
