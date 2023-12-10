<?php
namespace ExhibitMicrosite\Helpers;
use ExhibitMicrosite\Helpers\ParamsHelper;
use Zend_Controller_Front;
class ExhibitMicrositeHelper
{
  public $action;
  public $controller;
  public $options;
  public $index;
  public $per_page;
  public $total_rows;
  public $total_pages;
  public $refUri;
  function __construct($config)
  {
    $this->config = $config;
    $this->request = Zend_Controller_Front::getInstance()->getRequest();
    $this->action = isset($config["action"]) ? $config["action"] : "show";
    $this->controller = isset($config["controller"])
      ? $config["controller"]
      : "default";

    // Set the route here, passed from the controller using this helper.
    $this->route = isset($config["route"])
      ? $config["route"]
      : "ems_exhibitLanding";

    $this->params = new ParamsHelper();

    //echo $this->refUri;

    // Make sure we have the current exhibit.
    if (!isset($config["exhibit"])) {
      $this->exhibit = get_record("Exhibit", [
        "slug" => $this->params->slug,
        "public" => 1,
      ]);
    } else {
      $this->exhibit = $config["exhibit"];
    }

    // Get first exhibitPage if there is one.
    if ($this->params->page_slug_1) {
      $this->exhibitPage = get_record("ExhibitPage", [
        "slug" => $this->params->page_slug_1,
        "exhibit_id" => $this->exhibit->id,
      ]);
      $this->exhibitPages[] = $this->exhibitPage;
    }

    // Get second exhibitPage if there is one.
    if ($this->params->page_slug_2) {
      $this->exhibitPage = get_record("ExhibitPage", [
        "slug" => $this->params->page_slug_2,
        "exhibit_id" => $this->exhibit->id,
      ]);
      $this->exhibitPages[] = $this->exhibitPage;
    }

    // Get third exhibitPage if there is one.
    if ($this->params->page_slug_3) {
      $this->exhibitPage = get_record("ExhibitPage", [
        "slug" => $this->params->page_slug_3,
        "exhibit_id" => $this->exhibit->id,
      ]);
      $this->exhibitPages[] = $this->exhibitPage;
    }

    // Get item object if there is one.
    if (!isset($config["item"])) {
      if ($this->params->item_id) {
        $this->item = get_record("Item", [
          "slug" => $this->params->item_id,
          "public" => 1,
        ]);
      } else {
        $this->item = null;
      }
    } else {
      $this->item = $config["item"];
    }

    // Get file object if there is one.
    if (!isset($config["file"])) {
      if ($this->params->file_id) {
        $this->file = get_record("File", [
          "slug" => $this->params->file_id,
          "public" => 1,
        ]);
      }
    } else {
      $this->file = $config["file"];
    }

    // Get collection object if there is one.
    if (!isset($config["collection"])) {
      if ($this->params->collection_id) {
        $this->item = get_record("Collection", [
          "slug" => $this->params->collection_id,
          "public" => 1,
        ]);
      } else {
        $this->collection = null;
      }
    } else {
      $this->collection = $config["collection"];
    }

    // Get the exhibit theme options.
    $this->theme_options = $this->exhibit->theme_options;

    $this->options = $this->_setOptions();

    $this->_setPerPage();
    $this->_setIndex();
    $this->setRefUri();
  }

  /**
   * Set the db index integer for query limit clause
   * from the current page number.
   */
  protected function _setIndex()
  {
    if ($this->params->page_number) {
      if ($this->params->page_number > 0) {
        $this->index = ($this->params->page_number - 1) * $this->per_page;
      } else {
        $this->index = 0;
      }
    } else {
      $this->index = 0;
    }
  }

  /**
   * Set the per page option from the saved microsite options
   * or, if that hasn't been set, use the default Omeka setting.
   */
  protected function _setPerPage()
  {
    if (
      !isset($this->options["per_page"]) ||
      empty($this->options["per_page"])
    ) {
      $this->per_page = get_option("per_page_public");
    } else {
      $this->per_page = $this->options["per_page"];
    }
  }

  /**
   * Return a count of how many pages exist for the current total results
   * @param $total_rows integer
   * @return integer
   */
  public function setTotalPages($total_rows)
  {
    $this->total_pages = ceil(intval($total_rows) / intval($this->per_page));
  }

