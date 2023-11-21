<?php
$formStem = $block->getFormStem();
$options = $block->getOptions();
$options["col-span"] = isset($options["col-span"]) ? $options["col-span"] : 6;
?>
<div class="selected-items" data-formstem="<?php echo $formStem; ?>" data-blocktype="water-file">
    <h4><?php echo __("Item"); ?></h4>
    <?php echo $this->exhibitFormAttachments($block); ?>
</div>

<div class="layout-options">
    <div class="block-header">
        <h4><?php echo __("Layout Options"); ?></h4>
        <div class="drawer-toggle"></div>
    </div>


<?php // Include partials


include EXHIBIT_MICROSITE_PLUGIN_DIR . "/includes/layout-options/admin/palettes.php";
include EXHIBIT_MICROSITE_PLUGIN_DIR . "/includes/layout-options/admin/col-span.php";
include EXHIBIT_MICROSITE_PLUGIN_DIR .
  "/includes/layout-options/admin/align-self.php";
include EXHIBIT_MICROSITE_PLUGIN_DIR .
  "/includes/layout-options/admin/flex-direction.php";
include EXHIBIT_MICROSITE_PLUGIN_DIR . "/includes/layout-options/admin/padding.php";
?>
</div>
