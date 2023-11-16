<?php
class ExhibitMicrosite_DefaultController extends
  Omeka_Controller_AbstractActionController
{
  public function showAction()
  {
    die(__FILE__);
    $route = $this->getFrontController()
      ->getRouter()
      ->getCurrentRouteName();

    $request = Zend_Controller_Front::getInstance()->getRequest();
    $slug = $request->getParam("slug");

    exit();
  }
}
