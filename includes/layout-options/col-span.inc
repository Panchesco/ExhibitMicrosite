<?php
/**
 * col-span.inc
 * This include is meant for inclusion in views/shared/exhibit_layouts/[FORMNAME]
 * for setting Bootstrap flex grid column options.
 * @param string $formStem this is the ExhibitBuilder block formstem
 * @param array $options options saved with the block in an exhibit page record.
 */
 
$options["col_span"] = (isset($options["col_span"])) ? $options["col_span"] : '6';

?>
<div class="col-span">
  <?php echo $this->formLabel( $formStem . "[options][col_span]", __("How many
  columns should the theme block span? Minimum is 1, maximum is 12)
  Default is 6.") ); ?>
  <?php // Omeka version at time of plugin dev, does not support formNumber, so formSelect it is...
echo $this->formSelect( $formStem . "[options][col_span]",
  @$options["col_span"], 6, array_combine(range(1,12,1),range(1,12,1)) ); ?>
</div>
