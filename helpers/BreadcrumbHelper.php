<?php

namespace ExhibitMicrosite\Helpers;
use ExhibitMicrosite\Helpers\ParamsHelper;
use ExhibitMicrosite\Helpers\ExhibitMicrositeHelper;
use Zend_Controller_Front;

class BreadcrumbHelper
{
  public $config = [];
  public $data = [];
  public $html = "";
  public $exhibitPage_1;
  public $exhibitPage_2;
  public $exhibitPage_3;
  public $prevData = [];

  function __construct($config)
  {
    $this->config = $config;
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

    $this->emsExhibitLanding();
    $this->nonSummaryPage();
    $this->emsExhibitPage1();
    $this->emsExhibitPage2();
    $this->emsExhibitPage3();
    $this->emsCollectionItem();
    $this->emsCollectionItemFile();
    $this->emsShowFile1();
    $this->emsShowFile2();
    $this->emsShowFile3();
    $this->setPrevData();
    $this->html();
  }

  public function emsExhibitLanding()
  {
    if ($this->exhibit->use_summary_page) {
      $this->data[] = [
        "title" => $this->exhibit->title,
        "slug" => $this->exhibit->slug,
        "url" => url(
          [
            "action" => "show",
            "controller" => "default",
            "slug" => $this->exhibit->slug,
          ],
          "ems_exhibitLanding"
        ),
      ];
    }
  }

  public function nonSummaryPage()
  {
    if (!$this->exhibit->use_summary_page) {
      $this->data[] = [
        "title" => $this->exhibit->title,
        "slug" => $this->exhibit->slug,
        "url" => url(
          [
            "action" => "show",
            "controller" => "default",
            "slug" => $this->exhibit->slug,
          ],
          "ems_exhibitLanding"
        ),
      ];
    }
  }

  public function emsExhibitPage1()
  {
    if ($this->params->page_slug_1) {
      $this->exhibitPage_1 = get_record("ExhibitPage", [
        "exhibit_id" => $this->exhibit->id,
        "slug" => $this->params->page_slug_1,
      ]);

      $this->data[] = [
        "atts" => [
          "class" => "ems-trigger",
          "data-target" => "#nav-collections",
        ],
        "title" => $this->exhibitPage_1->title,
        "slug" => $this->exhibitPage_1->slug,
        "url" => url(
          [
            "action" => "show",
            "controller" => "exhibitpage",
            "slug" => $this->exhibit->slug,
            "page_slug_1" => $this->exhibitPage_1->slug,
          ],
          "ems_exhibitPage1"
        ),
      ];
    }
  }

  public function emsExhibitPage2()
  {
    if ($this->params->page_slug_2) {
      $this->exhibitPage_2 = get_record("ExhibitPage", [
        "exhibit_id" => $this->exhibit->id,
        "slug" => $this->params->page_slug_2,
      ]);

      $this->data[] = [
        "atts" => [],
        "title" => $this->exhibitPage_2->title,
        "slug" => $this->exhibitPage_2->slug,
        "url" => url(
          [
            "action" => "show",
            "controller" => "exhibitpage",
            "slug" => $this->exhibit->slug,
            "page_slug_1" => $this->exhibitPage_1->slug,
            "page_slug_2" => $this->exhibitPage_2->slug,
          ],
          "ems_exhibitPage2"
        ),
      ];
    }
  }

  public function emsExhibitPage3()
  {
    if ($this->params->page_slug_3) {
      $this->exhibitPage_3 = get_record("ExhibitPage", [
        "exhibit_id" => $this->exhibit->id,
        "slug" => $this->params->page_slug_3,
      ]);

      $this->data[] = [
        "atts" => [],
        "title" => $this->exhibitPage_3->title,
        "slug" => $this->exhibitPage_3->slug,
        "url" => url(
          [
            "action" => "show",
            "controller" => "exhibitpage",
            "slug" => $this->exhibit->slug,
            "page_slug_1" => $this->exhibitPage_1->slug,
            "page_slug_2" => $this->exhibitPage_2->slug,
            "page_slug_3" => $this->exhibitPage_3->slug,
          ],
          "ems_exhibitPage3"
        ),
      ];
    }
  }

  public function emsCollectionItem()
  {
    // Only add the file item to the breadcrumb if the display title is not empty.
    if (
      isset($this->config["file_info"]["display_title"]) &&
      !empty($this->config["file_info"]["display_title"])
    ) {
      if ($this->params->item_id && isset($this->config["item"])) {
        set_current_record("item", $this->config["item"]);
        $this->data[] = [
          "atts" => [],
          "title" => $this->config["file_info"]["display_title"],
          "slug" => $this->exhibit->slug,
          "url" => url(
            [
              "action" => "show",
              "controller" => "item",
              "slug" => $this->params->slug,
              "page_slug_1" => $this->params->page_slug_1
                ? $this->params->page_slug_1
                : "",
              "page_slug_2" => $this->params->page_slug_2
                ? $this->params->page_slug_2
                : "",
              "page_slug_3" => $this->params->page_slug_3
                ? $this->params->page_slug_3
                : "",
              "collection_id" => $this->params->collection_id
                ? $this->params->collection_id
                : "",
              "item_id" => $this->params->item_id,
            ],
            "ems_collection_item"
          ),
        ];
      }
    }
  }

