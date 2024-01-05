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
  "/views/admin/layout-options/flex-gallery-palettes.php";
include EXHIBIT_MICROSITE_PLUGIN_DIR .
  "/views/admin/layout-options/flex-file-text-colors.php";
include EXHIBIT_MICROSITE_PLUGIN_DIR .
  "/views/admin/layout-options/padding.php";
include EXHIBIT_MICROSITE_PLUGIN_DIR .
  "/views/admin/layout-options/flex-gallery-items.php";
include EXHIBIT_MICROSITE_PLUGIN_DIR .
  "/views/admin/layout-options/thumbs-heading.php";
?>
</div><!-- end .layout-options -->