  function urlArray()
  {
    $data = [];
    if ($this->params->slug) {
      $data["landing"] = [
        "url" => url(
          ["action" => "show", "slug" => $this->exhibit->slug],
          "ems_exhibitLanding"
        ),
        "title" => $this->exhibit->title,
      ];
    }

    if ($this->params->page_slug_1) {
      $data["page_1"] = [
        "url" => url(
          [
            "action" => "show",
            "slug" => $this->exhibit->slug,
            "page_slug_1" => $this->params->page_slug_1,
          ],
          "ems_exhibitPage1"
        ),
        "title" => get_record("ExhibitPage", ["slug" => $this->page_slug_1])
          ->title,
      ];
    }

    if ($this->page_slug_2) {
      $data["page_2"] = [
        "url" => url(
          [
            "action" => "show",
            "slug" => $this->exhibit->slug,
            "page_slug_1" => $params->page_slug_1,
            "page_slug_2" => $this->page_slug_2,
          ],
          "ems_exhibitPage2"
        ),
        "title" => get_record("ExhibitPage", ["slug" => $this->page_slug_2])
          ->title,
      ];
    }

    if ($this->page_slug_3) {
      $data["page_3"] = [
        "url" => url(
          [
            "action" => "show",
            "slug" => $this->exhibit->slug,
            "page_slug_1" => $this->page_slug_1,
            "page_slug_2" => $this->page_slug_2,
            "page_slug_3" => $this->page_slug_3,
          ],
          "ems_exhibitPage3"
        ),
        "title" => get_record("ExhibitPage", ["slug" => $this->page_slug_3])
          ->title,
      ];
    }
    return $data;
  }

  /**
   * Returns a canonical URL for the current page.
   * @return string
   */
  public function canonicalURL($route = null)
  {
    switch ($route) {
      // Microsite Exhibit Pages
      case "ems_exhibit_page1":
        $url = url(
          [
            "action" => "show",
            "controller" => "exhibitpage",
            "slug" => $this->params->slug,
            "page_slug_1" => $this->params->page_slug_1,
          ],
          $route
        );
        break;

      case "ems_exhibit_page2":
        $url = url(
          [
            "action" => "show",
            "controller" => "exhibitpage",
            "slug" => $this->params->slug,
            "page_slug_1" => $this->params->page_slug_1,
            "page_slug_2" => $this->params->page_slug_2,
          ],
          $route
        );
        break;

      case "ems_exhibit_page3":
        $url = url(
          [
            "action" => "show",
            "controller" => "exhibitpage",
            "slug" => $this->params->slug,
            "page_slug_1" => $this->params->page_slug_1,
            "page_slug_2" => $this->params->page_slug_2,
            "page_slug_3" => $this->params->page_slug_3,
          ],
          $route
        );
        break;

      // Microsite Exhibit Pages Show Item
      case "ems_show_file1":
        $url = url(
          [
            "action" => "show",
            "controller" => "item",
            "slug" => $this->params->slug,
            "item_id" => $this->params->item_id,
            "file_id" => $this->params->file_id,
          ],
          $route
        );
        break;

      case "ems_show_file2":
        $url = url(
          [
            "action" => "show",
            "controller" => "item",
            "slug" => $this->params->slug,
            "item_id" => $this->params->item_id,
            "file_id" => $this->params->file_id,
          ],
          $route
        );
        break;

      case "ems_show_file3":
        $url = url(
          [
            "action" => "show",
            "controller" => "item",
            "slug" => $this->params->slug,
            "item_id" => $this->params->item_id,
            "file_id" => $this->params->file_id,
          ],
          $route
        );
        break;

      // Microsite Show Item Page
      case "ems_show_file":
        $url = url(
          [
            "action" => "show",
            "controller" => "item",
            "slug" => $this->params->slug,
            "item_id" => $this->params->item_id,
            "file_id" => $this->params->file_id,
          ],
          "ems_show_file" . $params->depth
        );
        break;
      // Collection

      case "ems_collection":
        $url = url(
          [
            "action" => "show",
            "controller" => "collection",
            "slug" => $this->params->slug,
            "collection_id" => $this->params->collection_id,
          ],
          "ems_collection"
        );
        break;

      case "ems_collection_item":
        $url = url(
          [
            "action" => "show",
            "controller" => "item",
            "slug" => $this->params->slug,
            "collection_id" => $this->params->collection_id,
            "item_id" => $this->params->item_id,
          ],
          $route
        );
        break;

      // Microsite Landing Page
      default:
        $url = url(
          [
            "action" => "show",
            "controller" => "default",
            "slug" => $this->params->slug,
          ],
          $route
        );
        break;
    }

    return WEB_ROOT . $url;
  }

