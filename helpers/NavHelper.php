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

use ExhibitMicrosite\Helpers\ExhibitMicrositeHelper;

class ExhibitMicrosite_NavHelper
{
  function __construct()
  {
    $this->microsite = new ExhibitMicrositeHelper();

    print_r("<pre>");
    print_r($this->microsite->exhibit);
    print_r("</pre>");

    if ($this->microsite->exhibitPage) {
      print_r("<pre>");
      print_r($this->microsite->exhibitPage);
      print_r("</pre>");
    }
  }
} // End ExhibitMicrosite_Nav class.
