<?php
/**
 * flex-direction.inc
 * This include is meant for inclusion in views/shared/exhibit_layouts/[FORMNAME]
 * for setting handling of blocks with multiple attachments. Should they be stacked or wrapped?
 * @param string $formStem this is the ExhibitBuilder block formstem
 * @param array $options options saved with the block in an exhibit page record.
 */
?>
<div class="flex-basis">
  <?php echo $this->formLabel(
    $formStem . "[options][flex_direction]",
    __(
      "If this block has multiple attachments, how would you like them displayed? Stacked one on top of another or wrapped to rows?"
    )
  ); ?>
  <div>
  <?php // Omeka version at time of plugin dev, does not support formNumber, so formSelect it is...

echo $this->formSelect(
    $formStem . "[options][flex_direction]",
    @$options["flex_direction"],
    "d-flex flex-row",
    ["d-flex flex-row" => "Wrapped", "d-flex flex-column" => "Stacked"]
  ); ?>
  </div>
</div>
