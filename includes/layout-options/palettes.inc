<?php
/**
 * palettes.inc
 * This include is meant for inclusion in views/shared/exhibit_layouts/[FORMNAME]
 * for setting text and background colors for Waterworks Plugin exhibit blocks for display
 * in the Water theme.
 */
 $options['background_color'] = (isset($options['background_color'])) ? $options['background_color'] : "#ffffff";
 $options['color'] = (isset($options['color'])) ? $options['color'] : "#222222";

 $inlineBgNoneChecked = ( isset($options['inline_background_color']) && $options['inline_background_color'] == "inherit") ?  ' checked="checked"' : "";
 $inlineColorNoneChecked = ( isset($options['inline_color']) && $options['inline_color'] == "inherit") ?  ' checked="checked"' : "";

?>
<div class="palettes-preview-wrapper">
  <div class="palettes-col">
    <div
      class="palette"
      data-formstem="<?php echo $formStem; ?>"
      data-property="background_color"
    >
    <?php echo $this->formLabel($formStem . "[options][background_color]");?>
    <div class="tiles"></div>
    <?php echo $this->formInput("bg-palette-".$block->id,"#ffffff", ["type" => "color"]);?>
    <?php echo $this->formInput($formStem . "[options][background_color]","#ffffff", ["type" => "text"]);?>
    <label for="no-inline-bg_<?php echo $block->id;?>">
    <?php echo $this->formInput($formStem . "[options][background_color]","inherit", ["type" => "checkbox","class"=>"no-inline-bg","id" => "no-inline-bg_".$block->id]);?> <?php echo __("Do not set");?>
    </label>
    </div>
      <!-- end background color palette -->
    <div
        class="palette"
        data-formstem="<?php echo $formStem; ?>"
        data-property="color"
      >
      <?php echo $this->formLabel($formStem . "[options][color]");?>
      <div class="tiles"></div>
      <?php echo $this->formInput("color-palette-".$block->id,"#222222", ["type" => "color"]);?>
      <?php echo $this->formInput($formStem . "[options][color]","#222222", ["type" => "text"]);?>
      <label for="no-inline-bg_<?php echo $block->id;?>">
      <?php echo $this->formInput($formStem . "[options][color]","inherit", ["type" => "checkbox","class"=>"no-inline-bg","id" => "no-inline-bg_".$block->id]);?> <?php echo __("Do not set");?>
      </label>
      </div>
      <!-- end text color palette -->
    </div>
    <!-- end .palettes-col -->
    <div class="preview-wrapper">
      <label for="preview-<?php echo $formStem; ?>"
        ><?php echo __(
  "Preview Selected Colors"
); ?></label
      >
      <div class="preview" id="preview-<?php echo $formStem; ?>"
      style="background-color:<?php echo $options[
  "background_color"
]; ?>;color:<?php echo $options["color"]; ?>"> Lorem ipsum dolor sit amet,
      consectetur adipiscing elit. Proin malesuada varius massa. Donec vel
      pulvinar ex, eu tincidunt orci. Mauris dignissim sit amet magna vitae
      tempor. Sed orci dui, faucibus nec sapien eget, consectetur eleifend elit.
    </div>
  </div>
</div>
<!-- end .palettes-preview-wrapper -->
