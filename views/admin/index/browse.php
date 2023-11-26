<?php
queue_css_file("exhibit-microsite");
$head = [
  "bodyclass" => "exhibit-microsite browse",
  "title" => html_escape(__("Exhibit Microsites | Browse")),
  "content_class" => "horizontal-nav",
];

$options = isset($options) && is_array($options) ? $options : [];

$hierarchy = false;
if (isset($_GET["view"])) {
  $hierarchy = $_GET["view"] == "hierarchy";
}
echo head($head);
?>
<?php echo flash(); ?>
<a class="add-page button green" href="<?php echo html_escape(
  url("exhibit-microsite/index/add")
); ?>"><?php echo __("Add an Exhibit Microsite"); ?></a>
<?php set_loop_records("options", $options); ?>
<?php if (!has_loop_records("options")): ?>
    <p><?php echo __(
      "There are no Exhibit Microsites."
    ); ?> <a href="<?php echo html_escape(
   url("exhibit-microsite/index/add")
 ); ?>"><?php echo __("Add one."); ?></a></p>
<?php else: ?>
        <?php echo $this->partial("index/browse-list.php", [
          "options" => $options,
        ]); ?>
<?php endif; ?>
<?php echo foot(); ?>
