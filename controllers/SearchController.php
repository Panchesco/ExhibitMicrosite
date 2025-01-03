<?php

use ExhibitMicrosite\Helpers\ExhibitMicrositeHelper;
use ExhibitMicrosite\Helpers\BreadcrumbHelper;
use ExhibitMicrosite\Helpers\NavHelper;

class ExhibitMicrosite_SearchController extends
  Omeka_Controller_AbstractActionController
{
  public $exhibit;
  public $exhibitPage;
  public $exhibitPages;
  public $slug;
  public $theme_options;
  public $exhibit_theme_options;
  public $request;
  public $route;
  public $microsite;

  function init()
  {
    $this->route = $this->getFrontController()
      ->getRouter()
      ->getCurrentRouteName();

    $request = Zend_Controller_Front::getInstance()->getRequest();

    $this->slug = $request->getParam("slug");

    if (!$this->exhibit) {
      $this->exhibit = $this->_helper->db
        ->getTable("Exhibit")
        ->findBySlug($this->slug);
    }

    if ($this->exhibit) {
      $this->exhibitPages = $this->exhibit->getPages();
    }

    if (!$this->exhibitPage) {
      $this->exhibitPage = get_record("ExhibitPage", [
        "slug" => $request->getParam("page_slug_1"),
        "exhibit_id" => $this->exhibit->id,
      ]);
    }

    $this->microsite = new ExhibitMicrositeHelper([
      "route" => $this->route,
      "exhibit" => $this->exhibit,
      "view" => $this->view,
    ]);

    $this->theme_options = $this->exhibit->getThemeOptions();
    $this->breadcrumb = new BreadcrumbHelper(["exhibit" => $this->exhibit]);
    $this->nav = new NavHelper([
      "exhibit" => $this->exhibit,
      "route" => $this->route,
    ]);
    // Make the Exhibit Theme Options available
    $this->exhibit_theme_options = $this->exhibit->getThemeOptions();
  }

  /**
  * Renders the Search page.
   */
  public function showAction()
  {
    echo $this->view->partial("search/index.php", [
      "breadcrumb" => $this->breadcrumb->html,
      "canonicalURL" => $this->microsite->canonicalURL($this->route),
      "exhibit" => $this->exhibit,
      "exhibitPage" => $this->exhibitPage,
      "exhibit_theme_options" => $this->exhibit_theme_options,
      "view" => $this->view,
      "microsite" => $this->microsite,
      "nav" => $this->nav,
      "params" => $this->microsite->params,
      "refUri" => $this->microsite->refUri,
      "route" => $this->route,
      "theme_options" => $this->theme_options,
    ]);
    exit();
  }
} // End ExhibitMicrosite_SearchController
