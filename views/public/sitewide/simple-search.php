
<div class="container">
    <form id="simple-search" name="simple-search">
        <div class="row">
            <div class="col-12">
                <nav id="breadcrumb" class="w-100">
                <?php echo $breadcrumb; ?>
                </nav><!-- end .breadcrumb -->
            </div>
             <div class="col-10 col-lg-11">
                <h2 class="h1"><?php echo __("Search"); ?></h2>
             </div><!-- end .col-11 -->
             <div class="col-2 col-lg-1 d-flex align-items-top">
                <a href="#" class="ems-closer icon" title="<?php echo __(
                  "Close Search"
                ); ?>"></a>
              </div><!-- end .col-11 -->
              <div class="col-12">
                <?php echo $this->form(
                  "search-form",
                  $options["form_attributes"]
                ); ?>
                    <?php echo $this->formText("query", $filters["query"], [
                      "title" => __("Search"),
                      "placeholder" => __("Search"),
                    ]); ?>
                    <?php if ($options["show_advanced"]): ?>
                    <div id="advanced-form">
                        <fieldset id="query-types">
                            <legend><?php echo __(
                              "Search using this query type:"
                            ); ?></legend>
                            <?php echo $this->formRadio(
                              "query_type",
                              $filters["query_type"],
                              null,
                              $query_types
                            ); ?>
                        </fieldset>
                        <?php if ($record_types): ?>
                        <fieldset id="record-types">
                            <legend><?php echo __(
                              "Search only these record types:"
                            ); ?></legend>
                            <?php foreach ($record_types as $key => $value): ?>
                            <?php echo $this->formCheckbox(
                              "record_types[]",
                              $key,
                              in_array($key, $filters["record_types"])
                                ? [
                                  "checked" => true,
                                  "id" => "record_types-" . $key,
                                ]
                                : null
                            ); ?> <?php echo $this->formLabel(
   "record_types-" . $key,
   $value
 ); ?><br>
                            <?php endforeach; ?>
                        </fieldset>
                        <?php elseif (is_admin_theme()): ?>
                            <p><a href="<?php echo url(
                              "settings/edit-search"
                            ); ?>"><?php echo __(
  "Go to search settings to select record types to use."
); ?></a></p>
                        <?php endif; ?>
                        <p><?php echo link_to_item_search(
                          __("Advanced Search (Items only)")
                        ); ?></p>
                    </div>
                    <?php else: ?>
                        <?php echo $this->formHidden(
                          "query_type",
                          $filters["query_type"]
                        ); ?>
                        <?php foreach ($filters["record_types"] as $type): ?>
                        <?php echo $this->formHidden(
                          "record_types[]",
                          $type
                        ); ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php echo $this->formButton(
                      "submit_search",
                      $options["submit_value"],
                      ["type" => "submit"]
                    ); ?>
                </form>
              </div>
        </div><!-- end .row  -->
  </form><!-- end #simple-search -->
</div><!-- end .container -->
