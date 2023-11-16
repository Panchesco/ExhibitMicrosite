<?php

class ExhibitMicrosite_PageController extends Omeka_Controller_AbstractActionController {
    
    
    public function pageAction() {
        
        die(__FILE__);
        
        $route = $this->getFrontController()
          ->getRouter()
          ->getCurrentRouteName();
        
        $request = Zend_Controller_Front::getInstance()->getRequest();
            
        $page_slug = $request->getParam("page_slug");
       
        
    }
    
}