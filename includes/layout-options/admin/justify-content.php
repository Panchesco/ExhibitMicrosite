<?php
/**
 * justify-content.inc
 * This include is meant for inclusion in views/shared/exhibit_layouts/[FORMNAME]
 * for setting Bootstrap flex grid column jusfity-content option.
 * https://getbootstrap.com/docs/5.0/layout/columns/
 * @param string $formStem this is the ExhibitBuilder block formstem
 * @param array $options options saved with the block in an exhibit page record.
 */
$options["justify_content"] = isset($options["justify_content"])
  ? $options["justify_content"]
  : "justify-content-start"; ?>
<div class="'justify-content'">
  <?php echo $this->formLabel(
    $formStem . "[options][justify_content]",
    __("How
  should this block justify with relation to others in a row?")
  ); ?>
  <div>
    <?php // Omeka version at time of plugin dev, does not support formNumber, so formSelect it is...

echo $this->formSelect(
      $formStem . "[options][justify_content]",
      @$options["justify_content"],
      "justify-content-start",
      [
        "" => __("Do not
    set"),
        "justify-content-start" => "Start",
        "justify-content-center" => "Center",
        "justify-content-end" => "End",
        "justify-content-around" => "Space
    Around",
        "justify-content-between" => "Space Between",
        "justify-content-evenly" => "Space Evenly",
      ]
    ); ?>
  </div>
</div>
