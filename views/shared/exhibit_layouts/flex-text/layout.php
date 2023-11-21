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
<div class="<?php echo $class; ?>"<?php echo $inline_styles; ?>>
       <div class="d-flex <?php echo $flex_direction; ?> justify-content-start">
          <div class="<?php echo $text_div_class; ?>">
          <?php echo $text; ?>
          </div>
       </div><!-- end .text -->
</div><!-- end .water-gap -->
