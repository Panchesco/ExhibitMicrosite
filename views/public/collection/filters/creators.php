<?php if (count($creators_filter_data) > 1): ?>
<?php if (
  isset($_SESSION["filters"]["creator"]) &&
  is_array($_SESSION["filters"]["creator"])
) {
  $creators = $_SESSION["filters"]["creator"];
} else {
  $creators = [];
} ?>
    <div class="filter-block">
        <h2><?php echo __("Filter by Creator"); ?></h2>
          <ul id="creators">
          <?php foreach ($creators_filter_data as $key => $row): ?>
             <li><label for="creator-<?php echo $key; ?>"><input type="checkbox" id="creator-<?php echo $key; ?>"
             name="filters[creator][]"
             value="<?php echo urlencode($row["id"]); ?>"<?php isChecked(
  $row["id"],
  $creators
); ?>> <?php echo $row["creator"]; ?></label></li>
          <?php endforeach; ?>
          </ul>
        </div><!-- end .filter-block -->
<?php endif; ?>