  public function emsCollectionItemFile()
  {
    // Only add the file item to the breadcrumb if the display title is not empty.
    if (
      isset($this->config["file_info"]["display_title"]) &&
      !empty($this->config["file_info"]["display_title"])
    ) {
      if ($this->params->item_id && isset($this->config["item"])) {
        set_current_record("item", $this->config["item"]);
        $this->data[] = [
          "atts" => [],
          "title" => $this->config["file_info"]["display_title"],
          "slug" => $this->exhibit->slug,
          "url" => url(
            [
              "action" => "show",
              "controller" => "item",
              "slug" => $this->params->slug,
              "page_slug_1" => $this->params->page_slug_1
                ? $this->params->page_slug_1
                : "",
              "page_slug_2" => $this->params->page_slug_2
                ? $this->params->page_slug_2
                : "",
              "page_slug_3" => $this->params->page_slug_3
                ? $this->params->page_slug_3
                : "",
              "collection_id" => $this->params->collection_id
                ? $this->params->collection_id
                : "",
              "item_id" => $this->params->item_id,
              "file_id" => $this->params->file_id,
            ],
            "ems_collection_item_file"
          ),
        ];
      }
    }
  }

  public function emsShowFile1()
  {
    // Only add the file item to the breadcrumb if the display title is not empty.
    if (
      isset($this->config["file_info"]["display_title"]) &&
      !empty($this->config["file_info"]["display_title"])
    ) {
      if (
        $this->params->item_id &&
        isset($this->config["item"]) &&
        $this->params->file_id
      ) {
        set_current_record("item", $this->config["item"]);
        $this->data[] = [
          "atts" => [],
          "title" => $this->config["file_info"]["display_title"],
          "slug" => $this->exhibit->slug,
          "url" => url(
            [
              "action" => "show",
              "controller" => "item",
              "slug" => $this->params->slug,
              "page_slug_1" => $this->params->page_slug_1
                ? $this->params->page_slug_1
                : "",
              "page_slug_2" => $this->params->page_slug_2
                ? $this->params->page_slug_2
                : "",
              "page_slug_3" => $this->params->page_slug_3
                ? $this->params->page_slug_3
                : "",
              "collection_id" => $this->params->collection_id
                ? $this->params->collection_id
                : "",
              "item_id" => $this->params->item_id,
              "file_id" => $this->params->file_id,
            ],
            "ems_show_file1"
          ),
        ];
      }
    }
  }

  public function emsShowFile2()
  {
    // Only add the file item to the breadcrumb if the display title is not empty.
    if (
      isset($this->config["file_info"]["display_title"]) &&
      !empty($this->config["file_info"]["display_title"])
    ) {
      if (
        $this->params->item_id &&
        isset($this->config["item"]) &&
        $this->params->file_id
      ) {
        set_current_record("item", $this->config["item"]);
        $this->data[] = [
          "atts" => [],
          "title" => $this->config["file_info"]["display_title"],
          "slug" => $this->exhibit->slug,
          "url" => url(
            [
              "action" => "show",
              "controller" => "item",
              "slug" => $this->params->slug,
              "page_slug_1" => $this->params->page_slug_1
                ? $this->params->page_slug_1
                : "",
              "page_slug_2" => $this->params->page_slug_2
                ? $this->params->page_slug_2
                : "",
              "page_slug_3" => $this->params->page_slug_3
                ? $this->params->page_slug_3
                : "",
              "collection_id" => $this->params->collection_id
                ? $this->params->collection_id
                : "",
              "item_id" => $this->params->item_id,
              "file_id" => $this->params->file_id,
            ],
            "ems_show_file1"
          ),
        ];
      }
    }
  }

  public function emsShowFile3()
  {
    // Only add the file item to the breadcrumb if the display title is not empty.
    if (
      isset($this->config["file_info"]["display_title"]) &&
      !empty($this->config["file_info"]["display_title"])
    ) {
      if (
        $this->params->item_id &&
        isset($this->config["item"]) &&
        $this->params->file_id
      ) {
        set_current_record("item", $this->config["item"]);
        $this->data[] = [
          "atts" => [],
          "title" => $this->config["file_info"]["display_title"],
          "slug" => $this->exhibit->slug,
          "url" => url(
            [
              "action" => "show",
              "controller" => "item",
              "slug" => $this->params->slug,
              "page_slug_1" => $this->params->page_slug_1
                ? $this->params->page_slug_1
                : "",
              "page_slug_2" => $this->params->page_slug_2
                ? $this->params->page_slug_2
                : "",
              "page_slug_3" => $this->params->page_slug_3
                ? $this->params->page_slug_3
                : "",
              "collection_id" => $this->params->collection_id
                ? $this->params->collection_id
                : "",
              "item_id" => $this->params->item_id,
              "file_id" => $this->params->file_id,
            ],
            "ems_show_file1"
          ),
        ];
      }
    }
  }

