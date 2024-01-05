<?php
/**
 * col-span.inc
 * This include is meant for inclusion in views/shared/exhibit_layouts/[FORMNAME]
 * for setting Bootstrap flex grid column options.
 * @param string $formStem this is the ExhibitBuilder block formstem
 * @param array $options options saved with the block in an exhibit page record.
 */

$options["col_span"] = isset($options["col_span"])
  ? $options["col_span"]
  : "6"; ?>
<div class="col-span">
  <?php echo $this->formLabel(
    $formStem . "[options][col_span]",
    __("How many
  columns should the this block span? The minimum is 1, The maximum is 12.")
  ); ?>
  <?php // Omeka version at time of plugin dev, does not support formNumber, so formSelect it is...

echo $this->formSelect(
    $formStem . "[options][col_span]",
    @$options["col_span"],
    6,
    [
      0 => __("Do not
  set"),
      1 => 1,
      2 => 2,
      3 => 3,
      4 => 4,
      5 => 5,
      6 => 6,
      7 => 7,
      8 => 8,
      9 => 9,
      10 => 10,
      11 => 11,
      12 => 12,
    ]
  ); ?>
</div>
