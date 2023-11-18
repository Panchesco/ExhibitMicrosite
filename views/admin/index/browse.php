<?php

queue_css_file("exhibit-microsite");
$head = [
  "bodyclass" => "exhibit-microsite browse",
  "title" => html_escape(__("Exhibit Microsite | Browse")),
  "content_class" => "horizontal-nav",
];
$hierarchy = false;

echo head($head);
?>
<ul id="section-nav" class="navigation">
    <li class="<?php if (!$hierarchy) {
      echo "current";
    } ?>">
        <a href="<?php echo html_escape(
          url("exhibit-microsite/index/browse?view=list")
        ); ?>"><?php echo __("List View"); ?></a>
    </li>
</ul>
<?php echo flash(); ?>
<?php echo serialize([
  "reclaiming-the-border-narrativ" => "8",
  "ima-test" => "15",
]); ?>
<a class="add-page button green" href="<?php echo html_escape(
  url("simple-pages/index/add")
); ?>"><?php echo __("Add a Page"); ?></a>
<?php if (!has_loop_records("simple_pages_page")): ?>
    <p><?php echo __("There are no pages."); ?> <a href="<?php echo html_escape(
   url("simple-pages/index/add")
 ); ?>"><?php echo __("Add a page."); ?></a></p>
<?php else: ?>
    <?php if ($hierarchy): ?>
        <?php echo $this->partial("index/browse-hierarchy.php", [
          "simplePages" => $simple_pages_pages,
        ]); ?>
    <?php else: ?>
        <?php echo $this->partial("index/browse-list.php", [
          "simplePages" => $simple_pages_pages,
        ]); ?>
    <?php endif; ?>
<?php endif; ?>
<?php echo foot(); ?>
