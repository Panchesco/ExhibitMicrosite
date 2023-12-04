<?php echo __FILE__;

// This needs to work both on the ExhibitPage and in the global nav.
// In the global nav we'll need to get the ExhibitPage object if we're not on the Projects page.
$exhibitPage = isset($exhibitPage)
  ? exhibitPage
  : get_record("ExhibitPage", ["exhibit_id" => 8, "slug" => "projects"]);
$child_pages = isset($exhibitPage)
  ? $child_pages
  : $exhibitPage->getChildPages();
?>
<nav class="d-flex flex-wrap">
<?php foreach ($child_pages as $page):
  $file = exhibitPage_first_file_image($page); ?>
  <div class="exhibit-page-link-wrapper col-12 col-md-6 col-lg-4 ">
    <div class="exhibit-page-link py-4">
      <a href="<?php echo url(
        [
          "action" => "show",
          "slug" => $params->slug,
          "page_slug_1" => $params->page_slug_1,
          "page_slug_2" => $page->slug,
        ],
        "ems_exhibitPage2"
      ); ?>">
  <?php echo file_image("square_thumbnail", ["class" => ""], $file); ?>
      <span><?php echo $page->title; ?></span>
    </a>
    </div>
  </div>
<?php
endforeach; ?>
</nav>
