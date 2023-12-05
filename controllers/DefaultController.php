<?php

use ExhibitMicrosite\Helpers\ParamsHelper;
use ExhibitMicrosite\Helpers\ExhibitMicrositeHelper;
use ExhibitMicrosite\Helpers\BreadcrumbHelper;

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

    $this->breadcrumb = new BreadcrumbHelper(["exhibit" => $this->exhibit]);
  }

  public function showAction()
  {
    $this->init();

    if ($this->exhibit->use_summary_page == 1) {
      return $this->summaryPage();
    } else {
      $firstPage = $this->exhibit->getFirstTopPage();
      if (null !== $firstPage) {
        $this->_helper->redirector->gotoRoute(
          [
            "slug" => $this->exhibit->slug,
            "page_slug_1" => $firstPage->slug,
          ],
          "exhibitShow"
        );
      }
    }

    $this->view->exhibit = $this->exhibit;

    $this->view->partial("exhibit-pages/show.php", [
      "breadcrumb" => $this->breadcrumb->html,
      "exhibitPage" => get_record("ExhibitPage", $this->slug),
      "exhibit" => $this->exhibit,
      "theme_options" => $this->theme_options,
      "view" => $this->view,
    ]);
  }

  public function summaryPage()
  {
    $this->view->exhibit = $this->exhibit;

    echo $this->view->partial("exhibit/summary.php", [
      "breadcrumb" => $this->breadcrumb->html,
      "exhibit" => $this->exhibit,
      "theme_options" => $this->theme_options,
      "view" => $this->view,
    ]);

    exit();
  }
} // End ExhibitMicrosite_DefaultController
