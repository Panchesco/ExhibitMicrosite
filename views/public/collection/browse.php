<?php echo __FILE__;
/**
 *
 * NOTE: If a native Exhibit Builder default Gallery Block is rending, and you're seeing a
 * Warning : Undefined array key “imgAttributes" message, this is an Omeka bug:
 * https://forum.omeka.org/t/gallery-block-error-message-undefined-array-key/16146
 *
 * Wrap for non-ems blocks:
 */
echo head();
?>
 <form name="filters" method="POST" action="<?php echo url(
   [
     "action" => "browse",
     "controller" => "browsecollection",
     "page_number" => "",
   ],
   "ems_collection"
 ); ?>">
<nav id="breadcrumb">
 <?php echo $breadcrumb; ?>
</nav>
<h1><?php echo isset($microsite_options["collection_page_title"])
  ? $microsite_options["collection_page_title"]
  : $exhibitPage->title; ?></h1>
  <?php if ($params->page_number == 1): ?>
  <div class="flex-blocks-wrapper d-flex flex-wrap g-0">
  <?php foreach ($exhibitPage->ExhibitPageBlocks as $block):
    $layout = $block->getLayout();
    $options = $block->getOptions();
    $attachments = $block->getAttachments();
    set_current_record("exhibit", $exhibit, true);
    echo get_view()->partial($layout->getViewPartial(), [
      "block" => $block,
      "exhibit" => $exhibit,
      "exhibitPage" => $exhibitPage,
      "item_route" => "ems_collection_item",
      "options" => $options,
      "page_slug_1" => $params->page_slug_1,
      "page_slug_2" => $params->page_slug_2,
      "page_slug_3" => $params->page_slug_3,
      "page_slugs" => $params->page_slugs,
      "slug" => $params->slug,
      "text" => $block->text,
      "params" => $params,
      "refUri" => $refUri,
      "prevData" => $prevData,
    ]);
  endforeach; ?>
  <?php endif; ?>
  <?php echo $this->view->partial("collection/filters/active-filters.php", [
    "active_filters" => $active_filters,
    "filters_set" => $filters_set,
    "collection_filter_data" => $collection_filter_data,
    "creator_filter_data" => $creator_filter_data,
    "item_type_filter_data" => $item_type_filter_data,
  ]); ?>
 <div class="col-lg-8">
    <?php if (count($items) == 0): ?>
    <p><?php echo __(
      "Nothing was found in the collection using the current filter set. Please try clearing the filters or using a different combination of them."
    ); ?>
        <?php else: ?>
          <p><?php echo $result_set_string; ?></p>
  <?php endif; ?>
  </div>

  </div><!-- end .flex-blocks-wrapper -->
<div class="flex-blocks-wrapper d-flex flex-row flex-wrap g-0 justify-content-between">
  <div class="filters col-12 order-2 col-lg-3 order-lg-1">
<?php echo $this->view->partial("collection/filters/collection-id.php", [
  "collection_filter_data" => $collection_filter_data,
]); ?>
<?php echo $this->view->partial("collection/filters/creator.php", [
  "creator_filter_data" => $creator_filter_data,
]); ?>
<?php echo $this->view->partial("collection/filters/item-types.php", [
  "item_type_filter_data" => $item_type_filter_data,
]); ?>
<?php if (class_exists("Omeka_Form_Element_SessionCsrfToken")) {
  //$csrf = new Omeka_Form_Element_SessionCsrfToken();
} ?>
<div class="filter-button-wrapper">
  <button class="btn btn-sage " name="filters[action]" type="submit" value="set"><?php echo __(
    "Set Filters"
  ); ?></button>
  <button class="btn btn-dark" name="filters[action]" type="submit" value="clear"><?php echo __(
    "Clear Filters"
  ); ?></button>
</div>
</div>
  <div class="col-12 order-1 order-lg-2 col-lg-9 px-3">
    <h2 class="sr-only"><?php echo __("Items"); ?></h2>
    <div class="masonry-grid row">
   <?php foreach ($items as $item): ?>
     <?php set_current_record("item", $item); ?>
     <div class="collection-item-wrapper col-12 col-md-6 col-lg-4" data-creator="<?php echo urlencode(
       metadata("Item", ["Dublin Core", "Creator"])
     ); ?>">
       <div class="collection-item">
          <?php $item_url = url(
            [
              "action" => "show",
              "slug" => $params->paramsArray["slug"],
              "collection_id" => $item->collection_id,
              "item_id" => $item->id,
            ],
            "ems_collection_item"
          ); ?>
          <figure>
             <div class="image-wrapper">
               <?php echo item_image(
                 "thumbnail",
                 ["class" => "lazy"],
                 0,
                 $item
               ); ?>
               <?php if ($item->Files && count($item->Files) > 1): ?>
               <div class="plus">+<?php echo count($item->Files) -
                 1; ?></div><?php endif; ?>
             </div><!-- end .image-wrapper -->
              <figcaption>
                <?php echo metadata("Item", "rich_title"); ?>
              </figcaption>
          </figure><!-- end  -->
          <a class="item-link" href="<?php echo $item_url; ?>"><span class="visually-hidden"><?php echo __(
  "View this item"
); ?></span></a>
          </div><!-- end .item-wrapper -->
    </div><!-- end .collection-item-wrapper -->
  <?php endforeach; ?>
    </div>
    <?php if ($total_pages > 1): ?>
    <?php echo $this->view->partial("sitewide/pagination.php", [
      "pagination" => $pagination,
      "route" => $route,
      "action" => "browse",
      "controller" => "browsecollection",
    ]); ?>
    <?php endif; ?>
  </div>
</div><!-- end .flex-blocks-wrapper -->
</form>


<?php echo foot(); ?>

