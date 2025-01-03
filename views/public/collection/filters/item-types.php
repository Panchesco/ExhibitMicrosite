<?php if (count($item_type_filter_data) > 1): ?>
  <?php if (
    isset($_SESSION["filters"]["item_type"]) &&
    is_array($_SESSION["filters"]["item_type"])
  ) {
    $item_type = $_SESSION["filters"]["item_type"];
  } else {
    $item_type = [];
  } ?>
    <div class="item-type filter-block">
      <h2><?php echo __("Filter by Item Type"); ?></h2>
      <ul id="item-types">
      <?php foreach ($item_type_filter_data as $key => $row): ?>
        <li><label for="item-type-<?php echo $row[
          "item_type_id"
        ]; ?>"><input data-label="<?php echo $row[
  "item_type"
]; ?> " type="checkbox" id="item-type-<?php echo $row["item_type_id"]; ?>"
      name="filters[item_type][]"
      value="<?php echo $row["item_type_id"]; ?>"<?php isChecked(
  $row["item_type_id"],
  $item_type
); ?>><?php echo $row["item_type"]; ?></label></li>
      <?php endforeach; ?>
      </ul>
  </div><!-- end .filter-block -->
<?php endif; ?>
