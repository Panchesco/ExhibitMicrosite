<?php

use ExhibitMicrosite\Helpers\ParamsHelper;
use ExhibitMicrosite\Helpers\ExhibitMicrositeHelper;

class ExhibitMicrosite_DefaultController extends
  Omeka_Controller_AbstractActionController
{
  public $exhibit;
  public $slug;
  public $theme_options;
  public $request;
  public $route;
  public $microsite;

  function init()
  {
    $this->route = $this->getFrontController()
      ->getRouter()
      ->getCurrentRouteName();

    $this->params = new ParamsHelper();
    $this->slug = $this->params->slug;
    if (!$this->exhibit) {
      $this->exhibit = $this->_helper->db
        ->getTable("Exhibit")
        ->findBySlug($this->slug);
    }

    $this->theme_options = $this->exhibit->getThemeOptions();

    $this->view->addScriptPath(EXHIBIT_MICROSITE_PLUGIN_DIR . "/views");

    $this->microsite = new ExhibitMicrositeHelper([
      "route" => $this->route,
      "exhibit" => $this->exhibit,
    ]);
    $this->breadcrumb = $this->microsite->breadcrumbHTML();
  }

  public function showAction()
  {
    $this->init();

    if ($this->exhibit->use_summary_page == 1) {
      $this->view->exhibit = $this->exhibit;
      $this->view->partial("default/show.php", [
        "breadcrumb" => $this->breadcrumb,
        "exhibit" => $this->exhibit,
        "theme_options" => $this->theme_options,
        "view" => $this->view,
      ]);
    } else {
      $this->view->exhibit = $this->exhibit;

      foreach ($this->exhibit->getPages() as $key => $page) {
        print_r("<pre>");
        print_r(get_object_vars($page));
        print_r("<pre>");
      }

      $this->view->partial("exhibit-pages/show.php", [
        "exhibitPage" => get_record("ExhibitPage", $this->slug),
        "breadcrumb" => $this->breadcrumb,
        "exhibit" => $this->exhibit,
        "theme_options" => $this->theme_options,
        "view" => $this->view,
      ]);
    }
  }
}
