<?php
/**
 * col-stretch.inc
 * This include is meant for inclusion in views/shared/exhibit_layouts/[FORMNAME]
 * for setting Bootstrap flex grid column options.
 * @param string $formStem this is the ExhibitBuilder block formstem
 * @param array $options options saved with the block in an exhibit page record.
 */
 

$options["col_stretch"] = (isset($options["col_stretch"])) ? $options["col_stretch"] : 'stretch';
?>

<div class="col-stretch">
  <?php echo $this->formLabel( $formStem . "[options][col_stretch]", __("Stretch
  block to fill height of row?") ); ?>
  <?php // Omeka version at time of plugin dev, does not support formNumber, so formSelect it is...
echo $this->formRadio( $formStem . "[options][col_stretch]",
  @$options["col_stretch"], "stretch", ["stretch" => __("Yes"),"no-stretch" =>
  __("No")]) ; ?>
</div>
