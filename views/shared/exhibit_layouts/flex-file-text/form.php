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


include EXHIBIT_MICROSITE_PLUGIN_DIR .
  "/views/admin/layout-options/palettes.php";
include EXHIBIT_MICROSITE_PLUGIN_DIR .
  "/views/admin/layout-options/col-span.php";
include EXHIBIT_MICROSITE_PLUGIN_DIR .
  "/views/admin/layout-options/justify-content.php";
include EXHIBIT_MICROSITE_PLUGIN_DIR .
  "/views/admin/layout-options/align-self.php";
include EXHIBIT_MICROSITE_PLUGIN_DIR .
  "/views/admin/layout-options/flex-direction.php";
include EXHIBIT_MICROSITE_PLUGIN_DIR .
  "/views/admin/layout-options/padding.php";
include EXHIBIT_MICROSITE_PLUGIN_DIR .
  "/views/admin/layout-options/flex-file-text-colors.php";
include EXHIBIT_MICROSITE_PLUGIN_DIR .
  "/views/admin/layout-options/flex-file-text-padding.php";
include EXHIBIT_MICROSITE_PLUGIN_DIR .
  "/views/admin/layout-options/flex-file-text-margin.php";
?>
</div><!-- end .layout-options -->
