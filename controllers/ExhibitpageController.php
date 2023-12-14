<?php

use ExhibitMicrosite\helpers\ParamsHelper;
use ExhibitMicrosite\helpers\ExhibitMicrositeHelper;
use ExhibitMicrosite\helpers\BreadcrumbHelper;
use ExhibitMicrosite\Helpers\NavHelper;

class ExhibitMicrosite_ExhibitpageController extends
  Omeka_Controller_AbstractActionController
{
  public $exhibit;
  public $exhibitPage;
  public $route;
  public $slug;
  public $page_slug_1;
  public $page_slug_2;
  public $page_slug_3;
  public $page;
  public $depth;
  public $theme_options;
  public $page_slugs = [];

  public function init()
  {
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

    if (!empty($this->params->page_slugs)) {
      if (!$this->exhibitPage) {
        $page_slug = end($this->params->page_slugs);
        $this->exhibitPage = get_record("ExhibitPage", ["slug" => $page_slug]);
      }
    }

    $this->theme_options = $this->exhibit->getThemeOptions();

    $this->microsite = new ExhibitMicrositeHelper([
      "route" => $this->route,
      "exhibit" => $this->exhibit,
      "exhibitPage" => $this->exhibitPage,
    ]);

    $this->breadcrumb = new BreadcrumbHelper([
      "exhibit" => $this->exhibit,
      "route" => $this->route,
    ]);

    $this->view->addScriptPath(
      EXHIBIT_MICROSITE_PLUGIN_DIR . "/ExhibitMicrosite/views/exhibit-pages"
    );

    $this->view->addScriptPath(
      EXHIBIT_MICROSITE_PLUGIN_DIR . "/views/public/sitewide"
    );

    $this->view->addScriptPath(EXHIBIT_MICROSITE_PLUGIN_DIR . "/views/public");

    $this->view->addScriptPath(
      PUBLIC_THEME_DIR .
        "/" .
        $this->exhibit->theme .
        "/exhibit-microsite/views"
    );
  }

  public function showAction()
  {
    $this->init();

    $this->nav = new NavHelper([
      "exhibit" => $this->exhibit,
      "exhibitPage" => $this->exhibitPage,
      "route" => $this->route,
    ]);

    $this->view->exhibitPage = $this->exhibitPage;

    // Branch for projects page handling.
    if ($this->exhibitPage->slug == "collections") {
      echo $this->view->partial("exhibit-pages/index.php", [
        "breadcrumb" => $this->breadcrumb->html,
        "canonicalURL" => $this->microsite->canonicalURL($this->route),
        "exhibit" => $this->exhibit,
        "exhibitPage" => $this->exhibitPage,
        "child_pages" => $this->exhibitPage->getChildPages(),
        "params" => $this->params,
        "route" => $this->route,
        "item_route" => $this->itemRoute(),
        "theme_options" => $this->theme_options,
        "view" => $this->view,
        "refUri" => $this->microsite->refUri,
        "microsite" => $this->microsite,
        "nav" => $this->nav,
      ]);
    } else {
      echo $this->view->partial("exhibit-pages/show.php", [
        "breadcrumb" => $this->breadcrumb->html,
        "canonicalURL" => $this->microsite->canonicalURL($this->route),
        "exhibit" => $this->exhibit,
        "exhibitPage" => $this->exhibitPage,
        "params" => $this->params,
        "route" => $this->route,
        "item_route" => $this->itemRoute(),
        "theme_options" => $this->theme_options,
        "view" => $this->view,
        "refUri" => $this->microsite->refUri,
        "microsite" => $this->microsite,
        "nav" => $this->nav,
      ]);
    }

    exit();
  }

  /**
   * Returns the first image file object associated with an exhibit page.
   * @param object Omeka $exhibitPage object
   * @return $object
   */
  public function exhibitPageFirstFileImage($exhibitPage)
  {
    foreach ($exhibitPage->ExhibitPageBlocks as $block) {
      $attachments = $block->getAttachments();
      foreach ($attachments as $attachment) {
        $file = $attachment->getFile();
        if ($file && strpos($file->mime_type, "image") !== false) {
          return $file;
        }
      }
    }
    return null;
  }

  /**
   * Returns the route to use for item/s links
   * @return string
   */
  public function itemRoute()
  {
    switch ($this->route) {
      case "ems_exhibitPage3":
        return "ems_show_item3";
        break;

      case "ems_exhibitPage2":
        return "ems_show_item2";
        break;

      case "ems_exhibitPage1":
        return "ems_show_item1";
        break;

      default:
        return "ems_browse_items";
    }
  }
}
