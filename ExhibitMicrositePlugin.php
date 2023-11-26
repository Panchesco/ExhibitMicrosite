<?php
/**
 * @package ExhibitMicrosite
 * @category plugin
 */

if (!defined("EXHIBIT_MICROSITE_PLUGIN_DIR")) {
  define("EXHIBIT_MICROSITE_PLUGIN_DIR", dirname(__FILE__));
}

require_once PLUGIN_DIR . "/ExhibitBuilder/helpers/ExhibitFunctions.php";
require_once PLUGIN_DIR . "/ExhibitBuilder/helpers/ExhibitPageFunctions.php";
require_once EXHIBIT_MICROSITE_PLUGIN_DIR . "/functions.php";
require_once EXHIBIT_MICROSITE_PLUGIN_DIR . "/helpers/ParamsHelper.php";
require_once EXHIBIT_MICROSITE_PLUGIN_DIR .
  "/helpers/ExhibitMicrositeHelper.php";

class ExhibitMicrositePlugin extends Omeka_Plugin_AbstractPlugin
{
  protected $_hooks = [
    "install",
    "config",
    "config_form",
    "define_acl",
    "uninstall",
    "admin_head",
    "public_head",
    "define_routes",
    "after_delete_record",
  ];
  protected $_filters = [
    "exhibit_layouts",
    "public_theme_name",
    "admin_navigation_main",
    "item_citation",
  ];

  protected function hookInstall()
  {
    //$this->_installOptions();
  }
  public function hookUninstall()
  {
    //$this->_uninstallOptions();
  }

  public function hookConfig($args)
  {
    set_option(
      "microsite_exhibit_exhibits",
      $_POST["microsite_exhibit_exhibits"]
    );
  }

  public function hookConfigForm()
  {
    require EXHIBIT_MICROSITE_PLUGIN_DIR . "/config_form.php";
  }

  public function hookPublicHead()
  {
    //;
  }

  /**
   * Delete the options record for a microsite if the exhibit
   * it is associated with is deleted.
   */
  public function hookAfterDeleteRecord($args)
  {
    if ($args["record"]->record_type == "Exhibit") {
      delete_option("exhibit_microsite[" . $args["record"]->record_id . "]");
    }
  }

  /**
   * Display the CSS style and javascript for the exhibit in the admin head
   */
  public function hookAdminHead()
  {
    $request = Zend_Controller_Front::getInstance()->getRequest();
    $module = $request->getModuleName();
    $controller = $request->getControllerName();

    queue_js_file(["app"]);
    // Check if using Exhibits controller, and add the stylesheet for general display of exhibits
    if ($module == "exhibit-microsite" && $controller == "exhibits") {

      queue_css_file(["styles", "palettes"], "screen");
      queue_js_file(["palettes"]);

      $view = get_view();
      $view->addScriptPath(
        EXHIBIT_MICROSITE_PLUGIN_DIR . "/views/shared/exhibit_layouts"
      );

      $options["api"] = get_option("api_enable");
      $options["palette"] = [
        "#8B0015",
        "#AB0520",
        "#9D5A20",
        "#EBD999",
        "#F4EDE5",
        "#4A634E",
        "#001c48",
        "#0c234b",
        "#1E5288",
        "#E2E9EB",
        "#222222",
        "#212529",
        "#343A40",
        "#495057",
        "#6C757D",
        "#ADB5BD",
        "#CED4DA",
        "#DEE2E6",
        "#E9ECEF",
        "#F8F9FA",
        "#FFFFFF",
      ];
      $options["collection_ids"] = [];
      $json = json_encode($options, JSON_PRETTY_PRINT);
      ?><script>const exhibitMicrosite = <?php echo $json; ?></script><?php
    }
  }

  /**
   * Adds Bootstrap 5 Flex friendly Exhibit Blocks.
   * @param array $layouts This is the ExhibitBuilder layouts
   * array the plugin will add the theme blocks to
   * the available blocks on the ExhibitPage add/edit screens.
   * @return array
   */
  public function filterExhibitLayouts($layouts)
  {
    $water["flex-text"] = [
      "name" => "Bootstrap Flex Text Block",
      "description" => "A text block for display in a Bootstrap 5 Flex grid.",
    ];

    $water["flex-file"] = [
      "name" => "Bootstrap Flex File Block",
      "description" => "A file block for display in a Bootstrap 5 Flex grid.",
    ];

    $water["flex-file-text"] = [
      "name" => "Bootstrap Flex File with Text Block",
      "description" =>
        "A file with text block for display in a Bootstrap 5 Flex grid..",
    ];

    return array_merge($water, $layouts);
  }

