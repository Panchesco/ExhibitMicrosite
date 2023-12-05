    <?php if (count($collections_filter_data) > 1): ?>
    <?php if (
      isset($_SESSION["filters"]["collection"]) &&
      is_array($_SESSION["filters"]["collection"])
    ) {
      $collections = $_SESSION["filters"]["collection"];
    } else {
      $collections = [];
    } ?>
    <div class="filter-block">
    <h2><?php echo __("Filter by Collection"); ?></h2>
      <ul id="collections">
      <?php foreach ($collections_filter_data as $id => $row): ?>
         <li><label for="collection-<?php echo $row[
           "collection_id"
         ]; ?>"><input type="checkbox" id="collection-<?php echo $row[
  "collection_id"
]; ?>" name="filters[collection][]" value="<?php echo $row[
  "collection_id"
]; ?>"<?php isChecked($row["collection_id"], $collections); ?>> <?php echo $row[
  "title"
]; ?></label></li>
      <?php endforeach; ?>
      </ul>
    </div><!-- end .filter-block -->
    <?php endif; ?>