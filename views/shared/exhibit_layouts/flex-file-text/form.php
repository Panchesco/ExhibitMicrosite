<?php

$formStem = $block->getFormStem();
$options = $block->getOptions();
$options["background-color"] = isset($options["background-color"])
  ? $options["background-color"]
  : "#e9ecef";
$options["color"] = isset($options["color"]) ? $options["color"] : "#222222";
$options["col-span"] = isset($options["col-span"]) ? $options["col-span"] : 6;
$options["gallery-file-size"] = isset($options["gallery-file-size"])
  ? $options["gallery-file-size"]
  : "fullsize";
$palette = themePalette();
?>
<div class="selected-items">
    <h4><?php echo __("Items"); ?></h4>
    <?php echo $this->exhibitFormAttachments($block); ?>
</div>

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
include PLUGIN_DIR . "/Waterworks/includes/layout-options/col-span.inc";
include PLUGIN_DIR . "/Waterworks/includes/layout-options/align-self.inc";
include PLUGIN_DIR . "/Waterworks/includes/layout-options/flex-direction.inc";
include PLUGIN_DIR . "/Waterworks/includes/layout-options/padding.inc";
include PLUGIN_DIR . "/Waterworks/includes/layout-options/palettes.inc";
?>
</div><!-- end .layout-options -->
