<?php

use ExhibitMicrosite\Helpers\ParamsHelper;
use ExhibitMicrosite\Helpers\ExhibitMicrositeHelper;
use ExhibitMicrosite\Helpers\BreadcrumbHelper;
use ExhibitMicrosite\Helpers\NavHelper;

class ExhibitMicrosite_DefaultController extends
  Omeka_Controller_AbstractActionController
{
  public $exhibit;
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

    $this->microsite = new ExhibitMicrositeHelper([
      "route" => $this->route,
      "exhibit" => $this->exhibit,
    ]);

    $this->theme_options = $this->exhibit->getThemeOptions();

    $this->breadcrumb = new BreadcrumbHelper(["exhibit" => $this->exhibit]);

    $this->nav = new NavHelper([
      "exhibit" => $this->exhibit,
      "route" => $this->route,
    ]);

    $this->exhibit_theme_options = $this->exhibit->getThemeOptions();
  }

  public function summaryAction()
  {
    echo $this->view->partial("exhibit/summary.php", [
      "breadcrumb" => $this->breadcrumb->html,
      "canonicalURL" => $this->microsite->canonicalURL($this->route),
      "exhibit" => $this->exhibit,
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
} // End ExhibitMicrosite_DefaultController
