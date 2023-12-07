<?php
set_current_record("Item", $item);
echo head([
  "title" => metadata("item", ["Dublin Core", "Title"]),
  "bodyclass" => "items show",
]);

if (
  isset($theme_options["item_canvas_color"]) &&
  !empty($theme_options["item_canvas_color"])
) {
  $item_canvas_inline = ' style="{$theme_options["item_canvas_color"]}"';
}

$item_title = trim(
  metadata("item", "rich_title", [
    "no_escape" => true,
  ])
);

$h1 = $item_title;
if (
  isset($file_info["display_title"]) &&
  trim($file_info["display_title"]) != $item_title
) {
  $file_title = trim($file_info["display_title"]);
} else {
  $file_title = "";
}

if ($file_title && $file_title) {
  $h1 .= trim($microsite->options["titles_separator"]) . " " . $file_title;
}
?>
<div class="row g-0">
  <nav id="breadcrumb" class="col-span-12">
<?php echo $breadcrumb; ?>
  </nav>
  <div class="col-span-12">
    <h1><?php echo $h1; ?></h1>
  </div><!-- end col-span-12 -->
  <div class="stage-and-thumbs-wrapper">
  <div id="stage" class="stage>
    <figure>
      <div class="image-wrapper">
      <?php echo file_image(
        "thumbnail",
        ["class" => "lazy thumbnail"],
        $active_file
      ); ?></div><!-- end .image-wrapper -->
    </figure>
  </div><!-- end #stage -->
<?php if (count($files) > 1): ?>
  <nav id="file-thumbnails">
    <h2><?php echo __("Item Files"); ?></h2>
    <div class="thumbnails">
  <?php foreach ($files as $key => $file):
    set_current_record("file", $file, $item); ?>
    <a class="<?php if (
      $file->id == $active_file->id
    ): ?>active<?php endif; ?>" href="<?php echo $thumb_links_base .
  "/file/" .
  $file->id; ?>"><?php echo file_image("square_thumbnail", [
  "class" => "lazy",
]); ?></a>
<?php
  endforeach; ?>
  </div><!-- end .thumbnails -->
  </nav><!-- end #file-thumbnails -->

<?php endif; ?>
</div><!-- end .stage-and-thumbs-wrapper -->
<div class="d-flex g-0 gap-3 py-5">
  <div class="col-span-12 col-xl-8">
    <h2 class="underlined"><?php echo __("Dublin Core"); ?></h2>
    <div id="item-metadata">
        <?php echo all_element_texts("item", [
          "show_empty_elements" => false,
          "show_element_set_headings" => false,
          "show_element_sets" => ["Dublin Core", "Item Type Metadata"],
        ]); ?>
    </div>
  </div><!-- end #dublin-core -->
  <div id="citation" class="col-span-12 col-xl-4">

    <?php if (metadata("item", "Collection Name")): ?>
      <h2 class="underlined"><?php echo __("Collection"); ?></h2>
        <div id="collection" class="element">
          <div class="element-text"><a href="
          <?php echo url(
            [
              "action" => "browse",
              "controller" => "item",
              "slug" => $params->slug,
            ],
            "ems_collection",
            ["collection" => $collection->id]
          ); ?>"><?php echo $collection_title; ?></a>
                </div>
        </div>
     <?php endif; ?>

     <h2 class="underlined"><?php echo __("Format Metadata"); ?></h2>

     <?php if ($file_info["title"]): ?>
     <div class="element">
     <h3><?php echo __("File Title"); ?></h3>
     <div class="element-text"><?php echo $file_info["title"]; ?></div>
     </div><!-- end .element -->
     <?php endif; ?>



     <?php if (metadata($active_file, "original_filename")): ?>
      <div class="element">
      <h3><?php echo __("Original Filename"); ?></h3>
      <div class="element-text"><?php echo metadata(
        $active_file,
        "original_filename"
      ); ?></div>
      </div><!-- end .element -->

      <div class="element">
      <h3><?php echo __("File Size"); ?></h3>
      <div class="element-text"><?php
      $size = metadata($active_file, "size");
      $size = convert_to_readable_size($size);
      echo $size;
      ?></div>
      </div><!-- end .element -->

      <div class="element">
      <h3><?php echo __("Authentication"); ?></h3>
      <div class="element-text"><?php echo metadata(
        $active_file,
        "authentication"
      ); ?></div>
      </div><!-- end .element -->
     <?php endif; ?>
     <h2 class="underlined"><?php echo __("Type Metadata"); ?></h2>
      <?php if (metadata($active_file, "mime_type")): ?>
       <div class="element">
       <h3><?php echo __("Mime Type"); ?></h3>
       <div class="element-text"><?php echo metadata(
         $active_file,
         "mime_type"
       ); ?></div>
       </div><!-- end .element -->
      <?php endif; ?>
      <?php if (metadata($active_file, "type_os")): ?>
       <div class="element">
       <h3><?php echo __("File Type/OS"); ?></h3>
       <div class="element-text"><?php echo metadata(
         $active_file,
         "type_os"
       ); ?></div>
       </div><!-- end .element -->
      <?php endif; ?>

     <!-- Download Link. -->
          <div id="file-download" class="element">
              <h2 class="underlined"><?php echo __("Download"); ?></h2>
              <div class="element-text download-button">
                <h3><a id="download" class="btn btn-solid btn-sage color-white" href="<?php echo file_display_url(
                  $active_file,
                  "fullsize"
                ); ?>" download><?php echo __(
  "Download"
); ?> [<?php echo $size; ?>]</a></h3></div>
              </div>


    <!-- The following prints a citation for this item. -->
     <div id="item-citation" class="element">
         <h2 class="underlined"><?php echo __("Citation"); ?></h2>
         <div class="element-text"><?php echo metadata("item", "citation", [
           "no_escape" => true,
         ]); ?></div>
         <div class="copy-button">
           <h3><a id="copy-to-clipboard" data-success="<?php echo __(
             "Copied!"
           ); ?>" data-error="<?php echo __(
  "Browser not allowing copy to clipboard."
); ?>" href="#" class="btn btn-solid btn-sage color-white"><?php echo __(
  "Copy citation to clipboard"
); ?></a></h3></div>
         </div>
     </div>
  </div><!-- end #dublin-core -->
<p><a class="return-to" href="<?php echo $prevData["uri"]; ?>"><?php echo __(
  "Return to %s",
  $prevData["title"]
); ?></a>
</p>
<?php echo foot(); ?>


