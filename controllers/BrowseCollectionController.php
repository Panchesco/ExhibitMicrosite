<?php

use ExhibitMicrosite\Helpers\ParamsHelper;
use ExhibitMicrosite\Helpers\ExhibitMicrositeHelper;

class ExhibitMicrosite_BrowseCollectionController extends
  Omeka_Controller_AbstractActionController
{
  public $exhibit;
  public $exhibitPage;
  public $microsite_options;
  public $total_results;
  public $total_page_results;

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

    $this->set_total_results();
    $this->set_total_page_results();
    $this->microsite->setTotalPages($this->total_results);

    $this->breadcrumb = $this->microsite->breadcrumbHTML();
  }

  public function browseAction()
  {
    $this->init();

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
      "total_pages" => $this->microsite->total_pages,
      "pagination" => $this->microsite->paginate(),
    ]);
    exit();
  }

  public function collectionItems()
  {
    $limit = $this->microsite->limitSql(
      $this->microsite->index,
      $this->microsite->per_page
    );

    // Get the current page results.
    $item_ids = $this->collectionItemIds($limit);
    // Create a string from the result array.
    $item_ids = implode(",", $item_ids);

    if (!empty($item_ids)) {
      return get_records("Item", [
        "range" => "$item_ids",
        "collection" => $this->microsite->options["collection_id"],
      ]);
    }
    return [];
  }

  /**
   * Set the total number of results found.
   */
  public function set_total_results()
  {
    $this->total_results = count($this->collectionItemIds());
    return;
  }

  /**
   * Set the number of results for the current page.
   */
  public function set_total_page_results()
  {
    $index = $this->microsite->index;
    $offset = $this->microsite->per_page;
    $this->total_page_results = count(
      $this->collectionItemIds(" LIMIT {$index},{$offset}")
    );
  }

  /**
   * Get items for the page meeting the filter criteria.
   * @return array of item_ids
   */
  public function collectionItemIds($limit = "", $filters = [])
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
        );

      $sql .= $limit;

      $rows = $db->getTable("Item")->fetchAll($sql);

      return array_column($rows, "id");
    }
    return $data;
  }
} // End CollectionController class
