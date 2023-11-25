<?php
queue_css_file("exhibit-microsite");
$head = [
  "bodyclass" => "exhibit-microsite browse",
  "title" => html_escape(__("Exhibit Microsite | Browse")),
  "content_class" => "horizontal-nav",
];
echo head($head);
?>
<ul id="section-nav" class="navigation">
    <li class="<?php if (!$hierarchy) {
      echo "current";
    } ?>">
        <a href="<?php echo html_escape(
          url("exibit-microsite/index/browse?view=list")
        ); ?>"><?php echo __("List View"); ?></a>
    </li>
</ul>
<?php echo flash(); ?>

<a class="add-page button green" href="<?php echo html_escape(
  url("exhibit-microsite/index/add")
); ?>"><?php echo __("Add an Exhibit Microsite"); ?></a>
<?php if (!has_loop_records("exhibit_microsite_microsite")): ?>
    <p><?php echo __(
      "There are no microsites."
    ); ?> <a href="<?php echo html_escape(
   url("exhibit-microsite/index/add")
 ); ?>"><?php echo __("Add an Exhibit Microsite."); ?></a></p>
<?php else: ?>
        <?php echo $this->partial("index/browse-list.php", [
          "exhibitMicrosite" => $exhibit_microsite_microsites,
        ]); ?>
<?php endif; ?>
<?php echo foot(); ?>
