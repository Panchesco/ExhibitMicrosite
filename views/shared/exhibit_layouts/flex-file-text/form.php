<?php

$formStem = $block->getFormStem();
$options = $block->getOptions();
$options["col-span"] = isset($options["col-span"]) ? $options["col-span"] : 6;
$options["gallery-file-size"] = isset($options["gallery-file-size"])
  ? $options["gallery-file-size"]
  : "fullsize";
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


include EXHIBIT_MICROSITE_PLUGIN_DIR . "/includes/layout-options/palettes.inc";
include EXHIBIT_MICROSITE_PLUGIN_DIR . "/includes/layout-options/col-span.inc";
include EXHIBIT_MICROSITE_PLUGIN_DIR .
  "/includes/layout-options/justify-content.inc";
include EXHIBIT_MICROSITE_PLUGIN_DIR .
  "/includes/layout-options/align-self.inc";
include EXHIBIT_MICROSITE_PLUGIN_DIR .
  "/includes/layout-options/flex-direction.inc";
include EXHIBIT_MICROSITE_PLUGIN_DIR . "/includes/layout-options/padding.inc";
?>
</div><!-- end .layout-options -->
