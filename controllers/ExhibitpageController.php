<?php
use ExhibitMicrosite\helpers\ParamsHelper;
use ExhibitMicrosite\helpers\ExhibitMicrositeHelper;

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

    $this->view->addScriptPath(
      PUBLIC_THEME_DIR .
        "/" .
        $this->theme_options["theme_name"] .
        "/exhibit-microsite/views"
    );

    $this->view->addScriptPath(
      PLUGIN_DIR . "/exhibit-microsite/views/exhibit-pages"
    );

    $this->microsite = new ExhibitMicrositeHelper([
      "route" => $this->route,
      "exhibit" => $this->exhibit,
      "exhibitPage" => $this->exhibitPage,
    ]);

    $this->breadcrumb = $this->microsite->breadcrumbHTML();
  }

  public function showAction()
  {
    $this->init();

    $this->view->exhibitPage = $this->exhibitPage;

    echo $this->view->partial("exhibit-pages/show.php", [
      "breadcrumb" => $this->breadcrumb,
      "canonicalURL" => $this->microsite->canonicalURL($this->route),
      "exhibit" => $this->exhibit,
      "exhibitPage" => $this->exhibitPage,
      "params" => $this->params,
      "route" => $this->route,
      "item_route" => $this->itemRoute(),
      "theme_options" => $this->theme_options,
      "view" => $this->view,
    ]);

    exit();
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
