<div class="table-responsive">
    <table class="full">
        <thead>
            <tr>
                <?php echo browse_sort_links(
                  [
                    __("Exhibit") => "title",
                    __("Last Modified") => "updated",
                  ],
                  ["link_tag" => 'th scope="col"', "list_tag" => ""]
                ); ?>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($options as $option): ?>
            <tr>
                <td>
                    <span class="title">
                        <a href="<?php echo WEB_ROOT; ?>/exhibits/show/<?php echo $option[
  "exhibit_slug"
]; ?>" target="_blank"><?php echo $option["title"]; ?></a>
                    </span>
                    <ul class="action-links group">
                        <li><a class="edit" href="/admin/exhibit-microsite/index/edit/id/<?php echo $option[
                          "id"
                        ]; ?>">
                            <?php echo __("Edit"); ?>
                        </a></li>
                        <li><a class="delete-confirm" href="/admin/exhibit-microsite/index/delete-confirm/id/<?php echo $option[
                          "id"
                        ]; ?>">
                            <?php echo __("Delete"); ?>
                        </a></li>
                    </ul>
                </td>
                <td><strong><?php
                echo $option["modified_by_username"] .
                  "</strong> " .
                  __("on") .
                  " ";
                echo $option["updated"]
                  ? html_escape(
                    format_date($option["updated"], Zend_Date::DATETIME_SHORT)
                  )
                  : "";
                ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
