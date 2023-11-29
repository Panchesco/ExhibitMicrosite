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
    <?php if ($count > 1): ?>
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
    <div class="filter-block">
        <h2><?php echo __("Filter by Creator"); ?></h2>
          <ul id="creators">
          <?php foreach ($creators_filter_data as $key => $row): ?>
             <li><label for="creator-<?php echo $key; ?>"><input type="checkbox" id="creator-<?php echo $key; ?>"
             name="creators[]"
             value="<?php echo urlencode($row["creator"]); ?>"> <?php echo $row[
  "creator"
]; ?></label></li>
          <?php endforeach; ?>
          </ul>
        </div><!-- end .filter-block -->
  </div>
  <div class="col-lg-9 px-3">
   [items]
  </div>
</div><!-- end .flex-blocks-wrapper -->
<?php echo foot(); ?>
