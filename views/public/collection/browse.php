<?php echo __FILE__;
echo head();
?>
<nav id="breadcrumb">
 <?php echo $breadcrumb; ?>
</nav>
<h1><?php echo isset($microsite_options["collection_page_title"])
  ? $microsite_options["collection_page_title"]
  : $exhibitPage->title; ?></h1>
<div class="flex-blocks-wrapper d-flex flex-wrap g-0 justify-content-between">
  <div class="filters col-lg-3">
    <?php if (count($collections_filter_data) > 1): ?>
    <div class="filter-block">
    <h2><?php echo __("Filter by Collection"); ?></h2>
      <ul id="collections">
      <?php foreach ($collections_filter_data as $id => $row): ?>
         <li><label for="collection-<?php echo $row[
           "collection_id"
         ]; ?>"><input type="checkbox" id="collection-<?php echo $row[
  "collection_id"
]; ?>" name="collection[]" value="<?php echo $row[
  "collection_id"
]; ?>"> <?php echo $row["title"]; ?></label></li>
      <?php endforeach; ?>
      </ul>
    </div><!-- end .filter-block -->
    <?php endif; ?>
<?php if (count($creators_filter_data) > 1): ?>
    <div class="filter-block">
        <h2><?php echo __("Filter by Creator"); ?></h2>
          <ul id="creators">
          <?php foreach ($creators_filter_data as $key => $row): ?>
             <li><label for="creator-<?php echo $key; ?>"><input type="checkbox" id="creator-<?php echo $key; ?>"
             name="creator[]"
             value="<?php echo urlencode($row["creator"]); ?>"> <?php echo $row[
  "creator"
]; ?></label></li>
          <?php endforeach; ?>
          </ul>
        </div><!-- end .filter-block -->
<?php endif; ?>
<?php if (count($item_types_filter_data) > 1): ?>
    <div class="filter-block">
      <h2><?php echo __("Filter by Item Type"); ?></h2>
      <ul id="item-types">
      <?php foreach ($item_types_filter_data as $key => $row): ?>
        <li><label for="creator-<?php echo $key; ?>"><input type="checkbox" id="item-type-<?php echo $key; ?>"
      name="item_type[]"
      value="<?php echo $row["item_type_id"]; ?>"> <?php echo $row[
  "item_type"
]; ?></label></li>
      <?php endforeach; ?>
      </ul>
  </div><!-- end .filter-block -->
<?php endif; ?>
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

<?php echo foot(); ?>
