<?php
/**
 * padding.inc
 * This include is meant for inclusion in views/shared/exhibit_layouts/[FORMNAME]
 * for setting Bootstrap flex grid column options.
 * @param string $formStem this is the ExhibitBuilder block formstem
 * @param array $options options saved with the block in an exhibit page record.
 */
 $options['block_padding_x'] = (isset($options['block_padding_x'])) ? $options['block_padding_x'] : "";
 $options['block_padding_y'] = (isset($options['block_padding_y'])) ? $options['block_padding_y'] : "";
?>

<div class="block-padding-x">
  <?php echo $this->formLabel( $formStem . "[options][block_padding_x]",
  __("Block Padding Sides") ); ?>
  <?php
    echo $this->formSelect( $formStem . "[options][block_padding_x]",
  @$options["block_padding_x"] ? @$options["block_padding_x"] : "px-4", [], [
  "inherit-padding" => __("Do not set"), "px-1" => __(".25"),"px-2" =>
  __(".50"),"px-3" => __("1"),"px-4" => __("1.5"),"px-5" => __("3"),'auto' =>
  'Auto' ] ); ?>
</div>

<div class="block-padding-y">
  <?php echo $this->formLabel( $formStem . "[options][block_padding_y]",
  __("Block Padding Top and Bottom") ); ?>
  <?php
    echo $this->formSelect( $formStem . "[options][block_padding_y]",
  @$options["block_padding_y"] ? @$options["block_padding_y"] : "py-4", [], [
  "inherit-padding" => __("Do not set"), "py-1" => __(".25"),"py-2" =>
  __(".50"),"py-3" => __("1"),"py-4" => __("1.5"),"py-5" => __("3"),'auto' =>
  'Auto' ] ); ?>
</div>
