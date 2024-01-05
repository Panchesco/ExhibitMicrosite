<?php
/**
 * align-self.inc
 * This include is meant for inclusion in views/shared/exhibit_layouts/[FORMNAME]
 * for setting Bootstrap flex grid column align-self option.
 * @param string $formStem this is the ExhibitBuilder block formstem
 * @param array $options options saved with the block in an exhibit page record.
 */
 $options['align_self'] = (isset($options['align_self'])) ? $options['align_self'] : "align-self-stretch";
?>
<div class="align-self">
  <?php echo $this->formLabel( $formStem . "[options][align_self]", __("How
  should this block align vertically in a row of blocks?") ); ?>
  <div>
    <?php // Omeka version at time of plugin dev, does not support formNumber, so formSelect it is...
echo $this->formSelect( $formStem . "[options][align_self]",
    @$options["align_self"],"align-self-stretch",["" => __("Do not set"),
    "align-self-start" => "Top","align-self-center" => "Center",
    "align-self-end" => "Bottom","align-self-stretch" => "Stretch to fill"] );
    ?>
  </div>
</div>
