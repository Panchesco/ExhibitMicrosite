<?php
// This needs to work both on the ExhibitPage and in the global nav.
// In the global nav we'll need to get the ExhibitPage object if we're not on the Projects page.
$exhibitPage = isset($exhibitPage)
  ? $exhibitPage
  : get_record("ExhibitPage", ["exhibit_id" => $microsite->options['exhibit_id'], "slug" => "collections"]);
$child_pages = $exhibitPage->getChildPages();
?>
<div class="container g-0">
    <div class="d-flex flex-wrap">
  <?php foreach ($child_pages as $page):
    $file = exhibitPage_first_file_image($page); ?>
  <div class="exhibit-page-link-wrapper col-12 col-md-6 col-lg-4 ">
      <div class="exhibit-page-link py-5">
      <a href="<?php echo url(
        [
          "action" => "show",
          "controller" => "exhibitpage",
          "slug" => $microsite->params->paramsArray["slug"],
          "page_slug_1" => "collections",
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
  </div>
</div><!-- end .container -->

