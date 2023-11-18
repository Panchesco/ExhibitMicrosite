<?php
/**
 * Exhibit Microsite
 *
 */

/**
 * The Exhibit Microsite index controller class.
 *
 * @package ExhibitMicrosite
 */
class ExhibitMicrosite_IndexController extends
  Omeka_Controller_AbstractActionController
{
  public function init()
  {
    // Set the model class so this controller can perform some functions,
    // such as $this->findById()
    $this->_helper->db->setDefaultModelName("Option");
  }

  public function indexAction()
  {
    // Always go to browse.
    $this->_helper->redirector("browse");
    return;
  }
}
