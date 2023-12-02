<?php echo __FILE__;
echo head();

if (class_exists("Omeka_Form_Element_SessionCsrfToken")) {
  echo "class exists";
}
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

<div class="flex-blocks-wrapper d-flex flex-wrap g-0 justify-content-between">
  <div class="filters col-lg-3">
<?php echo $this->view->partial("collection/filters/collection-id.php", [
  "collections_filter_data" => $collections_filter_data,
]); ?>
<?php echo $this->view->partial("collection/filters/creators.php", [
  "creators_filter_data" => $creators_filter_data,
]); ?>
<?php echo $this->view->partial("collection/filters/item-types.php", [
  "item_types_filter_data" => $item_types_filter_data,
]); ?>
<?php if (class_exists("Omeka_Form_Element_SessionCsrfToken")) {
  //$csrf = new Omeka_Form_Element_SessionCsrfToken();
} ?>
<div class="filter-button-wrapper">
  <button class="btn btn-dark" name="filters[action]" type="submit" value="clear"><?php echo __(
    "Clear Filters"
  ); ?></button>
  <button class="btn btn-sage " name="filters[action]" type="submit" value="set"><?php echo __(
    "Set Filters"
  ); ?></button>
</div>
</div>
  <div id="collection" class="col-lg-9 px-3">
    <div class="row" data-masonry='{"percentPosition": true }'>
   <?php foreach ($items as $item): ?>
     <?php set_current_record("item", $item); ?>
     <div class="collection-item-wrapper" data-creator="<?php echo urlencode(
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
