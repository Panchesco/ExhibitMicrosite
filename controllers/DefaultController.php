<?php
class ExhibitMicrosite_DefaultController extends
  Omeka_Controller_AbstractActionController
{
  protected $_exhibit;
  protected $_slug;
  protected $_theme_options;
  protected $_request;
  protected $_route;

  function _init()
  {
    $this->_request = Zend_Controller_Front::getInstance()->getRequest();
    $this->_slug = $this->_request->getParam("slug");
    $this->_route = $this->getFrontController()
      ->getRouter()
      ->getCurrentRouteName();
    if (!$this->_exhibit) {
      $this->_exhibit = $this->_helper->db
        ->getTable("Exhibit")
        ->findBySlug($this->_slug);
    }

    $this->_theme_options = $this->_exhibit->getThemeOptions();

    print_r("<pre>");
    print_r($this->_theme_options);
    print_r("<pre>");

    $this->view->addScriptPath(
      PUBLIC_THEME_DIR .
        "/" .
        $this->_theme_options["theme_name"] .
        "/exhibit-microsite/views"
    );
  }

  public function showAction()
  {
    $this->_init();
    $this->view->exhibit = $this->_exhibit;
    $this->view->partial("default/show.php", [
      "exhibit" => $this->_exhibit,
      "theme_options" => $this->_theme_options,
      "view" => $this->view,
    ]);

    //exit();
  }
}
