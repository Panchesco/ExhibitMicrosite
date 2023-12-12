<?php
namespace ExhibitMicrosite\Helpers;
use ExhibitMicrosite\Helpers\ExhibitMicrositeHelper;

/**
 * Exhibit Microsite
 *
 */

/**
 * The Exhibit Microsite index controller class.
 *
 * @package ExhibitMicrosite
 */

class NavHelper
{
  function __construct($config = ["active_route" => ""])
  {
    $this->config = $config;

    $this->microsite = new ExhibitMicrositeHelper($config);

    $this->top_pages = $this->microsite->exhibit->getTopPages();
    $this->top_pages_html = $this->topPagesHtml();
  }

  public function topPagesData()
  {
    return $this->top_pages;
  }

  public function topPagesHtml()
  {
    $html = "<ul>\n";
    if ($this->microsite->exhibit->use_summary_page) {
      $route = "ems_exhibitLanding";
      $options["action"] = "summary";
      $options["controller"] = "default";
      $options["slug"] = $this->microsite->params->slug;
      $url = url($options, $route);
      $html .=
        '  <li><a href="' .
        $url .
        '">' .
        $this->microsite->exhibit->title .
        "</a></li>\n";
    }

    foreach ($this->top_pages as $page) {
      $options = [];

      switch ($page->slug) {
        case "browse":
          $options["action"] = "show";
          $options["controller"] = "browsecollection";
          $options["slug"] = $this->microsite->params->slug;
          $options["page_slug_1"] = $page->slug;
          $data_slug = $page->slug;
          $route = "ems_collection";
          $display_title = $page->title;
          //$current = $page->slug == "browse" ? ' class="active"' : "";
          break;

        case "collections":
          $options["action"] = "show";
          $options["controller"] = "exhibitpage";
          $options["slug"] = $this->microsite->params->slug;
          $options["page_slug_1"] = $page->slug;
          $data_slug = $page->slug;
          $route = "ems_exhibitPage1";
          $display_title = $page->title;
          //$current = $page->slug == "collections" ? ' class="active"' : "";
          break;

        case "search":
          $options["action"] = "show";
          $options["controller"] = "exhibitpage";
          $options["slug"] = $this->microsite->params->slug;
          $options["page_slug_1"] = $page->slug;
          $data_slug = $page->slug;
          $route = "ems_exhibitPage1";
          $display_title = '<span class="material-symbols-outlined">
          search
          </span>';
          break;

        default:
          $options["action"] = "show";
          $options["controller"] = "exhibitpage";
          $options["slug"] = $this->microsite->params->slug;
          $options["page_slug_1"] = $page->slug;
          $data_slug = $page->slug;
          $route = "ems_exhibitPage1";
          $display_title = $page->title;
          //$current =
          $page->slug == $this->microsite->params->page_slug_1
            ? ' class="active"'
            : "";
          break;
      }

      $current = "";
      $html .= ' <li data-slug="' . $data_slug . '"';
      $html .=
        $current .
        '</li><a title="' .
        $page->title .
        '" href="' .
        url($options, $route) .
        '">' .
        $display_title .
        "</a></li>" .
        "\n";
    }
    $html .= "</ul>\n";
    return $html;
  }
} // End ExhibitMicrosite_Nav class.
