<?php
/**
 *
 * NOTE: If a native Exhibit Builder default Gallery Block is rending, and you're seeing a
 * Warning : Undefined array key “imgAttributes" message, this is an Omeka bug:
 * https://forum.omeka.org/t/gallery-block-error-message-undefined-array-key/16146
 *
 * Wrap for non-ems blocks:
 */
echo $this->view->partial("microsite-header.php", [
  "title" => $microsite->options["microsite_title"],
  "subheading" => $microsite->options["microsite_subheading"],
  "theme_options" => $theme_options,
  "params" => $params,
  "global_nav" => $nav->top_pages_html,
]); ?>
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
  <div class="col-lg-8">
    <?php if (count($items) == 0): ?>
    <p><?php echo __(
      "Nothing was found in the collection using the current filter set. Please try clearing the filters or using a different combination of them."
    ); ?></p>
        <?php else: ?>
          <p><?php echo $result_set_string; ?></p>
  <?php endif; ?>
  </div>
  <?php if ($filters_set): ?>
  <?php echo $this->view->partial("collection/filters/active-filters.php", [
    "active_filters" => $active_filters,
    "filters_set" => $filters_set,
    "collection_filter_data" => $collection_filter_data,
    "creator_filter_data" => $creator_filter_data,
    "item_type_filter_data" => $item_type_filter_data,
    "options" => $microsite_options,
  ]); ?>
  <?php endif; ?>
<div class="flex-blocks-wrapper d-flex flex-row flex-wrap  justify-content-between">
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
      <div class="row masonry-grid">
   <?php foreach ($items as $item): ?>
     <?php set_current_record("item", $item); ?>
     <div class="collection-item-wrapper col-12 col-md-6 col-lg-4">
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
               <?php echo item_image("fullsize", ["class" => ""], 0, $item); ?>
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


    </div><!-- end .row -->

</div><!-- end .flex-blocks-wrapper -->
<div class="row">
  <div class="col">
    <?php if ($total_pages > 1): ?>
    <?php echo $this->view->partial("sitewide/pagination.php", [
      "pagination" => $pagination,
      "route" => $route,
      "action" => "browse",
      "controller" => "browsecollection",
    ]); ?>
    <?php endif; ?>
  </div><!-- end .col-lg-8 -->
</div><!-- end .row -->
</form>


<?php echo foot(); ?>

