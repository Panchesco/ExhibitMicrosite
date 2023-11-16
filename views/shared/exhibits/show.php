<?php echo __FILE__;

echo head([
  "title" =>
    metadata("exhibit_page", "title") .
    " &middot; " .
    metadata("exhibit", "title"),
  "bodyclass" => "exhibits show",
]);


if (@$exhibitPage === null) {
  $exhibitPage = get_current_record("exhibit_page");
}
$navtree = SCWaterExhibitNavTreeData($exhibit);
?>

<nav id="breadcrumb">
  <ul>
    <li><a href="/exhibits/show/reclaiming-the-border-narrativ">Exhibit Name</a></li>
    <li><a href="/exhibits/show/reclaiming-the-border-narrativ/projects">Projects</a></li>
    <li><a href="/exhibits/show/reclaiming-the-border-narrativ/projects/monumental-interventions">Exhibit Page Name</a></li>
  </ul>
</nav>

<h1><span class="exhibit-page"><?php echo metadata(
  "exhibit_page",
  "title"
); ?></span></h1>

<div class="d-flex flex-wrap align-items-stretch">
<?php //$blocks = $exhibitPage->ExhibitPageBlocks;

SCWaterRenderBlocks(); ?>
</div><!-- end .d-flex -->

<?php echo exhibit_builder_page_trail(); ?>
<div id="exhibit-page-navigation">
    <?php if ($prevLink = exhibit_builder_link_to_previous_page()): ?>
    <div id="exhibit-nav-prev">
    <?php echo $prevLink; ?>
    </div>
    <?php endif; ?>
    <?php if ($nextLink = exhibit_builder_link_to_next_page()): ?>
    <div id="exhibit-nav-next">
    <?php echo $nextLink; ?>
    </div>
    <?php endif; ?>
    <div id="exhibit-nav-up">
    <?php echo exhibit_builder_page_trail(); ?>
    </div>
</div>

<nav id="exhibit-pages">
    <h4><?php
//echo exhibit_builder_link_to_exhibit($exhibit);
?></h4>
    <?php
//echo exhibit_builder_page_tree($exhibit, $exhibit_page);
?>
</nav>

<?php echo foot(); ?>
