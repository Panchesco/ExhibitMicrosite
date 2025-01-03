<?php
/**
 * palettes.inc
 * This include is meant for inclusion in views/shared/exhibit_layouts/[FORMNAME]
 * for setting text and background colors for Waterworks Plugin exhibit blocks for display
 * in the Water theme.
 */
 $options['backgroundColor'] = (isset($options['backgroundColor'])) ? $options['backgroundColor'] : "inherit";
 $options['color'] = (isset($options['color'])) ? $options['color'] : "inherit";


 // Color picker fields need a valid hex value.
 if($options["backgroundColor"] == "inherit") {
   $options["backgroundColor_picker"] = "#ffffff";
 } else {
   $options["backgroundColor_picker"] = ( isset($options["backgroundColor_picker"]))  ? $options["backgroundColor_picker"] : "#ffffff";
 }

  if($options["color"] == "inherit") {
    $options["color_picker"] = "#222222";
  } else {
    $options["color_picker"] = ( isset($options["color_picker"]))  ? $options["color_picker"] : "#222222";
  }

?>
<div class="palettes-preview-wrapper" data-formstem="<?php echo $formStem;?>" data-block="<?php echo $block->id;?>">
  <div class="palettes-col">

    <div
        class="palette"
        data-formstem="<?php echo $formStem; ?>"
        data-property="color"
      >
      <?php echo $this->formLabel($formStem . "[options][color]",__("Set block text color."));?>
      <div class="tiles"></div>
      <?php echo $this->formInput($formStem . "[options][color_picker]", $options["color"],["type" => "color","data-prop"=>"color"]);?>
      <?php echo $this->formInput($formStem . "[options][color]",$options["color"], ["type" => "hidden","class"=>"color-choice"]);?>
      <label for="no-inline-color_<?php echo $block->id;?>">
      <?php echo $this->formInput($formStem . "[options][color]","inherit", ["type" => "checkbox","class"=>"no-inline no-inline-color"]);?> <?php echo __("Do not set");?>
      </label>
      </div>
      <!-- end text color palette -->
      <div
        class="palette"
        data-formstem="<?php echo $formStem; ?>"
        data-property="backgroundColor"
      >
      <?php echo $this->formLabel($formStem . "[options][backgroundColor]",__("Set block background color."));?>
      <div class="tiles"></div>
      <?php echo $this->formInput($formStem . "[options][backgroundColor_picker]",$options["backgroundColor"], ["type" => "color","data-prop"=>"backgroundColor"]);?>
      <?php echo $this->formInput($formStem . "[options][backgroundColor]",$options["backgroundColor"], ["type" => "hidden","class"=>"color-choice"]);?>
      <label for="no-inline-backgroundColor_<?php echo $block->id;?>">
      <?php echo $this->formInput($formStem . "[options][backgroundColor]","inherit", ["type" => "checkbox","class"=>"no-inline no-inline-backgroundColor","id" => "no-inline-backgroundColor_".$block->id]);?> <?php echo __("Do not set");?>
      </label>
      </div>
        <!-- end background color palette -->
    </div>
    <!-- end .palettes-col -->
    <div class="preview-wrapper">
      <label for="preview-<?php echo $formStem; ?>"
        ><?php echo __(
  "Preview Selected Colors"
); ?></label
      >
      <div class="preview" id="preview_<?php echo $block->id;?>"
      style="background-color:<?php echo $options[
  "backgroundColor"
]; ?>;color:<?php echo $options["color"]; ?>"> Lorem ipsum dolor sit amet,
      consectetur adipiscing elit. Proin malesuada varius massa. Donec vel
      pulvinar ex, eu tincidunt orci. Mauris dignissim sit amet magna vitae
      Lorem ipsum dolor sit amet,
      consectetur adipiscing elit.
    </div>
  </div>
</div>
<!-- end .palettes-preview-wrapper -->
