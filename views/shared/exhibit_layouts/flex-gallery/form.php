<?php
$formStem = $block->getFormStem();
$options = $block->getOptions();
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
  "/includes/layout-options/admin/flex-gallery-palettes.php";
include EXHIBIT_MICROSITE_PLUGIN_DIR .
  "/includes/layout-options/admin/flex-file-text-colors.php";
include EXHIBIT_MICROSITE_PLUGIN_DIR .
  "/includes/layout-options/admin/padding.php";
include EXHIBIT_MICROSITE_PLUGIN_DIR .
  "/includes/layout-options/admin/flex-gallery-items.php";
include EXHIBIT_MICROSITE_PLUGIN_DIR .
  "/includes/layout-options/admin/thumbs-heading.php";
?>
</div><!-- end .layout-options -->
