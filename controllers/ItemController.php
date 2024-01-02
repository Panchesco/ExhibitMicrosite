<?php

use ExhibitMicrosite\Helpers\ExhibitMicrositeHelper;
use ExhibitMicrosite\Helpers\BreadcrumbHelper;
use ExhibitMicrosite\Helpers\NavHelper;

class ExhibitMicrosite_ItemController extends
  Omeka_Controller_AbstractActionController
{
  public $item_id;
  public $item;
  public $exhibit;
  public $exhibitPage;
  public $theme_options;
  public $active_file;
  public $file_info;
  public $files;
  public $nav;

  protected $_request;

  protected function _init()
  {
    // Create instance of view object.
    $this->view = get_view();

    // Create instance of request method.
    $this->request = Zend_Controller_Front::getInstance()->getRequest();

    // Set current route name, so we can pass it to views and blocks for linking
    // to ExhibitMicrosite custom routes.
    $this->route = $this->getFrontController()
      ->getRouter()
      ->getCurrentRouteName();

    // Get the current exhibit object based on the slug.
    if (!$this->exhibit && $this->request->getParam("slug")) {
      $this->exhibit = get_record("Exhibit", [
        "public" => 1,
        "slug" => $this->request->getParam("slug"),
      ]);
    }

    // Set the current exhibit page object.
    if (!$this->exhibitPage) {
      $this->exhibitPage = get_record("ExhibitPage", [
        "public" => 1,
        "exhibit_id" => $this->exhibit->id,
        "slug" => $this->request->getParam("slug"),
      ]);
    }

    // Create instance of ExhibitMicrositeHelper to use in here and to pass to
    // views.
    $this->microsite = new ExhibitMicrositeHelper([
      "route" => $this->route,
      "exhibit" => $this->exhibit,
      "exhibitPage" => $this->exhibitPage,
    ]);

    // Set the item object.
    if (!$this->item && $this->request->getParam("item_id")) {
      $this->item = get_record_by_id(
        "Item",
        $this->request->getParam("item_id")
      );
      $this->files = $this->item->getFiles();
      if ($this->files) {
        $this->active_file = !$this->request->getParam("file_id")
          ? $this->files[0]
          : get_record_by_id("File", $this->request->getParam("file_id"));
      } else {
        $this->files = [];
        $this->active_file = null;
      }
    }

    // Set the active file and active file info.
    if ($this->active_file) {
      $title = metadata($this->active_file, "rich_title", [
        "no_escape" => true,
      ]);
      $this->file_info = [
        "id" => $this->active_file->id,
        "title" => $title,
        "original_filename" => $this->active_file->original_filename,
        "display_title" =>
          $title != $this->active_file->original_filename ? $title : null,
      ];
    }

    // Set some config values to pass to the breadcrumb helper.
    $config = [
      "exhibit" => $this->exhibit,
      "exhibitPage" => $this->exhibitPage,
      "item" => $this->item,
      "route" => $this->route,
      "file_info" => $this->active_file
        ? $this->file_info
        : [
          "id" => 0,
          "title" => "",
          "display_title" => "",
          "original_filename" => "",
        ],
    ];

    $this->breadcrumb = new BreadcrumbHelper($config);
    $this->nav = new NavHelper([
      "exhibit" => $this->exhibit,
      "route" => $this->route,
    ]);
  }

  public function showAction()
  {
    $this->_init();

    $collection = $this->item->getCollection();

    $this->view->item = $this->item;

    echo $this->view->partial("items/show.php", [
      "active_file" => $this->active_file,
      "breadcrumb" => $this->breadcrumb->html,
      "collection" => $collection,
      "route" => $this->route,
      "exhibit" => $this->exhibit,
      "exhibit_theme_options" => $this->exhibit->getThemeOptions(),
      "exhibitPage" => $this->exhibitPage,
      "files" => $this->files,
      "item" => $this->item,
      "item_id" => $this->item_id,
      "file_info" => $this->file_info,
      "microsite" => $this->microsite,
      "view" => $this->view,
      "refUri" => $this->microsite->refUri,
      "prevData" => $this->breadcrumb->prevData,
      "canonicalURL" => $this->microsite->canonicalURL($this->route),
      "collection_title" => metadata($collection, "rich_title", [
        "no_escape" => true,
      ]),
      "nav" => $this->nav,
    ]);

    exit();
  }
} // End ExhibitMicrosite_ItemController;
