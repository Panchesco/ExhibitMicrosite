<?php

$formStem = $block->getFormStem();
$options = $block->getOptions();
$options["background_color"] = isset($options["background_color"])
  ? $options["background_color"]
  : "#e9ecef";
$options["color"] = isset($options["color"]) ? $options["color"] : "#000000";
$options["col-span"] = isset($options["col-span"]) ? $options["col-span"] : 6;
$options["new-row"] = isset($options["new-row"]) ? $options["new-row"] : 0;
$palette = [];//themePalette();
?>

<div class="block-text">
    <h4><?php echo __("Text"); ?></h4>
    <?php echo $this->exhibitFormText($block); ?>
</div>

<div class="layout-options">
<div class="block-header">
    <h4><?php echo __("Layout Options"); ?></h4>
    <div class="drawer-toggle"></div>
</div><!-- end .layout-options -->

<?php // Include partials


include PLUGIN_DIR . "/ExhibitMicrosite/includes/layout-options/col-span.inc";
include PLUGIN_DIR . "/ExhibitMicrosite/includes/layout-options/align-self.inc";
include PLUGIN_DIR . "/ExhibitMicrosite/includes/layout-options/flex-direction.inc";
include PLUGIN_DIR . "/ExhibitMicrosite/includes/layout-options/padding.inc";
include PLUGIN_DIR . "/ExhibitMicrosite/includes/layout-options/palettes.inc";
?>
</div><!-- end .layout-options -->

