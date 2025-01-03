<?php echo __FILE__;
$pageTitle = __("Browse Items");
echo $this->view->partial("microsite-header.php", [
  "title" => $microsite->options["microsite_title"],
  "subheading" => $microsite->options["microsite_subheading"],
  "theme_options" => $theme_options,
  "params" => $params,
  "global_nav" => $nav->top_pages_html,
  "bodyid" => $this->microsite->route,
  "bodyclass" => "browse",
  "view" => $this->view,
  "breadcrumb" => $breadcrumb,
  "exhibit_theme_options" => $exhibit_theme_options,
]);
?>

<h1><?php echo $pageTitle; ?> <?php echo __(
   "(%s total)",
   $total_results
 ); ?></h1>

<nav class="items-nav navigation secondary-nav">
    <?php echo public_nav_items(); ?>
</nav>

<?php echo item_search_filters(); ?>

<?php echo pagination_links(); ?>

<?php if ($total_results > 0): ?>

<?php
$sortLinks[__("Title")] = "Dublin Core,Title";
$sortLinks[__("Creator")] = "Dublin Core,Creator";
$sortLinks[__("Date Added")] = "added";
?>
<div id="sort-links">
    <span class="sort-label"><?php echo __(
      "Sort by: "
    ); ?></span><?php echo browse_sort_links($sortLinks); ?>
</div>

<?php endif; ?>

<?php foreach (loop("items") as $item): ?>
<div class="item record">
    <h2><?php echo link_to_item(null, ["class" => "permalink"]); ?></h2>
    <div class="item-meta">
    <?php if (metadata("item", "has files")): ?>
    <div class="item-img">
        <?php echo link_to_item(item_image()); ?>
    </div>
    <?php endif; ?>

    <?php if (
      $description = metadata(
        "item",
        ["Dublin Core", "Description"],
        ["snippet" => 250]
      )
    ): ?>
    <div class="item-description">
        <?php echo $description; ?>
    </div>
    <?php endif; ?>

    <?php if (metadata("item", "has tags")): ?>
    <div class="tags"><p><strong><?php echo __("Tags"); ?>:</strong>
        <?php echo tag_string("items"); ?></p>
    </div>
    <?php endif; ?>

    <?php fire_plugin_hook("public_items_browse_each", [
      "view" => $this,
      "item" => $item,
    ]); ?>

    </div><!-- end class="item-meta" -->
</div><!-- end class="item hentry" -->
<?php endforeach; ?>

<?php echo pagination_links(); ?>

<div id="outputs">
    <span class="outputs-label"><?php echo __("Output Formats"); ?></span>
    <?php echo output_format_list(false); ?>
</div>

<?php fire_plugin_hook("public_items_browse", [
  "items" => $items,
  "view" => $this,
]); ?>

<?php echo foot(); ?>
