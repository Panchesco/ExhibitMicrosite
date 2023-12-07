<?php
namespace ExhibitMicrosite\Helpers;
use Zend_Controller_Front;
class ParamsHelper
{
  public $page_slugs;
  public $paramsArray;
  public $depth;
  function __construct()
  {
    $request = Zend_Controller_Front::getInstance()->getRequest();
    $this->paramsArray = $request->getParams();
    $this->collection_id = $request->getParam("collection_id");
    $this->file_id = $request->getParam("file_id");
    $this->item_id = $request->getParam("item_id");
    $this->page_slug_1 = $request->getParam("page_slug_1");
    $this->page_slug_2 = $request->getParam("page_slug_2");
    $this->page_slug_3 = $request->getParam("page_slug_3");
    $this->page_number = $request->getParam("page_number");
    $this->slug = $request->getParam("slug");
    $this->depth = $request->getParam("depth");

    if ($this->page_slug_1) {
      $this->page_slugs[] = $this->page_slug_1;
    }

    if ($this->page_slug_2) {
      $this->page_slugs[] = $this->page_slug_2;
    }

    if ($this->page_slug_3) {
      $this->page_slugs[] = $this->page_slug_3;
    }
  }

  function getPageSlugs()
  {
    return $this->page_slugs;
  }

  function getParamsArray()
  {
    return $this->paramsArray;
  }
} // End ParamsHelper class
