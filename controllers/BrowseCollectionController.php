<?php
use ExhibitMicrosite\Helpers\ParamsHelper;
use ExhibitMicrosite\Helpers\ExhibitMicrositeHelper;
use ExhibitMicrosite\Helpers\BreadcrumbHelper;
use ExhibitMicrosite\Helpers\NavHelper;

class ExhibitMicrosite_BrowseCollectionController extends
  Omeka_Controller_AbstractActionController
{
  public $exhibit;
  public $exhibitPage;
  public $microsite_options;
  public $total_results;
  public $total_page_results;
  public $active_filters;
  public $filters_set;
  public function init()
  {
    if (!isset($_SESSION)) {
      session_start();
    }

    if (isset($_POST["filters"])) {
      if ($_POST["filters"]["action"] == "set") {
        $_SESSION["filters"] = $_POST["filters"];
      } elseif ($_POST["filters"]["action"] == "clear") {
        unset($_SESSION["filters"]);
      }
    }

    if (isset($_SESSION["filters"]) && is_array($_SESSION["filters"])) {
      foreach ($_SESSION["filters"] as $key => $data) {
        if (is_array($data)) {
          $_SESSION["filters"][$key] = array_unique($data);
        }
      }
    }

    $this->filters_set = false;

    /**
     * If the user has come to this page via a link with the collection_id
     * query param, update the session filter variables.
     * Only do this if the id is numeric.
     */
    if (isset($_GET["collection"])) {
      if (is_numeric($_GET["collection"])) {
        $_SESSION["filters"]["collection"][] = $_GET["collection"];
      }
    }

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
        ->findBySlug("browse", ["exhibit_id" => $this->exhibit->id]);
    }

    $this->breadcrumb = new BreadcrumbHelper([
      "route" => $this->route,
      "exhibit" => $this->exhibit,
    ]);

    $this->theme_options = $this->exhibit->getThemeOptions();

    $this->microsite = new ExhibitMicrositeHelper([
      "route" => $this->route,
      "exhibit" => $this->exhibit,
    ]);

    $this->set_total_results();
    $this->set_total_page_results();
    $this->microsite->setTotalPages($this->total_results);

    // If the collection,creator, or item_type_id filters are set, move them into
    // the $active_filters array.
    if (
      isset($_SESSION["filters"]["collection"]) &&
      count($this->microsite->options["collections"]) > 1
    ) {
      $this->active_filters["collection"] = $_SESSION["filters"]["collection"];
      $this->filters_set = true;
    } else {
      $this->active_filters["collection"] = [];
    }

    if (isset($_SESSION["filters"]["creator"])) {
      $this->active_filters["creator"] = $_SESSION["filters"]["creator"];
      $this->filters_set = true;
    } else {
      $this->active_filters["creator"] = [];
    }

    if (isset($_SESSION["filters"]["item_type"])) {
      $this->active_filters["item_type"] = $_SESSION["filters"]["item_type"];
      $this->filters_set = true;
    } else {
      $this->active_filters["item_type"] = [];
    }
  }

  public function browseAction()
  {
    $this->init();

    $this->nav = new NavHelper([
      "exhibit" => $this->exhibit,
      "exhibitPage" => $this->exhibitPage,
      "route" => $this->route,
    ]);

    $creator_filter_data = $this->microsite->itemsFilterData("Creator");
    $item_type_filter_data = $this->microsite->itemTypesFilterData();

    $this->view->exhibitPage = $this->exhibitPage;

    echo $this->view->partial("collection/browse.php", [
      "breadcrumb" => $this->breadcrumb->html,
      "canonicalURL" => $this->microsite->canonicalURL($this->route),
      "exhibit" => $this->exhibit,
      "exhibitPage" => $this->exhibitPage,
      "params" => $this->params,
      "microsite_options" => $this->microsite->options,
      "collection_filter_data" => $this->microsite->options["collections"],
      "route" => $this->route,
      "theme_options" => $this->theme_options,
      "exhibit_theme_options" => $this->exhibit->getThemeOptions(),
      "creator_filter_data" => $creator_filter_data,
      "item_type_filter_data" => $item_type_filter_data,
      "items" => $this->collectionItems(),
      "view" => $this->view,
      "total_pages" => $this->microsite->total_pages,
      "result_set_string" => $this->result_set_string(),
      "per_page" => $this->microsite->per_page,
      "pagination" => $this->microsite->paginate(),
      "refUri" => $this->microsite->refUri,
      "prevData" => $this->breadcrumb->prevData,
      "active_filters" => $this->active_filters,
      "filters_set" => $this->filters_set,
      "microsite" => $this->microsite,
      "nav" => $this->nav,
    ]);
    exit();
  }

  public function result_set_string()
  {
    // Set the start value.
    if ($this->params->page_number == 1) {
      $start = 1;
    } else {
      if (
        $this->total_results <
        $this->microsite->per_page * $this->params->page_number
      ) {
        $multiplier = $this->params->page_number - 1;
        $start = $multiplier * $this->microsite->per_page + 1;
      } else {
        $start = ($this->params->page_number - 1) * $this->microsite->per_page;
        $start++;
      }
    }

    // Set the end value.
    if ($this->params->page_number == $this->microsite->total_pages) {
      $end = $this->total_results;
    } else {
      $end = $this->params->page_number * $this->microsite->per_page;
    }

    return __(
      "Browsing items %d - %d of the %d found.",
      $start,
      $end,
      $this->total_results
    );
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
      $sql = "
          SELECT {$db->prefix}collections.`id`,{$db->prefix}collections.`public`, {$db->prefix}items.`id`
          FROM {$db->prefix}items
          ";

      $sql .= "LEFT OUTER JOIN {$db->prefix}collections ON {$db->prefix}collections.id = {$db->prefix}items.collection_id ";
      $sql .=
        "
          WHERE 1
          AND {$db->prefix}collections.`public` = 1
          AND {$db->prefix}items.`public` = 1
          " .
        $this->microsite->columnInSql(
          "collection_id",
          $this->microsite->options["collection_id"]
        );

      $sql .= $this->_collectionFilterSelect(
        "{$db->prefix}items.collection_id"
      );
      $sql .= $this->_collectionCreatorFilterSelect("{$db->prefix}items.id");

      $sql .= $this->_itemTypeFilterSelect("{$db->prefix}items.item_type_id");

      $sql .= $limit;

      $rows = $db->getTable("Item")->fetchAll($sql);

      return array_column($rows, "id");
    }
    return $data;
  }

  /**
   * Returns a clause to filter query by collection ids saved to session.
   * @param string $column column name to use in query.
   * @return string
   */
  protected function _collectionFilterSelect($column)
  {
    if (isset($_SESSION["filters"]["collection"])) {
      if (!allNumeric($_SESSION["filters"]["collection"])) {
        return "";
      }
      $ids = implode(",", $_SESSION["filters"]["collection"]);
      return " AND {$column} IN({$ids})";
    }
    return "";
  }

  /**
   * Returns a clause to filter query by collection creators saved to o session.
   * @param string $column column name to use in query.
   * @return string
   */
  protected function _collectionCreatorFilterSelect($column)
  {
    $ids = $this->_creatorItemIds();
    if (!empty($ids)) {
      $ids = implode(",", $ids);
      return " AND {$column} IN({$ids})";
    }
    return "";
  }

  /**
   * Returns an array of item ids that have been assigned the creator found in
   * the submitted filters.
   * @return array
   */
  protected function _creatorItemIds()
  {
    if (isset($_SESSION["filters"]["creator"])) {
      if (!allNumeric($_SESSION["filters"]["creator"])) {
        return "";
      }
      $ids = implode(",", $_SESSION["filters"]["creator"]);

      $db = get_db();
      $sql = "SELECT `record_id` FROM `{$db->prefix}element_texts`
              WHERE 1
              AND `record_type` = 'Item'
              AND `text` IN(SELECT text FROM `{$db->prefix}element_texts` WHERE `id` IN ({$ids}))
              ORDER BY `text` ASC
              ";
      $rows = $db->getTable("ElementText")->fetchAll($sql);
      return array_column($rows, "record_id");
    }
  }

  /**
   * Returns a clause to filter query by item_type ids saved to session.
   * @param string $column column name to use in query.
   * @return string
   */
  protected function _itemTypeFilterSelect($column)
  {
    if (isset($_SESSION["filters"]["item_type"])) {
      if (!allNumeric($_SESSION["filters"]["item_type"])) {
        return "";
      }
      $ids = implode(",", $_SESSION["filters"]["item_type"]);
      return " AND {$column} IN({$ids})";
    }
    return "";
  }
} // End CollectionController class
