<?php

class ExhibitMicrosite_PageController extends
  Omeka_Controller_AbstractActionController
{
  protected $_exhibit;
  protected $_slug;
  protected $_page_slug_1;
  protected $_page_slug_2;
  protected $_page_slug_3;
  protected $_page;
  protected $_depth;
  protected $_theme_options;
  protected $_page_slugs = [];
  protected $_route;

  protected function _init()
  {
    // Set the current exhibit;
    $this->_request = Zend_Controller_Front::getInstance()->getRequest();
    $this->_route = $this->getFrontController()
      ->getRouter()
      ->getCurrentRouteName();

    $this->_slug = $this->_request->getParam("slug");

    if (!$this->_exhibit) {
      $this->_exhibit = $this->_helper->db
        ->getTable("Exhibit")
        ->findBySlug($this->_slug);
    }

    // Set $this->_slug to current ExhibitPage slug.
    $this->_page_slug_1 = $this->_request->getParam("page_slug_1");
    $this->_page_slug_2 = $this->_request->getParam("page_slug_2");
    $this->_page_slug_3 = $this->_request->getParam("page_slug_3");
    $this->_page_number = $this->_request->getParam("page_number");
    $this->_depth = 0;

    if ($this->_page_slug_1) {
      $this->_slug = $this->_page_slug_1;
      $this->_page_slugs[] = $this->_slug;
      $this->_depth++;
    }

    if ($this->_page_slug_2) {
      $this->_slug = $this->_page_slug_2;
      $this->_page_slugs[] = $this->_slug;
      $this->_depth++;
    }

    if ($this->_page_slug_3) {
      $this->_slug = $this->_page_slug_3;
      $this->_page_slugs[] = $this->_slug;
      $this->_depth++;
    }

    $this->_theme_options = $this->_exhibit->getThemeOptions();
  }

  public function showAction()
  {
    $this->_init();

    $this->_exhibitPage = get_record("ExhibitPage", ["slug" => $this->_slug]);

    $this->view->assign(["exhibitPage" => $this->_exhibitPage]);

    $this->view->addScriptPath(
      PLUGIN_DIR . "/exhibit-microsite/views/exhibit-pages"
    );

    $this->view->addScriptPath(
      PUBLIC_THEME_DIR .
        "/" .
        $this->_theme_options["theme_name"] .
        "/exhibit-microsite/views"
    );

    echo $this->view->partial("exhibit-pages/show.php", [
      "exhibit" => $this->_exhibit,
      "exhibitPage" => $this->_exhibitPage,
      "page_slugs" => $this->_page_slugs,
      "theme_options" => $this->_theme_options,
      "view" => $this->view,
    ]);

    exit();
  }
}
