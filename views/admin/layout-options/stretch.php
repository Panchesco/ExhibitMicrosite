<?php
/**
 * stretch.inc
 * This include is meant for inclusion in views/shared/exhibit_layouts/[FORMNAME]
 * for setting Bootstrap flex grid column options.
 * @param string $formStem this is the ExhibitBuilder block formstem
 * @param array $options options saved with the block in an exhibit page record.
 */
?>
<div class="stretch">
  <?php echo $this->formLabel( $formStem . "[options][col_stretch]", __("Stretch
  the column to match the heights of other blocks in the same row?") ); ?>
  <?php // Omeka version at time of plugin dev, does not support formNumber, so formSelect it is...
echo $this->formSelect( $formStem . "[options][col_stretch]",
  @$options["col_stretch"],"align-self-stretch",["align-self-stretch" =>
  "Yes","align-self-start" => "No"] ); ?>
</div>
