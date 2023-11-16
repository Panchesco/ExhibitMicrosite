<?php

class ExhibitMicrosite_PageController extends Omeka_Controller_AbstractActionController {


    public function showAction() {



        $route = $this->getFrontController()
          ->getRouter()
          ->getCurrentRouteName();

          exit('showAction ' . __FILE__);

        $request = Zend_Controller_Front::getInstance()->getRequest();

        $page_slug = $request->getParam("page_slug");

        exit();


    }

}
