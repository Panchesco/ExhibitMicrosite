<?php
namespace ExhibitMicrosite\Helpers;
use ExhibitMicrosite\Helpers\ParamsHelper;
use Zend_Controller_Front;
class ExhibitMicrositeHelper
{
  public $options;
  public $index;
  public $per_page;
  public $total_rows;
  public $total_pages;
  function __construct($config)
  {
    $this->request = Zend_Controller_Front::getInstance()->getRequest();

    // Set the route here, passed from the controller using this helper.
    $this->route = isset($config["route"])
      ? $config["route"]
      : "ems_exhibitLanding";

    $this->params = new ParamsHelper();

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
    // Set the breadcrumb data
    $this->setBreadcrumbData();
    $this->_setPerPage();
    $this->_setIndex();
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
      case "ems_show_item1":
        $url = url(
          [
            "action" => "show",
            "controller" => "item",
            "slug" => $this->params->slug,
            "item_id" => $this->params->item_id,
          ],
          "ems_show_item"
        );
        break;

      case "ems_show_item2":
        $url = url(
          [
            "action" => "show",
            "controller" => "item",
            "slug" => $this->params->slug,
            "item_id" => $this->params->item_id,
          ],
          "ems_show_item"
        );
        break;

      case "ems_show_item3":
        $url = url(
          [
            "action" => "show",
            "controller" => "item",
            "slug" => $this->params->slug,
            "item_id" => $this->params->item_id,
          ],
          "ems_show_item"
        );
        break;

      // Microsite Show Item Page
      case "ems_show_item":
        $url = url(
          [
            "action" => "show",
            "controller" => "item",
            "slug" => $this->params->slug,
            "item_id" => $this->params->item_id,
          ],
          $route
        );
        break;

      //TODO:
      // Files

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
          "ems_show_file1"
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
          "ems_show_file2"
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
          "ems_show_file3"
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
          "ems_show_file"
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
          "ems_show_item"
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

  /**
   * Returns an array titles and urls
   * for the current page breadcrumb trail.
   * @return array.
   */
  public function setBreadcrumbData()
  {
    $this->breadcrumb_data = [];

    $this->exhbitBreadcrumbData();

    $this->exhibitPagesBreadcrumbData();

    $this->itemsBreadcrumbData();

    $this->collectionsBreadcrumbData();
  } // end function

  public function exhbitBreadcrumbData()
  {
    $this->breadcrumb_data = [];
    if ($this->params->slug) {
      $this->breadcrumb_data[] = [
        "title" => $this->exhibit->title,
        "url" => url(
          [
            "module" => "exhibit-microsite",
            "controller" => "default",
            "action" => "show",
            "slug" => $this->params->slug,
          ],
          "ems_exhibitLanding"
        ),
      ];
    }
  }

  public function exhibitPagesBreadcrumbData()
  {
    if ($this->params->page_slug_1) {
      $this->breadcrumb_data[] = [
        "title" => $this->exhibitPages[0]->title,
        "url" => url(
          [
            "module" => "exhibit-microsite",
            "controller" => "exhibitpage",
            "action" => "show",
            "slug" => $this->params->slug,
            "page_slug_1" => $this->params->page_slug_1,
          ],
          "ems_exhibitPage1"
        ),
      ];
    }

    if ($this->params->page_slug_2) {
      if (isset($this->exhibitPages[1]->title)) {
        $this->breadcrumb_data[] = [
          "title" => $this->exhibitPages[1]->title,
          "url" => url(
            [
              "module" => "exhibit-microsite",
              "controller" => "exhibitpage",
              "action" => "show",
              "slug" => $this->params->slug,
              "page_slug_1" => $this->params->page_slug_1,
              "page_slug_2" => $this->params->page_slug_2,
            ],
            "ems_exhibitPage2"
          ),
        ];
      }
    }

    if ($this->params->page_slug_3) {
      $this->breadcrumb_data[] = [
        "title" => $this->exhibitPages[2]->title,
        "url" => url(
          [
            "module" => "exhibit-microsite",
            "controller" => "exhibitpage",
            "action" => "show",
            "slug" => $this->params->slug,
            "page_slug_1" => $this->params->page_slug_1,
            "page_slug_2" => $this->params->page_slug_2,
            "page_slug_3" => $this->params->page_slug_3,
          ],
          "ems_exhibitPage3"
        ),
      ];
    }
  }

  public function itemsBreadcrumbData()
  {
    if ($this->params->item_id || $this->params->file_id) {
      $item = get_record_by_id("Item", $this->params->item_id);
      set_current_record("item", $item);
      $this->breadcrumb_data[] = [
        "title" => metadata("item", "rich_title", [
          "no_escape" => true,
        ]),
        "url" => url(
          [
            "module" => "exhibit-microsite",
            "controller" => "item",
            "action" => "browse",
            "slug" => $this->params->slug,
          ],
          $this->route
        ),
      ];
    }

    $item = get_record_by_id("Item", $this->params->item_id);
    if (element_exists("Dublin Core", "rich_title")) {
      $title = metadata($item, "rich_title");
    } else {
      $title = null;
    }

    if ($title) {
      $this->breadcrumb_data[] = [
        "title" => $title,
        "url" => url(
          [
            "module" => "exhibit-microsite",
            "controller" => "item",
            "action" => "browse",
            "slug" => $this->params->slug,
          ],
          "ems_browse_items"
        ),
      ];
    }
  } // End itemsBreadcrumbData method

  public function collectionsBreadcrumbData()
  {
    $this->breadcrumb_data = [];

    // Exhibit Landing Page.
    if (in_array($this->route, ["ems_collection", "ems_collection_item"])) {
      $this->breadcrumb_data[] = [
        "title" => $this->exhibit->title,
        "url" => url(
          [
            "module" => "exhibit-microsite",
            "controller" => "default",
            "action" => "show",
            "slug" => $this->params->slug,
          ],
          "ems_exhibitLanding"
        ),
      ];

      // Collection Landing Page
      $this->breadcrumb_data[] = [
        "title" => $this->options["collection_page_title"],
        "url" => url(
          [
            "module" => "exhibit-microsite",
            "controller" => "collection",
            "action" => "browse",
            "slug" => $this->params->slug,
          ],
          "ems_collection"
        ),
      ];

      // Item metadata page.
      if ($this->params->item_id) {
        $item = get_record_by_id("Item", $this->params->item_id);
        set_current_record("item", $item);
        if (metadata("item", "rich_title")) {
          $title = metadata("item", "rich_title");
        } else {
          $title = null;
        }
        $this->breadcrumb_data[] = [
          "title" => $title,
          "url" => url(
            [
              "module" => "exhibit-microsite",
              "controller" => "collection",
              "action" => "browse",
              "slug" => $this->params->slug,
              "collection_id" => $this->params->collection_id,
              "item_id" => $this->params->item_id,
            ],
            "ems_collection"
          ),
        ];
      }

      // print_r("<pre>");
      // print_r($this->options);
      // print_r("</pre>");
    }
  } // End collectionsBreadcrumbData method

  /**
   * Returns HTML
   * for the current page breadcrumb trail.
   * @return array.
   */
  public function breadcrumbHTML()
  {
    $html = "<ul>";
    foreach ($this->breadcrumb_data as $segment) {
      if ($segment != end($this->breadcrumb_data)) {
        $html .=
          '<li><a href="' .
          $segment["url"] .
          '">' .
          $segment["title"] .
          "</a></li>";
      } else {
        $html .= "<li>" . $segment["title"] . "</li>";
      }
    }
    $html .= "</ul>";
    return $html;
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
    $element_name = strtolower($element_name);

    if (isset($this->options["collection_id"])) {
      if (
        is_array($this->options["collection_id"]) &&
        !empty($this->options["collection_id"])
      ) {
        $collection_clause =
          " AND i.collection_id IN(" .
          implode(",", $this->options["collection_id"]) .
          ") ";
      } else {
        $collection_clause = "";
      }
    } else {
      $collection_clause = "";
    }

    $db = get_db();
    $sql = "
     SELECT et.`id`,et.`text` AS {$element_name} FROM `{$db->prefix}elements` e
     LEFT OUTER JOIN `{$db->prefix}element_texts` et ON et.element_id = e.id
     LEFT OUTER JOIN `{$db->prefix}items` i on i.`id` = et.record_id
     WHERE 1
     AND e.`name` = '{$element_name}'
     AND et.`record_type` = 'Item' {$collection_clause}
     GROUP BY et.text
     ORDER BY et.`text` ASC
     ";

    $rows = $db->getTable("ElementText")->fetchAll($sql);

    return $rows;
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
      SELECT it.id AS item_type_id,it.name AS item_type FROM {$db->prefix}item_types it
      LEFT OUTER JOIN {$db->prefix}items i ON i.item_type_id = it.id
      WHERE 1
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
} // End MicrositeHelper class.
