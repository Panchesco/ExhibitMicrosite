<?php

use ExhibitMicrosite\Helpers\ParamsHelper;
use ExhibitMicrosite\Helpers\ExhibitMicrositeHelper;

class ExhibitMicrosite_CollectionController extends
  Omeka_Controller_AbstractActionController
{
  public $exhibit;
  public $exhibitPage;
  public $microsite_options;
  public function init()
  {
    // Set the model class so this controller can perform some functions,
    // such as $this->findById()
    $this->_helper->db->setDefaultModelName("Collection");

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

    if (!$this->exhibitPage) {
      $this->exhibitPage = $this->_helper->db
        ->getTable("ExhibitPage")
        ->findBySlug("collection", ["exhibit_id" => $this->exhibit->id]);
    }

    $this->theme_options = $this->exhibit->getThemeOptions();

    $this->view->addScriptPath(
      PUBLIC_THEME_DIR .
        "/" .
        $this->exhibit->theme .
        "/exhibit-microsite/views"
    );

    $this->view->addScriptPath(
      PLUGIN_DIR . "/exhibit-microsite/views/exhibit-pages"
    );

    $this->microsite = new ExhibitMicrositeHelper([
      "route" => $this->route,
      "exhibit" => $this->exhibit,
    ]);

    $this->breadcrumb = $this->microsite->breadcrumbHTML();
  }

  public function browseAction()
  {
    $this->init();

    $creators_filter_data = $this->microsite->itemsFilterData("Creator");
    $item_types_filter_data = $this->microsite->itemTypesFilterData();

    $this->view->exhibitPage = $this->exhibitPage;
    echo $this->view->partial("collection/browse.php", [
      "breadcrumb" => $this->breadcrumb,
      "canonicalURL" => $this->microsite->canonicalURL($this->route),
      "exhibit" => $this->exhibit,
      "exhibitPage" => $this->exhibitPage,
      "params" => $this->params,
      "microsite_options" => $this->microsite->options,
      "count" => count($this->microsite->options["collection_id"]),
      "collections_filter_data" => $this->microsite->options["collections"],
      "route" => $this->route,
      "theme_options" => $this->theme_options,
      "creators_filter_data" => $creators_filter_data,
      "item_types_filter_data" => $item_types_filter_data,
      "items" => $this->collectionItems(),
      "view" => $this->view,
    ]);

    exit();
  }

  public function collectionItems($filters = [])
  {
    $db = get_db();
    return get_records(
      "Item",
      [
        "collection" => $this->microsite->options["collection_id"],
      ],
      0
    );
  }
} // End CollectionController class
