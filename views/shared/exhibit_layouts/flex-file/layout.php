<?php echo __FILE__ . "<p>refUri: " . $refUri . "</p>";

$files = [];
$captions = [];
$file_images = [];
$item_urls = [];
$plus = [];
$block_items = [];
$item;
$refUri = isset($refUri) ? $refUri : current_url();
$attachments = $block->getAttachments();

// $attachment->Item->Files

include EXHIBIT_MICROSITE_PLUGIN_DIR .
  "/includes/layout-options/public/option-defaults.php";
?>
<div class="block block-flex-values<?php echo "$block_flex_values"; ?>">
<?php foreach ($attachments as $key => $attachment):
  if ($attachment->Item && isset($attachment->Item->Files[0])): ?>
       <div class="item-wrapper<?php echo $item_flex_values; ?>"<?php echo $inline_styles; ?>>
       <?php $item_url = $refUri . "/item/" . $attachment->Item->id; ?>
       <figure>
          <div class="image-wrapper">
            <?php echo item_image(
              "thumbnail",
              ["class" => "lazy"],
              0,
              $attachment->Item
            ); ?>
            <?php if (
              $attachment->Item->Files &&
              count($attachment->Item->Files) > 1
            ): ?><div class="plus">+<?php echo count($attachment->Item->Files) -
  1; ?></div><?php endif; ?>
            <a class="item-link" href="<?php echo $item_url; ?>"><span class="visually-hidden"><?php echo __(
  "View this item"
); ?></span></a>
          </div><!-- end .image-wrapper -->
           <?php if (!empty($attachment->caption)): ?><figcaption>
             <?php echo $attachment->caption; ?>
           </figcaption><?php endif; ?>
       </figure><!-- end  -->
       </div><!-- end .item-wrapper -->
 <?php endif;
endforeach; ?>
</div><!-- end .block-flex-values -->
