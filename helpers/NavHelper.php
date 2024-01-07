<?php
namespace ExhibitMicrosite\Helpers;
use ExhibitMicrosite\Helpers\ExhibitMicrositeHelper;

/**
 * Exhibit Microsite NavHelper class.
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
   * Returns the top level Exhibit Pages for the current exhibit.
   * @return array - array of Omeka ExhibitBuilder Exhibit pages with no parents
   */
  public function topPagesData()
  {
    return $this->top_pages;
  }

  /**
  * Returns HTML for Top level Exhibit Page links
  * @param string $wrapper the html wrapper tag for the links. Default is ul.
  */
  public function topPagesHtml($wrapper = "ul",$options = ["tag" => "li"])
  {
    $wrapper = str_replace(['<','>'],"",$wrapper);
    $wrapper = strtolower($wrapper);
    $options["tag"]= str_replace(['<','>'],"",$options["tag"]);
    $options["tag"] = strtolower($options["tag"]);
    $html = "<{$wrapper}>\n";

    // If the Exhibit for this microsite uses a summary page, start the nav with it.
    if ($this->microsite->exhibit->use_summary_page) {
      if ($this->microsite->options["summary_in_nav"]) {

        $current = ($this->microsite->exhibit->slug == $this->microsite->params->slug && ! $this->microsite->params->page_slug_1) ? "current "   : $this->microsite->exhibit->slug;
        $options["display_title"] = !empty($this->microsite->options["summary_alt_title"])
          ? $this->microsite->options["summary_alt_title"]
          : $this->microsite->exhibit->title;
        $options["route"] = "ems_exhibitLanding";
        $options["routing"]["action"] = "summary";
        $options["routing"]["controller"] = "default";
        $options["routing"]["slug"] = $this->microsite->params->slug;
        $options["atts"] = [ "class" => $current . "nav-ems_exhibitLanding " . $this->microsite->params->slug];
        $options["data_slug"] = $this->microsite->params->slug;
        $html.= $this->text_link($options);
      }
    }

    // Loop through the exhibit top pages and create links for each.
    // To customize this, add a switch statement based on the page slug and
    // pass options to either the image_link() or text_link() methods.
    // Default switch branch is example of options array elements for text link;
    // Search case switch branch is example of options to pass to image link.
    foreach ($this->top_pages as $key => $page) {

      $current = ($page->slug == $this->microsite->params->page_slug_1) ? "current " . $page->slug : $page->slug;
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
          $options["atts"] = ["class" => $current];
          $options["img"]["atts"] = ["id" => "search-icon",
                                      "src" => WEB_RELATIVE_THEME . "/border-narrative/img/icons/search_FILL0_wght400_GRAD0_opsz24.svg",
                                    "title" => __("Search"),
                                  "alt" => __("Search icon")
                                ];
          $html.= $this->image_link($options);
          break;

          case "es":
          $options["routing"]["action"] = "show";
          $options["routing"]["controller"] = "exhibitpage";
          $options["routing"]["slug"] = $this->microsite->params->slug;
          $options["routing"]["page_slug_1"] = $page->slug;
          $options["data_slug"] = $page->slug;
          $options["display_title"] = ($page->short_title) ? $page->short_title: $page->title;
          $options["route"] = "ems_exhibitPage1";
          $options["atts"] = ["class" => $current . " lang"];
          $html.= $this->text_link($options);
          break;

          default:
          $options["routing"]["action"] = "show";
          $options["routing"]["controller"] = "exhibitpage";
          $options["routing"]["slug"] = $this->microsite->params->slug;
          $options["routing"]["page_slug_1"] = $page->slug;
          $options["data_slug"] = $page->slug;
          $options["route"] = "ems_exhibitPage1";
          $options['atts'] = ["class" => $current];
          $options["display_title"] = ($page->short_title) ? $page->short_title: $page->title;
          $html.= $this->text_link($options);
          break;
      }
    }
    $html.="</{$wrapper}";
    return $html;
  }


  /**
   * Return html for an image link.
   * @param array $options
   * @return string
   */
  public function image_link($options) {
      $html = "<" . $options["tag"];
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
   * Return html for a text link.
   * @param array $options
   * @return string
   */
  public function text_link($options) {
      $html = "<" . $options["tag"];
      foreach($options["atts"] as $att => $value ) {
        $html.= ' ' . $att . '="' . $value . '"';
      }
      $html.= '">'."\n";
      $html.= '  <a href="' . url($options['routing'],$options['route']) . '">' . $options['display_title'] . "</a>\n";
      $html.= '</' . $options["tag"] . ">\n";
      return $html;
  }
}
// End ExhibitMicrosite_Nav class.
