<?php
/**
 * thumbnails.inc
 * This include is meant for inclusion in views/shared/exhibit_layouts/[FORMNAME]
 * for setting gallery options for Waterworks Plugin exhibit blocks for display
 * in the Water theme.
 * @param string $formStem this is the ExhibitBuilder block formstem
 * @param array $options options saved with the block in an exhibit page record.
 */
?>
<div class="gallery-file-size">
  <?php echo $this->formLabel( $formStem . "[options][gallery-file-size]",
  __("Gallery file size") ); ?>
  <?php
    $defaultFileSize = "fullsize";
    echo $this->formSelect( $formStem . "[options][gallery-file-size]",
  @$options["gallery-file-size"] ? @$options["gallery-file-size"] :
  $defaultFileSize, [], [ "square_thumbnail" => __("Square Thumbnail"),
  "thumbnail" => __("Thumbnail"),"fullsize" => __("Fullsize (recommended)") ] );
  ?>
</div>
<div class="thumbnail_count">
  <?php echo $this->formLabel( $formStem . "[options][thumbnail_count]",
  __("Maximum number of thumbnails to show in this block?") ); ?>
  <?php // Omeka version at time of plugin dev, does not support formNumber, so formSelect it is...
echo $this->formSelect( $formStem . "[options][thumbnail_count]",
  @$options["thumbnail_count"], "1", array_combine(range(1,12,1),range(1,12,1)))
  ; ?>
</div>
<div class="thumbnail_arrange">
  <?php echo $this->formLabel( $formStem . "[options][thumbnail_arrange]",
  __("Arrange Thumbnails") ); ?>
  <?php // Omeka version at time of plugin dev, does not support formNumber, so formSelect it is...
echo $this->formSelect( $formStem . "[options][thumbnail_Arrange]",
  @$options["thumbnail_arrange"], "horizontal", ['horizontal' =>
  __("Side-by-side"),"vertical" => __("Stacked")] ) ; ?>
</div>
