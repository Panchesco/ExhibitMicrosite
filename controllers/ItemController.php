<?php

use ExhibitMicrosite\Helpers\ParamsHelper;
use ExhibitMicrosite\Helpers\ExhibitMicrositeHelper;
use ExhibitMicrosite\Helpers\BreadcrumbHelper;

class ExhibitMicrosite_ItemController extends
  Omeka_Controller_AbstractActionController
{
  public $item_id;
  public $item;
  public $exhibit;
  public $exhibitPage;
  public $slug;
  public $page_slug_1;
  public $page_slug_2;
  public $page_slug_3;
  public $page;
  public $theme_options;
  public $page_slugs = [];
  public $route;
  public $active_file;
  public $file_info;
  public $files;

  protected $_request;

  protected function _init()
  {
    $this->view = get_view();
    $this->request = Zend_Controller_Front::getInstance()->getRequest();
    $this->route = $this->getFrontController()
      ->getRouter()
      ->getCurrentRouteName();

    $params = new ParamsHelper();

    if (!$this->exhibit && $params->slug) {
      $this->exhibit = get_record("Exhibit", [
        "public" => 1,
        "slug" => $params->slug,
      ]);
    }

    if (!$this->exhibitPage && $params->page_slugs) {
      $this->exhibitPage = get_record("ExhibitPage", [
        "public" => 1,
        "exhibit_id" => $this->exhibit->id,
        "slug" => array_pop($params->page_slugs),
      ]);
    }

    $this->microsite = new ExhibitMicrositeHelper([
      "route" => $this->route,
      "exhibit" => $this->exhibit,
      "exhibitPage" => $this->exhibitPage,
    ]);

    if (!$this->item && $params->item_id) {
      $this->item = get_record_by_id("Item", $params->item_id);
      $this->files = $this->item->getFiles();
      if ($this->files) {
        $this->active_file = !$params->file_id
          ? $this->files[0]
          : get_record_by_id("File", $params->file_id);
      } else {
        $this->files = [];
        $this->active_file = null;
      }
    }
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

    $this->thumb_links_base = $this->thumbLinksBase();

    $this->view->addScriptPath(EXHIBIT_MICROSITE_PLUGIN_DIR . "/views");

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
  }

  public function fileInfo()
  {
    return $file_info;
  }

  public function thumbLinksBase()
  {
    $params = new ParamsHelper();
    $base = WEB_ROOT . "/exhibits/show";

    if ($params->slug) {
      $base .= "/" . $params->slug;
    }

    if (!$params->page_slugs || !is_array($params->page_slugs)) {
      $base .= "/item";
      $base .= "/" . $params->item_id;
      return $base;
    }

    foreach ($this->page_slugs as $slug) {
      $base .= "/" . $slug;
    }
    foreach ($params->page_slugs as $param) {
      $base .= "/" . $param;
    }
    $base .= "/item";
    $base .= "/" . $params->item_id;
    return $base;
  }

  public function showAction()
  {
    $this->_init();

    $params = new ParamsHelper();
    $collection = $this->item->getCollection();

    $this->view->item = $this->item;

    echo $this->view->partial("items/show.php", [
      "active_file" => $this->active_file,
      "breadcrumb" => $this->breadcrumb->html,
      "collection" => $collection,
      "collection_id" => $collection->id,
      "route" => $this->route,
      "exhibit" => $this->exhibit,
      "exhibitPage" => $this->exhibitPage,
      "files" => $this->files,
      "item" => $this->item,
      "item_id" => $this->item_id,
      "file_info" => $this->file_info,
      "page_slugs" => $params->page_slugs,
      "slug" => $params->slug,
      "thumb_links_base" => $this->thumb_links_base,
      "theme_options" => $this->microsite->exhibit->getThemeOptions(),
      "microsite" => $this->microsite,
      "view" => $this->view,
      "canonicalURL" => $this->microsite->canonicalURL($this->route),
      "collection_title" => metadata($collection, "rich_title", [
        "no_escape" => true,
      ]),
    ]);

    exit();
  }

  public function browseAction()
  {
  }
} // End ExhibitMicrosite_ItemController;
