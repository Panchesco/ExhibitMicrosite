
<?php
$collection = [];
$creator = [];
$item_type = [];
foreach ($collection_filter_data as $row) {
  $collection[$row["collection_id"]] = $row["title"];
}
foreach ($creator_filter_data as $row) {
  $creator[$row["id"]] = $row["creator"];
}
foreach ($item_type_filter_data as $row) {
  $item_type[$row["item_type_id"]] = $row["item_type"];
}
?>
<div class="row col-12">
  <div class="col-12">
 <h3 class="weight-200">Active Filters</h3>
</div>

<div class=" active-filters col-lg-9 p-3 no-xjs">
  <?php if ($filters_set): ?>
  <?php foreach ($active_filters as $key => $set) {
    if (is_array($set)) {
      foreach ($set as $index => $id) {
        echo '<div class="badge ' .
          $key .
          '" data-checkbox="' .
          $key .
          "-" .
          $id .
          '">' .
          ${$key}[$id] .
          "</div>";
      }
    }
  } ?>
  <?php else: ?>
    <p><?php echo __("No active filters."); ?></p>
  <?php endif; ?>
  </div><!-- end .active-filters -->
  <div class="filters col-lg-3 sans-serif g-0 p-0">
<div class="filter-button-wrapper badge-buttons">
    <button class="btn btn-sage" name="filters[action]" type="submit" value="set"><?php echo __(
      "Update"
    ); ?></button>
    <button class="btn btn-dark" name="filters[action]" type="submit" value="clear"><?php echo __(
      "Clear Filters"
    ); ?></button>
  </div>
  </div>
  </div><!-- end .row -->

