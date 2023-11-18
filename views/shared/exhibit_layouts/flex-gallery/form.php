<?php
$formStem = $block->getFormStem();
$options = $block->getOptions();

//$palette = themePalette();
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
    </div>



<?php // Include partials


include PLUGIN_DIR . "/Waterworks/includes/layout-options/col-span.inc";
include PLUGIN_DIR . "/Waterworks/includes/layout-options/palettes.inc";
?>
</div>
