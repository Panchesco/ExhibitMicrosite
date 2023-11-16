<?php
/**
 * palettes.inc
 * This include is meant for inclusion in views/shared/exhibit_layouts/[FORMNAME]
 * for setting text and background colors for Waterworks Plugin exhibit blocks for display
 * in the Water theme.
 */
 $options['background_color'] = (isset($options['background_color'])) ? $options['background_color'] : "#ffffff";
 $options['color'] = (isset($options['color'])) ? $options['color'] : "#222222";

?>
<div class="palettes-preview-wrapper">
  <div class="palettes-col">
      <div class="palette" data-formstem="<?php echo $formStem; ?>" data-target="<?php echo $formStem; ?>[options][background_color]" data-property="background_color">
      <?php echo $this->formLabel(
        $formStem . "[options][background_color]",
        __("Select a Background Color")
      ); ?>
      <div class="tiles"></div>
      <input type="color" name="<?php echo $formStem; ?>[options][background_color]" value="<?php echo $options[
  "background_color"
]; ?>" data-property="background_color" title="<?php echo $options[
  "background_color"
]; ?>" />
      </div>
      <!-- end background color palette -->
     <div class="palette" data-formstem="<?php echo $formStem; ?>" data-target="<?php echo $formStem; ?>[options][color]" data-property="color">
     <?php echo $this->formLabel(
       $formStem . "[options][color]",
       __("Select a Text Color")
     ); ?>
     <div class="tiles"></div>
    <input type="color" name="<?php echo $formStem; ?>[options][color]" value="<?php echo $options[
  "color"
]; ?>" data-property="color" title="<?php echo $options[
  "color"
]; ?>" />
    </div>
    <!-- end text color palette -->
  </div><!-- end .palettes-col -->
  <div class="preview-wrapper">
    <label for="preview-<?php echo $formStem; ?>"><?php echo __(
  "Preview Selected Colors"
); ?></label>
    <div class="preview" id="preview-<?php echo $formStem; ?>" style="background-color:<?php echo $options[
  "background_color"
]; ?>;color:<?php echo $options["color"]; ?>">
      Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin malesuada varius massa. Donec vel pulvinar ex, eu tincidunt orci. Mauris dignissim sit amet magna vitae tempor. Sed orci dui, faucibus nec sapien eget, consectetur eleifend elit.
    </div>
  </div>
</div><!-- end .palettes-preview-wrapper -->