  /**
   * Sets the previous URL and title from the current route so we can pass
   * that to pages linking
   * to items and files, objects that may have more than one possible URL.
   */
  public function setPrevData()
  {
    $data = [];
    $title = "";

    if ($this->route == "ems_exhibitLanding") {
      $data["action"] = "show";
      $data["controller"] = "default";
      $route = "ems_exhibitLanding";
      $data["slug"] = $this->params->slug;
      $title = $this->config["exhibit"]->title;
    }

    if ($this->route == "ems_exhibitPage1") {
      $data["action"] = "show";
      $data["controller"] = "exhibitpage";
      $route = "ems_exhibitLanding";
      $data["slug"] = $this->params->slug;
      $title = $this->exhibit->title;
    }

    if ($this->route == "ems_exhibitPage2") {
      $data["action"] = "show";
      $data["controller"] = "exhibitpage";
      $route = "ems_exhibitPage1";
      $data["slug"] = $this->params->slug;
      $data["page_slug_1"] = $this->params->page_slug_1;
      $title = $this->exhibitPage_2->title;
    }

    if ($this->route == "ems_exhibitPage3") {
      $data["action"] = "show";
      $data["controller"] = "exhibitpage";
      $route = "ems_exhibitPage2";
      $data["slug"] = $this->params->slug;
      $data["page_slug_1"] = $this->params->page_slug_1;
      $data["page_slug_2"] = $this->params->page_slug_2;
      $title = $this->exhibitPage_2->title;
    }

    if ($this->route == "ems_show_file3") {
      $data["action"] = "show";
      $data["controller"] = "item";
      $route = "ems_exhibitPage3";
      $data["slug"] = $this->params->slug;
      $data["page_slug_1"] = $this->params->page_slug_1;
      $data["page_slug_2"] = $this->params->page_slug_2;
      $data["page_slug_3"] = $this->params->page_slug_3;
      $data["item_id"] = $this->params->item_id;
      $title = $this->exhibitPage_3->title;
    }

    if ($this->route == "ems_show_file2") {
      $data["action"] = "show";
      $data["controller"] = "item";
      $route = "ems_exhibitPage2";
      $data["slug"] = $this->params->slug;
      $data["page_slug_1"] = $this->params->page_slug_1;
      $data["page_slug_2"] = $this->params->page_slug_2;
      $data["item_id"] = $this->params->item_id;
      $title = $this->exhibitPage_2->title;
    }

    if ($this->route == "ems_show_file1") {
      $data["action"] = "show";
      $data["controller"] = "item";
      $route = "ems_exhibitPage1";
      $data["slug"] = $this->params->slug;
      $data["page_slug_1"] = $this->params->page_slug_1;
      $data["item_id"] = $this->params->item_id;
      $title = $this->exhibitPage_1->title;
    }

    if ($this->route == "ems_collection") {
      $data["action"] = "browse";
      $data["controller"] = "default";
      $route = "ems_exhibitLanding";
      $data["slug"] = $this->exhibit->slug;
      $title = $this->exhibit->title;
    }

    if ($this->route == "ems_collection_item") {
      $data["action"] = "browse";
      $data["controller"] = "exhibitpage";
      $route = "ems_exhibitPage1";
      $data["slug"] = $this->params->slug;
      $data["page_slug_1"] = $this->params->page_slug_1;
      $title = $this->exhibitPage_1->title;
    }

    if ($this->route == "ems_collection_item_file") {
      $data["action"] = "browse";
      $data["controller"] = "exhibitpage";
      $route = "ems_collection";
      $data["slug"] = $this->params->slug;
      $data["page_slug_1"] = $this->params->page_slug_1;
      $title = $this->exhibitPage_1->title;
    }

    if (!empty($data)) {
      $this->prevData["uri"] = url($data, $route);
      $this->prevData["title"] = $title;
    }
  }

  public function html()
  {
    $last_title = "";

    $this->html = "<ul>";
    foreach ($this->data as $key => $row) {
      $attributes = isset($row["atts"])
        ? ems_array_to_attributes($row["atts"])
        : "";
      if ($row["title"] !== $last_title) {
        $this->html .=
          "<li" .
          $attributes .
          '><a href="' .
          $row["url"] .
          '">' .
          $row["title"] .
          "</a></li>";
      }
      $last_title = $row["title"];
    }
    $this->html .= "</ul>";
  }
} // End BreadcrumbHelper class.
