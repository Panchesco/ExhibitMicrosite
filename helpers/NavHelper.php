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
    $html =
      '<label id="mobile-menu-wrapper" for="mobile-menu-toggle">[mobile menu toggle]<input type="checkbox" name="mobile-menu-toggle" id="mobile-menu-toggle" /></label>';
    $html .= "<ul>\n";
    if ($this->microsite->exhibit->use_summary_page) {
      if ($this->microsite->options["summary_in_nav"]) {
        $title = !empty($this->microsite->options["summary_alt_title"])
          ? $this->microsite->options["summary_alt_title"]
          : $this->microsite->exhibit->title;
        $route = "ems_exhibitLanding";
        $options["action"] = "summary";
        $options["controller"] = "default";
        $options["slug"] = $this->microsite->params->slug;
        $url = url($options, $route);
        $html .=
          '  <li class="nav-ems_exhibitLanding"><a href="' .
          $url .
          '">' .
          $title .
          "</a></li>\n";
      }
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
          $current =
            $this->microsite->params->page_slug_1 == "browse"
              ? ' class="current"'
              : "";
          break;

        case "collections":
          $options["action"] = "show";
          $options["controller"] = "exhibitpage";
          $options["slug"] = $this->microsite->params->slug;
          $options["page_slug_1"] = $page->slug;
          $data_slug = $page->slug;
          $route = "ems_exhibitPage1";
          $display_title = $page->title;
          $current =
            $this->microsite->params->page_slug_1 == "collections"
              ? ' class="current ems-trigger indicator" data-target="#nav-collections"'
              : ' class="ems-trigger indicator" data-target="#nav-collections"';
          break;

        case "search":
          $options["action"] = "show";
          $options["controller"] = "exhibitpage";
          $options["slug"] = $this->microsite->params->slug;
          $options["page_slug_1"] = $page->slug;
          $data_slug = $page->slug;
          $route = "ems_exhibitPage1";
          $display_title =
            '<img id="search-icon" src="' .
            WEB_RELATIVE_THEME .
            '/border-narrative/img/icons/search_FILL0_wght400_GRAD0_opsz24.svg" title="' .
            __("Search") .
            ' alt="' .
            __("Search Icon") .
            '">';
          $current = ' data-target="#nav-search" class="ems-trigger"';
          break;

        default:
          $options["action"] = "show";
          $options["controller"] = "exhibitpage";
          $options["slug"] = $this->microsite->params->slug;
          $options["page_slug_1"] = $page->slug;
          $data_slug = $page->slug;
          $route = "ems_exhibitPage1";
          $display_title = $page->title;
          $current =
            $page->slug == $this->microsite->params->page_slug_1
              ? ' class="current"'
              : "";
          break;
      }

      $html .= " <li" . $current . ' data-slug="' . $data_slug . '">';
      $html .=
        '<a title="' .
        $page->title .
        '" href="' .
        url($options, $route) .
        '">' .
        $display_title .
        "</a></li>" .
        "\n";
    }

    $html .=
      '<li class="lang" data-slug="lang"><a href="#">Español</a></li>' . "\n";

    $html .= "</ul>\n";

    return $html;
  }
} // End ExhibitMicrosite_Nav class.
