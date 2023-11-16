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

  protected function _init()
  {
    // Set the current exhibit;
    $request = Zend_Controller_Front::getInstance()->getRequest();
    $this->_slug = $request->getParam("slug");
    $this->_page_slug_1 = $request->getParam("page_slug_1");
    $this->_page_slug_2 = $request->getParam("page_slug_2");
    $this->_page_slug_3 = $request->getParam("page_slug_3");
    $this->_page_number = $request->getParam("page_number");
    $this->_depth = 0;

    if (!$this->_exhibit) {
      $this->_exhibit = $this->_helper->db
        ->getTable("Exhibit")
        ->findBySlug($this->_slug);
    }

    $this->theme_options = $this->_exhibit->getThemeOptions();
  }

  public function showAction()
  {
    $this->_init();

    $route = $this->getFrontController()
      ->getRouter()
      ->getCurrentRouteName();

    $request = Zend_Controller_Front::getInstance()->getRequest();

    $route = $this->getFrontController()
      ->getRouter()
      ->getCurrentRouteName();
    $request = Zend_Controller_Front::getInstance()->getRequest();

    if ($this->_page_slug_1) {
      $slug = $this->_page_slug_1;
      $this->_depth++;
    }

    if ($this->_page_slug_2) {
      $slug = $this->_page_slug_2;
      $this->_depth++;
    }

    if ($this->_page_slug_3) {
      $slug = $this->_page_slug_3;
      $this->_depth++;
    }

    $exhibitPage = $this->_helper->db
      ->getTable("ExhibitPage")
      ->findBySlug("home");

    print_r("<pre>");
    print_r($exhibitPage);
    print_r("</pre>");

    die();

    $this->view->assign(["exhibitPage" => $exhibitPage]);

    $this->view->addScriptPath(
      THEME_DIR . "/exhibit-microsite/views/exhibit-microsite/exhibit-page"
    );
    $this->view->addScriptPath(
      PLUGIN_THEME_DIR .
        "/" .
        $this->theme_options["theme"] .
        "/views/exhibit-microsite/exhibit-page"
    );

    // echo $this->view->partial("show.php", [
    //   "exhibitPage" => $exhibitPage,
    //   "theme_options" => $this->theme_options,
    // ]);

    exit();
  }
}
