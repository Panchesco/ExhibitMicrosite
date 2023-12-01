<nav class="d-flex justify-content-center" aria-label="<?php echo __(
  "Page navigation"
); ?>">
      <ul class="pagination">
        <?php if ($pagination["prev"]): ?>
        <li class="page-item">
          <a class="page-link" href="<?php echo url(
            [
              "action" => $action,
              "controller" => $controller,
              "page_number" => $pagination["prev"],
            ],
            $route
          ); ?>" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>
        <?php endif; ?>
        <?php foreach ($pagination["items"] as $page): ?>
         <li class="page-item<?php echo $page == $pagination["current"]
           ? " active"
           : ""; ?>">
           <?php if (trim($page) != "…"): ?>
           <a class="page-link" href="<?php echo url(
             [
               "action" => $action,
               "controller" => $controller,
               "page_number" => $page,
             ],
             $route
           ); ?>" aria-label="<?php echo __(
  "Page %d",
  $page
); ?>"><?php echo $page; ?>
           </a>
           <?php else: ?>
           <a class="page-link" href="javascript:void(0);"><?php echo $page; ?></a>
          <?php endif; ?>
         </li>
        <?php endforeach; ?>
        <?php if ($pagination["next"]): ?>
        <li class="page-item">
          <a class="page-link" href="<?php echo url(
            [
              "action" => $action,
              "controller" => $controller,
              "page_number" => $pagination["next"],
            ],
            $route
          ); ?>" aria-label="<?php echo __("Next"); ?>">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
        <?php endif; ?>
      </ul>
    </nav>