  function hookDefineRoutes($args)
  {
    //Don't add these routes on the admin side to avoid conflicts.
    if (is_admin_theme()) {
      return;
    }

    // Don't add these route if this isn't a microsite.
    if ($this->_is_microsite() === false) {
      return;
    }

    $router = $args["router"];
    $router->addConfig(
      new Zend_Config_Ini(
        EXHIBIT_MICROSITE_PLUGIN_DIR . "/routes.ini",
        "routes"
      )
    );
  }

  public function filterThemeOptions($options, $args)
  {
    $request = Zend_Controller_Front::getInstance()->getRequest();
    $module = $request->getModuleName();

    // if (Omeka_Context::getInstance()->getRequest()->getModuleName() == 'exhibit-builder' && function_exists('__v')) {
    //     if ($exhibit = exhibit_builder_get_current_exhibit()) {
    //         $exhibitThemeOptions = $exhibit->getThemeOptions();
    //     }
    // }
    // if (!empty($exhibitThemeOptions)) {
    //     return serialize($exhibitThemeOptions);
    // }
    return []; //$themeOptions;
  }

  /**
   * Define the ACL.
   *
   * @param Omeka_Acl
   */
  public function hookDefineAcl($args)
  {
    $acl = $args["acl"];

    $indexResource = new Zend_Acl_Resource("ExhibitMicrosite_Index");
    $acl->add($indexResource);

    $acl->allow(["super", "admin"], "ExhibitMicrosite_Index", [
      "show",
      "browse",
      "add",
      "edit",
      "delete-confirm",
    ]);
  }

  /**
   * Add the Exhibit Microsites link to the admin main navigation.
   *
   * @param array Navigation array.
   * @return array Filtered navigation array.
   */
  public function filterAdminNavigationMain($nav)
  {
    $nav[] = [
      "label" => __("Exhibit Microsites"),
      "uri" => url("exhibit-microsite"),
      "resource" => "ExhibitMicrosite_Index",
      "privilege" => "browse",
    ];
    return $nav;
  }

  /**
   * Filter for changing the public theme between exhibits.
   *
   * @param string $themeName "Normal" current theme.
   * @return string Theme that will actually be used.
   */
  function filterPublicThemeName($themeName)
  {
    static $exhibitTheme;

    if ($exhibitTheme) {
      return $exhibitTheme;
    }

    $request = Zend_Controller_Front::getInstance()->getRequest();

    if ($request && $request->getModuleName() == "exhibit-microsite") {
      $slug = $request->getParam("slug");
      $exhibit = get_db()
        ->getTable("Exhibit")
        ->findBySlug($slug);
      if ($exhibit && $exhibit->theme) {
        // Save result in static for future calls
        $exhibitTheme = $exhibit->theme;
        add_filter("theme_options", [$this, "microsite_theme_options"]);
        return $exhibitTheme;
      }
    }

    // Short-circuit any future calls to the hook if we didn't change the theme
    $exhibitTheme = $themeName;

    return $exhibitTheme;
  }

  /**
   * Filters the item citation to reflect the current URL hierarchy.
   * @param string $citation
   * @param array $args
   * @return string.
   */
  function filterItemCitation($citation, $args)
  {
    if (isset($args["item"]) && is_object($args["item"])) {
      $site_title = get_option("site_title");
      $item = $args["item"];
      $citation = "“";
      $citation .= metadata("item", "rich_title", ["no_escape" => true]);
      $citation .= ",” ";
      $citation .= "<em>{$site_title}</em>, ";
      $citation .= date("F j, Y") . ", ";
      $citation .= WEB_ROOT . current_url();
    }
    return $citation;
  }

  /**
   * Confirms the current URI is that of a microsite.
   * @return boolean.
   */
  protected function _is_microsite()
  {
    // We need to confirm the current request is a microsite before adding the routes.
    $microsites = @unserialize(get_option("exhibit_microsite_exhibits"));
    $uri = htmlentities($_SERVER["REQUEST_URI"]);
    foreach ($microsites as $slug => $exhibit_id) {
      if (strpos($uri, $slug, 0) !== false) {
        return true;
      }
    }
    return false;
  }

  /**
   * Intercept get_theme_option calls to allow theme settings on a per-Exhibit basis.
   *
   * @param string $themeOptions Serialized array of theme options
   * @param string $args Unused here
   */
  function microsite_theme_options($themeOptions, $args)
  {
    $request = Zend_Controller_Front::getInstance()->getRequest();
    try {
      $exhibit = get_record("Exhibit", [
        "public" => 1,
        "slug" => $request->getParam("slug"),
      ]);
      if ($exhibit) {
        $exhibitThemeOptions = $exhibit->getThemeOptions();
        if (!empty($exhibitThemeOptions)) {
          return serialize($exhibitThemeOptions);
        }
      }
    } catch (Zend_Exception $e) {
      // no view available
    }
    return $themeOptions;
  }
} // End ExhbitMicrosite Class
