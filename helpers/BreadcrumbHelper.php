<?php

namespace ExhibitMicrosite\Helpers;
use ExhibitMicrosite\Helpers\ParamsHelper;
use ExhibitMicrosite\Helpers\ExhibitMicrositeHelper;
use Zend_Controller_Front;

class BreadcrumbHelper
{
  public $config;
  public $data;
  public $html;
  public $exhbitPage_1;
  public $exhbitPage_2;
  public $exhbitPage_3;

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

    // print_r("<pre>");
    // print_r($this->route);
    // print_r("</pre>");
    // die();

    $this->setSummaryPage();
    $this->setExhibitPage1();
    $this->setExhibitPage2();
    $this->setExhibitPage3();
    $this->setEmsShowItem1();
    // $this->setEmsShowItem2();
    // $this->setEmsShowItem3();
    $this->setEmsShowFile();

    $this->html();
  }

  public function setSummaryPage()
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

  public function setExhibitPage1()
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

  public function setExhibitPage2()
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

  public function setExhibitPage3()
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

  public function html()
  {
    $this->html = "<ul>";
    foreach ($this->data as $row) {
      $this->html .=
        '<li><a href="' . $row["url"] . '">' . $row["title"] . "</a></li>";
    }
    $this->html .= "</ul>";
  }

  public function setEmsShowItem1()
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
            "page_slug_1" => $this->params->page_slug_1,
            "item_id" => $this->params->item_id,
          ],
          "ems_show_item1"
        ),
      ];
    }
  }

  //   public function setEmsShowItem2()
  //   {
  //     if ($this->params->item_id && isset($this->config["item"])) {
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
  //           "ems_show_item2"
  //         ),
  //       ];
  //     }
  //   }
  //
  //   public function setEmsShowItem3()
  //   {
  //     if ($this->params->item_id && isset($this->config["item"])) {
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
  //             "page_slug_3" => $this->params->page_slug_2,
  //             "item_id" => $this->params->item_id,
  //           ],
  //           "ems_show_item3"
  //         ),
  //       ];
  //     }
  //   }

  public function setEmsShowFile()
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
            "page_slug_1" => $this->params->page_slug_1,
            "item_id" => $this->params->item_id,
          ],
          "ems_show_item1"
        ),
      ];
    }
  }
} // End BreadcrumbHelper class.
