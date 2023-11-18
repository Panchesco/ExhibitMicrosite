<?php
$files = [];
$first = SCWaterBlockFirstFile($attachments, $block_id);
$class = $block->layout;
$colors = " bg-" . str_replace("#", "", $options["background-color"]);
$colors .= " color-" . str_replace("#", "", $options["color"]);
$stretch = $options["col_stretch"] == "stretch" ? " h-100" : "";
?>


<?php if (1 == 0): ?>
<div class="water-gallery row col-xl-<?php echo options["col_span"]; ?> pb-5">
  <div class="col-xl-8 stage-col p-4">
    <div class="row align-items-center">

      <div style="height:<?php echo $options[
        "stage_height"
      ]; ?>" id="stage-<?php echo $block->id; ?>" class="stage col-12 align-items-center d-flex loading">
        <div class="water-display"></div>

        <div role="status" class="rb-loader">
           <div class="loader-wrapper">
            <div class="visually-hidden loading-message">Loading…</div>
            <span></span>
            <span></span>
            <span></span>
        </div>
        <!-- /.loader-wrapper -->
          </div>
          <!-- /.rb-loader -->

          <nav class="prev-next">
            <div><a href="#prev" class="prev"><i class="ua-brand-left-corner"></i></a></div>
            <div><a href="#next" class="next"><i class="ua-brand-right-corner"></i></a></div>
          </nav>

      </div><!-- end #stage -->
        <div class="thumbs"><?php echo gallery_thumbs(
          $attachments,
          $options
        ); ?>
    </div>
    </div><!-- end .row -->
  </div><!-- end col-8 water-gap -->
  <div class="caption-wrapper col-xl-4">
    <div class="caption p-4 g-0 h-100">
    <?php echo gallery_starter_caption($attachments, $options); ?>
    </div>
  </div>
</div><!-- end .water-gallery -->

<?php endif; ?>

<div class="water-gallery row col-xl-<?php echo $options["col_span"]; ?> pb-5" >
  <div class="col-xl-8 stage-col p-4">
    <div class="row align-items-center">

      <div style="height:600px; ;overflow:hidden;overflow-x:scroll;" id="stage-<?php echo $block->id; ?>" class="stage col-12 align-items-center d-flex loading >

          <?php foreach ($attachments as $attachment): ?>

            <?php $file = get_record_by_id("File", $attachment->file_id); ?>
            <img src="/files/thumbnails/<?php echo $file[
              "filename"
            ]; ?>" style="height:100%;opacity:1;border: solid 4px transparent;"/>


          <?php endforeach; ?>




<?php if (1 == 1): ?>
        <div role="status" class="rb-loader">
           <div class="loader-wrapper">
            <div class="visually-hidden loading-message">Loading…</div>
            <span></span>
            <span></span>
            <span></span>
        </div>
        <!-- /.loader-wrapper -->
          </div>
          <!-- /.rb-loader -->
          <?php endif; ?>

          <nav class="prev-next">
            <div><a href="#prev" class="prev"><i class="ua-brand-left-corner"></i></a></div>
            <div><a href="#next" class="next"><i class="ua-brand-right-corner"></i></a></div>
          </nav>

      </div><!-- end #stage -->
        <div class="thumbs"><?php echo gallery_thumbs(
          $attachments,
          $options
        ); ?>
    </div>
    </div><!-- end .row -->
  </div><!-- end col-8 water-gap -->
  <div class="caption-wrapper col-xl-4">
    <div class="caption p-4 g-0 h-100">
    </div>
  </div>
  </div
<!-- end .water-gallery -->
