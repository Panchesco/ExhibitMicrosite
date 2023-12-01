<?php

use ExhibitMicrosite\Helpers\ParamsHelper;
use ExhibitMicrosite\Helpers\ExhibitMicrositeHelper;

class ExhibitMicrosite_CollectionController extends
  Omeka_Controller_AbstractActionController
{
  public $exhibit;
  public $exhibitPage;
  public $microsite_options;
  public $start = 0;
  public $offset = 3;
  public function init()
  {
    // Set the model class so this controller can perform some functions,
    // such as $this->findById()
    $this->_helper->db->setDefaultModelName("Collection");

    // Set the current exhibit;
    $this->request = Zend_Controller_Front::getInstance()->getRequest();
    $this->route = $this->getFrontController()
      ->getRouter()
      ->getCurrentRouteName();

    $this->params = new ParamsHelper();

    // Set pagination props.
    // if (isset($this->params->page_number)) {
    //   $num = $this->params->page_number - 1;
    //   if ($num >= 0) {
    //     $this->start = $num;
    //   }
    // } else {
    //   $this->start = 0;
    // }

    if (!$this->exhibit) {
      $this->exhibit = $this->_helper->db
        ->getTable("Exhibit")
        ->findBySlug($this->params->slug);
    }

    if (!$this->exhibitPage) {
      $this->exhibitPage = $this->_helper->db
        ->getTable("ExhibitPage")
        ->findBySlug("collection", ["exhibit_id" => $this->exhibit->id]);
    }

    $this->theme_options = $this->exhibit->getThemeOptions();

    $this->view->addScriptPath(
      PUBLIC_THEME_DIR .
        "/" .
        $this->exhibit->theme .
        "/exhibit-microsite/views"
    );

    $this->view->addScriptPath(
      PLUGIN_DIR . "/exhibit-microsite/views/exhibit-pages"
    );

    $this->microsite = new ExhibitMicrositeHelper([
      "route" => $this->route,
      "exhibit" => $this->exhibit,
    ]);

    $this->breadcrumb = $this->microsite->breadcrumbHTML();
  }

  public function browseAction()
  {
    $this->init();

    echo $this->params->page_number . "<br>";
    echo $this->microsite->index . "<br>";
    echo $this->microsite->offset . "<br>";

    $creators_filter_data = $this->microsite->itemsFilterData("Creator");
    $item_types_filter_data = $this->microsite->itemTypesFilterData();

    $this->view->exhibitPage = $this->exhibitPage;
    echo $this->view->partial("collection/browse.php", [
      "breadcrumb" => $this->breadcrumb,
      "canonicalURL" => $this->microsite->canonicalURL($this->route),
      "exhibit" => $this->exhibit,
      "exhibitPage" => $this->exhibitPage,
      "params" => $this->params,
      "microsite_options" => $this->microsite->options,
      "collections_filter_data" => $this->microsite->options["collections"],
      "route" => $this->route,
      "theme_options" => $this->theme_options,
      "creators_filter_data" => $creators_filter_data,
      "item_types_filter_data" => $item_types_filter_data,
      "items" => $this->collectionItems(),
      "view" => $this->view,
      "total_results" => "",
      "items_count" => "",
    ]);
    exit();
  }

  public function collectionItems()
  {
    $item_ids = $this->collectionItemIds();
    $item_ids = implode(",", $item_ids);
    if (!empty($item_ids)) {
      return get_records(
        "Item",
        [
          "range" => $item_ids,
          "collection" => $this->microsite->options["collection_id"],
        ],
        0
      );
    }
    return [];
  }

  /**
   * Get items for the page meeting the filter criteria.
   * @return array of item_ids
   */
  public function collectionItemIds($filters = [])
  {
    $data = [];
    if (!empty($this->microsite->options["collection_id"])) {
      $db = get_db();
      $sql =
        "
    SELECT `id` FROM {$db->prefix}items 
    WHERE 1 
    AND `public` = 1
    " .
        $this->microsite->columnInSql(
          "collection_id",
          $this->microsite->options["collection_id"]
        ) .
        "
    LIMIT " .
        $this->start .
        "," .
        $this->offset .
        "";

      print_r("<pre>");
      print_r($sql);
      print_r("</pre>");

      $rows = $db->getTable("Item")->fetchAll($sql);

      return array_column($rows, "id");
    }
    return $data;
  }
} // End CollectionController class
