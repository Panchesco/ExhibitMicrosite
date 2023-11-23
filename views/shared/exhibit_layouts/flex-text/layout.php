<?php
/**
 * The following vars are available:
 * $options
 * $text
 * $block
 * $block_id
 * $exhibit_id
 * $exhibit_title
 * $exhibit_slug
 * $exhibit_page_title
 * $exhibit_page_short_title
 * $exhibit_page_slug
 */
include EXHIBIT_MICROSITE_PLUGIN_DIR .
  "/includes/layout-options/public/option-defaults.php"; ?>
 <div class="block block-flex-values<?php echo "$block_flex_values"; ?>">

        <div class="item-wrapper<?php echo $item_flex_values; ?>"<?php echo $inline_styles; ?>>
        <?php echo $text; ?>
        </div><!-- end .item-wrapper -->
 </div><!-- end .block-flex-values -->
