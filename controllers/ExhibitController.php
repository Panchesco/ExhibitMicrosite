<?php
class ExhibitMicrosite_ExhibitController extends Omeka_Controller_AbstractActionController {


    public function showAction() {

        $route = $this->getFrontController()
      ->getRouter()
      ->getCurrentRouteName();

      exit('showAction ' . __FILE__);

    $request = Zend_Controller_Front::getInstance()->getRequest();

    $slug = $request->getParam("slug");
    die();
    exit();

    }

}
