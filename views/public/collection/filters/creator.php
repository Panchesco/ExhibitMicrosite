<?php if (count($creator_filter_data) > 1): ?>
<?php if (
  isset($_SESSION["filters"]["creator"]) &&
  is_array($_SESSION["filters"]["creator"])
) {
  $creator = $_SESSION["filters"]["creator"];
} else {
  $creator = [];
} ?>
    <div class="creator filter-block">
        <h2><?php echo __("Filter by Creator"); ?></h2>
          <ul id="creator">
          <?php foreach ($creator_filter_data as $key => $row): ?>
             <li><label for="creator-<?php echo $row[
               "id"
             ]; ?>"><input data-label="<?php echo $row[
  "creator"
]; ?>" type="checkbox" id="creator-<?php echo $row["id"]; ?>"
             name="filters[creator][]"
             value="<?php echo urlencode($row["id"]); ?>"<?php isChecked(
  $row["id"],
  $creator
); ?>> <?php echo $row["creator"]; ?></label></li>
          <?php endforeach; ?>
          </ul>
        </div><!-- end .filter-block -->
<?php endif; ?>