  protected function _setOptions()
  {
    if (!$this->exhibit) {
      if ($this->slug) {
        $this->exhibit = get_record("Exhibit", [
          "slug" => $this->params->slug,
          "public" => 1,
        ]);
      } else {
        return;
      }
    }

    // Get the microsite options saved in the omeka options table.
    $data = [];
    $db = get_db();
    $sql =
      "SELECT `name`,`value` FROM `{$db->prefix}options` WHERE 1 AND `name` = 'exhibit_microsite[" .
      $this->exhibit->id .
      "]'";
    $row = $db->getTable("Option")->fetchRow($sql);

    // If the option row is found, format the options, including human friendly Collections info.
    if ($row) {
      $row = maybe_unserialize($row["value"]);
      $row["collections"] = [];
      $row["exhibit_id"] = isset($row["exhibit_id"])
        ? $row["exhibit_id"]
        : null;
      $row["collection_id"] =
        isset($row["collection_id"]) && is_array($row["collection_id"])
          ? $row["collection_id"]
          : [];
      $ids = !empty($row["collection_id"])
        ? " AND id IN(" . implode(",", $row["collection_id"]) . ")"
        : "";

      // Get the public Collections associated with the microsite exhibit.
      $sql = "
         SELECT et.record_id AS collection_id,et.text as title,et.html FROM `{$db->prefix}elements` e
         LEFT OUTER JOIN `{$db->prefix}element_texts` et ON et.element_id = e.id
         WHERE 1
         AND e.name = 'Title'
         AND et.record_type = 'Collection'
         AND et.record_id IN (SELECT id FROM `{$db->prefix}collections` WHERE 1 {$ids} AND public = 1)
         ORDER BY et.text ASC
         ";

      $rows = $db->getTable("Collection")->fetchAll($sql);
      foreach ($rows as $collection) {
        if ($collection["html"] == 1) {
          $collection["title"] = strip_tags($collection["title"]);
        }
        $row["collections"][] = $collection;
      }

      return $row;
    }
    return;
  }

  /**
   * Returns array of Dublin Core element_name values for
   * the current microsite collections
   * @return array
   */
  public function itemsFilterData($element_name)
  {
    $db = get_db();
    $element_name = strtolower($element_name);

    if (isset($this->options["collection_id"])) {
      if (
        is_array($this->options["collection_id"]) &&
        !empty($this->options["collection_id"])
      ) {
        $collection_clause =
          " AND {$db->prefix}items.collection_id IN(" .
          implode(",", $this->options["collection_id"]) .
          ") ";
      } else {
        $collection_clause = "";
      }
    } else {
      $collection_clause = "";
    }

    $sql = "
     SELECT {$db->prefix}element_texts.`id`,{$db->prefix}element_texts.`text` AS {$element_name} FROM `{$db->prefix}elements`
     LEFT OUTER JOIN `{$db->prefix}element_texts` ON {$db->prefix}element_texts.element_id = {$db->prefix}elements.id
     LEFT OUTER JOIN `{$db->prefix}items`  on {$db->prefix}items.`id` = {$db->prefix}element_texts.record_id
     LEFT OUTER JOIN `{$db->prefix}collections` on {$db->prefix}collections.`id` = {$db->prefix}items.collection_id
     WHERE 1
     AND {$db->prefix}collections.public = 1
     AND {$db->prefix}elements.`name` = '{$element_name}'
     AND {$db->prefix}element_texts.`record_type` = 'Item'
     {$collection_clause}
     ORDER BY {$db->prefix}element_texts.`text` ASC
     ";

    $rows = $db->getTable("ElementText")->fetchAll($sql);

    // Loop through the rows and create a new set of results eliminating duplicates.
    $data = [];
    $return_rows = [];
    foreach ($rows as $row) {
      if (!in_array($row[$element_name], $data)) {
        $return_rows[] = $row;
        $data[] = $row[$element_name];
      }
    }

    return $return_rows;
  }

