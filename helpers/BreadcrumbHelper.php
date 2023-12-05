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

    $this->emsShowItem();
    // $this->setEmsShowItem2();
    // $this->setEmsShowItem3();
    $this->emsShowFile();

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

  public function emsShowItem()
  {
    if ($this->params->item_id && isset($this->config["item"])) {
      set_current_record("item", $this->config["item"]);
      $this->data[] = [
        "title" => metadata("Item", "rich_title"),
        "slug" => $this->exhibit->slug,
        "url" => url(
          [
            "action" => "show",
            "controller" => "item",
            "slug" => $this->params->slug,
            "item_id" => $this->params->item_id,
          ],
          "ems_show_item"
        ),
      ];
    }
  }

  public function emsShowItem1()
  {
    if (
      $this->params->page_slug_1 &&
      $this->params->item_id &&
      isset($this->config["item"])
    ) {
      set_current_record("item", $this->config["item"]);
      $this->data[] = [
        "title" => metadata("Item", "rich_title"),
        "slug" => $this->exhibit->slug,
        "url" => url(
          [
            "action" => "show",
            "controller" => "item",
            "slug" => $this->params->slug,
            "page_slug_1" => $this->params->page_slug_1,
            "item_id" => $this->params->item_id,
          ],
          "ems_show_item1"
        ),
      ];
    }
  }

  //   public function emsShowItem2()
  //   {
  //     if (
  //       $this->params->page_slug_2 &&
  //       $this->params->item_id &&
  //       isset($this->config["item"])
  //     ) {
  //       set_current_record("item", $this->config["item"]);
  //       $this->data[] = [
  //         "title" => metadata("Item", "rich_title"),
  //         "slug" => $this->exhibit->slug,
  //         "url" => url(
  //           [
  //             "action" => "show",
  //             "controller" => "item",
  //             "slug" => $this->params->slug,
  //             "page_slug_1" => $this->params->page_slug_1,
  //             "page_slug_2" => $this->params->page_slug_2,
  //             "item_id" => $this->params->item_id,
  //           ],
  //           "ems_show_item1"
  //         ),
  //       ];
  //     }
  //   }
  //
  //   public function emsShowItem3()
  //   {
  //     if (
  //       $this->params->page_slug_3 &&
  //       $this->params->item_id &&
  //       isset($this->config["item"])
  //     ) {
  //       set_current_record("item", $this->config["item"]);
  //       $this->data[] = [
  //         "title" => metadata("Item", "rich_title"),
  //         "slug" => $this->exhibit->slug,
  //         "url" => url(
  //           [
  //             "action" => "show",
  //             "controller" => "item",
  //             "slug" => $this->params->slug,
  //             "page_slug_1" => $this->params->page_slug_3,
  //             "item_id" => $this->params->item_id,
  //           ],
  //           "ems_show_item1"
  //         ),
  //       ];
  //     }
  //   }

  public function emsShowFile()
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
            "ems_show_item1"
          ),
        ];
      }
    }
  }

  public function html()
  {
    $last_title = "";
    $this->html = "[breadcrumb]" . "<ul>";
    foreach ($this->data as $key => $row) {
      if ($row["title"] !== $last_title) {
        $this->html .=
          '<li><a href="' . $row["url"] . '">' . $row["title"] . "</a></li>";
      }
      $last_title = $row["title"];
    }
    $this->html .= "</ul>";
  }
} // End BreadcrumbHelper class.
