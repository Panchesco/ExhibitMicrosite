<?php
namespace ExhibitMicrosite\Helpers;
use ExhibitMicrosite\Helpers\ParamsHelper;
use Zend_Controller_Front;
class ExhibitMicrositeHelper
{
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

    // Get page number if there is one.
    if (!isset($config["page_number"])) {
      if ($this->params->page_number) {
        $this->page_number = $this->params->page_number;
      } else {
        $this->page_number = 1;
      }
    } else {
      $this->page_number = $config["page_number"];
    }

    // Get the exhibit theme options.
    $this->theme_options = $this->exhibit->theme_options;

    // Set the breadcrumb data
    $this->setBreadcrumbData();
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
      $this->breadcrumb_data[] = [
        "title" => __("Items"),
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
  }

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
} // End MicrositeHelper class.