  /**
   * Returns array of item_type_id => name
   * pairs for items in the current collections
   * @return array
   */
  public function itemTypesFilterData()
  {
    $data = [];
    $db = get_db();

    $collections_clause = $this->columnInSql(
      "i.collection_id",
      $this->options["collection_id"]
    );
    $sql = "
      SELECT it.`id` AS item_type_id,it.`name` AS item_type FROM {$db->prefix}item_types it
      LEFT OUTER JOIN {$db->prefix}items i ON i.`item_type_id` = it.`id`
      LEFT OUTER JOIN {$db->prefix}collections ON {$db->prefix}collections.id = i.collection_id
      WHERE 1
      AND {$db->prefix}collections.public = 1
      AND i.public = 1
      ";
    $sql .= $collections_clause;
    $sql .= "
      GROUP BY (it.id)
      ORDER BY it.name ASC
      ";
    return $db->getTable("ElementTypes")->fetchAll($sql);
  }

  /**
   * Format a db.column IN(array of values) sql
   * @param string $column db column name
   * @param array $data array of values
   * @return string
   */
  public function columnInSql($column, $data)
  {
    if (!empty($data)) {
      return " AND {$column} IN(" . implode(",", $data) . ") ";
    }
    return "";
  }

  /**
   * Create the limit clause for a query.
   * @param $index integer
   * @param $offest integer
   * @return string.
   */
  public function limitSql($index = 0, $offset = 999999)
  {
    return " LIMIT {$index},{$offset}";
  }

  /**
   * Format a db.column IN(array of values) sql
   * @param string $column db column name
   * @param array $data array of values
   * @return string
   */
  public function elementId($name, $element_set_name = "Dublin Core")
  {
    $db = get_db();
    $sql = "
    SELECT `id` FROM {$db->prefix}elements
    WHERE 1 AND {$db->prefix}elements.name = '{$name}'
    AND {$db->prefix}elements.element_set_id = (SELECT `id` FROM {$db->prefix}element_sets WHERE `name` = '{$element_set_name}'  LIMIT 1 )
    ";

    $row = $db->getTable("Element")->fetchRow($sql);
    if ($row) {
      return $row["id"];
    }
    return null;
  }

  /**
   * Returns an array of data for creating pagination links.
   * @params array [current => current_page_number, max => total pages]
   * https://www.zacfukuda.com/blog/pagination-algorithm
   */
  public function paginate()
  {
    if (!isset($this->params->page_number) || !isset($this->total_pages)) {
      return null;
    }

    $current = $this->params->page_number;
    $max = $this->total_pages;

    $prev = $current == 1 ? null : $current - 1;
    $next = $current == $max ? null : $current + 1;
    $items = [1];

    if ($current === 1 && $max === 1) {
      return [
        "current" => $current,
        "prev" => $prev,
        "next" => $next,
        "items" => $items,
      ];
    }
    if ($current > 4) {
      array_push($items, "…");
    }

    $r = 2;
    $r1 = $current - $r;
    $r2 = $current + $r;

    for ($i = $r1 > 2 ? $r1 : 2; $i <= min($max, $r2); $i++) {
      array_push($items, $i);
    }

    if ($r2 + 1 < $max) {
      array_push($items, "…");
    }
    if ($r2 < $max) {
      array_push($items, $max);
    }

    return [
      "current" => $current,
      "prev" => $prev,
      "next" => $next,
      "items" => $items,
    ];
  }

  /**
   * Sets the current URL from the current route so we can pass that to pages linking
   * to items and files, objects that may have more than one possible URL.
   */
  public function setRefUri()
  {
    $data["action"] = $this->action;
    $data["controller"] = $this->controller;
    if ($this->params->slug) {
      $data["slug"] = $this->params->slug;
    }
    if ($this->params->page_slug_1) {
      $data["page_slug_1"] = $this->params->page_slug_1;
    }
    if ($this->params->page_slug_2) {
      $data["page_slug_2"] = $this->params->page_slug_2;
    }
    if ($this->params->page_slug_3) {
      $data["page_slug_3"] = $this->params->page_slug_3;
    }

    if ($this->params->collection_id) {
      $data["collection_id"] = $this->params->collection_id;
    }

    if ($this->params->item_id) {
      $data["item_id"] = $this->params->item_id;
    }

    if ($this->params->file_id) {
      $data["file_id"] = $this->params->file_id;
    }
    $this->refUri = url($data, $this->route);
  }
} // End MicrositeHelper class.
