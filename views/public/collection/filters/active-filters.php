
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

foreach ($active_filters as $filter => $values) {
  $active_filters[$filter] = array_unique($values);
}
?>
<div class="row col-12 align-items-center g-0">
  <div class="active-filters col-lg-9 py-3 no-js">
    <h3><?php echo __("Active Filters"); ?></h3>
    <p><?php echo __(
      "To remove a filter, click on its filter badge below, then submit the changes using the Update button. To remove all filters, use the Clear Filters button."
    ); ?>
    <?php if ($filters_set): ?>
    <?php foreach ($active_filters as $key => $set) {
      if (is_array($set)) {
        foreach ($set as $index => $id) {
          if (isset(${$key}[$id])) {
            echo '<div class="badge ' .
              $key .
              '"><a data-checkbox="' .
              str_replace("_", "-", $key) .
              "-" .
              $id .
              '">' .
              ${$key}[$id] .
              "</a></div>";
          }
        }
      }
    } ?>
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
    </div><!-- end .filter-button-wrapper -->
  </div><!-- end .filters -->
</div><!-- end .row -->

