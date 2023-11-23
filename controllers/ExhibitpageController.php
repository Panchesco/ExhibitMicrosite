<?php
use ExhibitMicrosite\helpers\ParamsHelper;
use ExhibitMicrosite\helpers\ExhibitMicrositeHelper;

class ExhibitMicrosite_ExhibitpageController extends
  Omeka_Controller_AbstractActionController
{
  public $exhibit;
  public $exhibitPage;
  public $route;
  public $slug;
  protected $_page_slug_1;
  protected $_page_slug_2;
  protected $_page_slug_3;
  protected $_page;
  protected $_depth;
  protected $_theme_options;
  protected $_page_slugs = [];

  protected function _init()
  {
    // Set the current exhibit;
    $this->_request = Zend_Controller_Front::getInstance()->getRequest();
    $this->route = $this->getFrontController()
      ->getRouter()
      ->getCurrentRouteName();

    $this->slug = $this->_request->getParam("slug");

    if (!$this->exhibit) {
      $this->exhibit = $this->_helper->db
        ->getTable("Exhibit")
        ->findBySlug($this->slug);
    }

    // Set $this->slug to current ExhibitPage slug.
    $this->_page_slug_1 = $this->_request->getParam("page_slug_1");
    $this->_page_slug_2 = $this->_request->getParam("page_slug_2");
    $this->_page_slug_3 = $this->_request->getParam("page_slug_3");
    $this->_page_number = $this->_request->getParam("page_number");
    $this->_depth = 0;

    if ($this->_page_slug_1) {
      $this->slug = $this->_page_slug_1;
      $this->_page_slugs[] = $this->slug;
      $this->_depth++;
    }

    if ($this->_page_slug_2) {
      $this->slug = $this->_page_slug_2;
      $this->_page_slugs[] = $this->slug;
      $this->_depth++;
    }

    if ($this->_page_slug_3) {
      $this->slug = $this->_page_slug_3;
      $this->_page_slugs[] = $this->slug;
      $this->_depth++;
    }

    $this->_theme_options = $this->exhibit->getThemeOptions();
    $this->exhibitPage = get_record("ExhibitPage", ["slug" => $this->slug]);

    $this->view->addScriptPath(
      PUBLIC_THEME_DIR .
        "/" .
        $this->_theme_options["theme_name"] .
        "/exhibit-microsite/views"
    );

    $this->view->addScriptPath(
      PLUGIN_DIR . "/exhibit-microsite/views/exhibit-pages"
    );

    $this->microsite = new ExhibitMicrositeHelper([
      "route" => $this->route,
      "exhbit" => $this->exhibit,
      "exhibitPage" => $this->exhibitPage,
    ]);

    $this->breadcrumb = $this->microsite->breadcrumbHTML();
  }

  public function showAction()
  {
    $this->_init();

    echo $this->view->partial("exhibit-pages/show.php", [
      "exhibit" => $this->exhibit,
      "exhibitPage" => $this->exhibitPage,
      "page_slugs" => $this->_page_slugs,
      "theme_options" => $this->_theme_options,
      "view" => $this->view,
      "breadcrumb" => $this->breadcrumb(),
      "canonicalURL" => $this->microsite->canonicalURL($this->route),
    ]);

    exit();
  }

  public function breadcrumbData()
  {
    $params = new ParamsHelper();
    $data["landing"] = [
      "url" => url(
        ["action" => "show", "slug" => $this->exhibit->slug],
        "ems_exhibitLanding"
      ),
      "title" => $this->exhibit->title,
    ];

    if ($this->_page_slug_1) {
      $data["page_1"] = [
        "url" => url(
          [
            "action" => "show",
            "slug" => $this->exhibit->slug,
            "page_slug_1" => $this->_page_slug_1,
          ],
          "ems_exhibitPage1"
        ),
        "title" => get_record("ExhibitPage", ["slug" => $this->_page_slug_1])
          ->title,
      ];
    }

    if ($this->_page_slug_2) {
      $data["page_2"] = [
        "url" => url(
          [
            "action" => "show",
            "slug" => $this->exhibit->slug,
            "page_slug_1" => $this->_page_slug_1,
            "page_slug_2" => $this->_page_slug_2,
          ],
          "ems_exhibitPage2"
        ),
        "title" => get_record("ExhibitPage", ["slug" => $this->_page_slug_2])
          ->title,
      ];
    }

    if ($this->_page_slug_3) {
      $data["page_3"] = [
        "url" => url(
          [
            "action" => "show",
            "slug" => $this->exhibit->slug,
            "page_slug_1" => $this->_page_slug_1,
            "page_slug_2" => $this->_page_slug_2,
            "page_slug_3" => $this->_page_slug_3,
          ],
          "ems_exhibitPage3"
        ),
        "title" => get_record("ExhibitPage", ["slug" => $this->_page_slug_3])
          ->title,
      ];
    }
    return $data;
  }
}
