<?php
namespace ExhibitMicrosite\Helpers;
use ExhibitMicrosite\Helpers\ExhibitMicrositeHelper;

/**
 * The Exhibit Microsite NavHelper class.
 * @description Tasks associated with the Exhibit Microsite's navigation.
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

  /**
   * @description Returns the top level Exhibit Pages for the current exhibit.
   * @return array array of Omeka ExhibitBuilder Exhibit pages with no parents
   */
  public function topPagesData()
  {
    return $this->top_pages;
  }

  /**
  * @description Returns HTML for Top level Exhibit Page links
  * @param string $wrapper the html wrapper tag for the links. Default is ul.
  */
  public function topPagesHtml($wrapper = "ul")
  {
    $wrapper = str_replace(['<','>'],"",$wrapper);
    $wrapper = strtolower($wrapper);
    $options = [];
    $options["tag"] = "li";
    $html = "<{$wrapper}>\n";

    if ($this->microsite->exhibit->use_summary_page) {
      if ($this->microsite->options["summary_in_nav"]) {
        $options["display_title"] = !empty($this->microsite->options["summary_alt_title"])
          ? $this->microsite->options["summary_alt_title"]
          : $this->microsite->exhibit->title;
        $options["route"] = "ems_exhibitLanding";
        $options["routing"]["action"] = "summary";
        $options["routing"]["controller"] = "default";
        $options["routing"]["slug"] = $this->microsite->params->slug;
        $options["atts"] = [ "class" => "nav-ems_exhibitLanding"];
        $options["data_slug"] = $this->microsite->params->slug;
        $options["current"] =
        $this->microsite->exhibit->slug == $this->microsite->params->slug
          ? ' class="current ' .  $this->microsite->exhibit->slug . '"'
          : ' class="' .  $this->microsite->exhibit->slug. '"';
        $html.= $this->text_link($options);
      }
    }

    foreach ($this->top_pages as $key => $page) {
      $options = [];
      $options["atts"] = [];
      $options["img"]["atts"] = [];
      $options["tag"] = "li";

      switch ($page->slug) {

        case "search":

          $options["routing"]["action"] = "show";
          $options["routing"]["controller"] = "exhibitpage";
          $options["routing"]["slug"] = $this->microsite->params->slug;
          $options["routing"]["page_slug_1"] = $page->slug;
          $options["data_slug"] = $page->slug;
          $options["display_title"] = ($page->short_title) ? $page->short_title: $page->title;
          $options["route"] = "ems_exhibitPage1";
          $options["img"]["atts"] = ["id" => "search-icon",
                                      "src" => WEB_RELATIVE_THEME . "/border-narrative/img/icons/search_FILL0_wght400_GRAD0_opsz24.svg",
                                    "title" => __("Search"),
                                  "alt" => __("Search icon")
                                ];

          $options["current"] =
          $page->slug == $this->microsite->params->page_slug_1
            ? ' class="current ' . $page->slug . '"'
            : ' class="' . $page->slug . '"';
          $html.= $this->image_link($options);
          break;

        default:

          $options["routing"]["action"] = "show";
          $options["routing"]["controller"] = "exhibitpage";
          $options["routing"]["slug"] = $this->microsite->params->slug;
          $options["routing"]["page_slug_1"] = $page->slug;
          $options["data_slug"] = $page->slug;
          $options["route"] = "ems_exhibitPage1";
          $options["display_title"] = ($page->short_title) ? $page->short_title: $page->title;
          $options["current"] =
            $page->slug == $this->microsite->params->page_slug_1
              ? ' class="current ' . $page->slug . '"'
              : ' class="' . $page->slug . '"';

          $html.= $this->text_link($options);
          break;
      }
    }

    $html.= ' <li class="lang"><a href="#">Español</a></li>' . "\n";
    $html.="</{$wrapper}";
    return $html;
  }


  /**
   * @Description return html for an image link.
   * @param array $options
   * @return string
   */
  public function image_link($options) {
      $html = "<" . $options["tag"] . $options['current'] . 'data-target="' . $options['data_slug'] . '" data-slug="' . $options['data_slug'];
       foreach($options["atts"] as $att => $value ) {
         $html.= ' ' . $att . '="' . $value . '"';
       }
      $html.= '">'."\n";
      $html.= '  <a href="' . url($options['routing'],$options['route']) . '">';
      $html.="    <img";
      foreach($options["img"]["atts"] as $att => $value ) {
        $html.= ' ' . $att . '="' . $value . '"';
      }
      $html.= " />";
      $html.= "</a>\n";
      $html.= '</' . $options["tag"] . ">\n";
    return $html;
  }

  /**
   * @Description return html for a text link.
   * @param array $options
   * @return string
   */
  public function text_link($options) {
      $html = "<" . $options["tag"] . $options['current'] . ' data-slug="' . $options['data_slug'];
      foreach($options["atts"] as $att => $value ) {
        $html.= ' ' . $att . '="' . $value . '"';
      }
      $html.= '">'."\n";
      $html.= '  <a href="' . url($options['routing'],$options['route']) . '">' . $options['display_title'] . "</a>\n";
      $html.= '</' . $options["tag"] . ">\n";
      return $html;
  }
} // End ExhibitMicrosite_Nav class.
