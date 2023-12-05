<?php
/**
 * @package ExhibitMicrosite
 * @category plugin
 */

if (!defined("EXHIBIT_MICROSITE_PLUGIN_DIR")) {
  define("EXHIBIT_MICROSITE_PLUGIN_DIR", dirname(__FILE__));
}

//require_once PLUGIN_DIR . "/ExhibitBuilder/helpers/ExhibitFunctions.php";
//require_once PLUGIN_DIR . "/ExhibitBuilder/helpers/ExhibitPageFunctions.php";
require_once EXHIBIT_MICROSITE_PLUGIN_DIR . "/functions.php";
require_once EXHIBIT_MICROSITE_PLUGIN_DIR . "/helpers/ParamsHelper.php";
require_once EXHIBIT_MICROSITE_PLUGIN_DIR .
  "/helpers/ExhibitMicrositeHelper.php";
require_once EXHIBIT_MICROSITE_PLUGIN_DIR . "/helpers/BreadcrumbHelper.php";

class ExhibitMicrositePlugin extends Omeka_Plugin_AbstractPlugin
{
  public $options;
  protected $_hooks = [
    "install",
    "config_form",
    "define_acl",
    "uninstall",
    "define_routes",
    "admin_head",
    "after_delete_record",
  ];
  protected $_filters = [
    "exhibit_layouts",
    "public_theme_name",
    "admin_navigation_main",
    "item_citation",
    "public_navigation_admin_bar",
  ];

  public function filterPublicNavigationAdminBar($args)
  {
    return $args;
  }

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
  }

  public function hookConfigForm()
  {
    require EXHIBIT_MICROSITE_PLUGIN_DIR . "/config_form.php";
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
    if ($controller == "exhibits") {

      queue_css_file(["styles", "palettes"], "screen");
      queue_js_file(["palettes"]);

      $view = get_view();
      $view->addScriptPath(
        EXHIBIT_MICROSITE_PLUGIN_DIR . "/views/shared/exhibit_layouts"
      );

      // Todo - These values need to be stored via the config form for the plugin.
      // So users can edit.
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
    $ems["flex-text"] = [
      "name" => "Flex Text Block",
      "description" => "A text block for display in a Bootstrap 5 Flex grid.",
    ];

    $ems["flex-file"] = [
      "name" => "Flex File Block",
      "description" => "A file block for display in a Bootstrap 5 Flex grid.",
    ];

    $ems["flex-file-text"] = [
      "name" => "Flex File with Text Block",
      "description" =>
        "A file with text block for display in a Bootstrap 5 Flex grid..",
    ];

    $ems["flex-gallery"] = [
      "name" => "Flex Slider Gallery",
      "description" => "Slider gallery with square thumbnails",
    ];

    return array_merge($ems, $layouts);
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
        add_filter("theme_options", "microsite_theme_options");
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
    // Get the microsite IDs from the options table.
    $ems = [];
    $db = get_db();
    $sql = "SELECT id,name,value FROM `{$db->prefix}options` WHERE 1 AND name REGEXP 'exhibit_microsite\\[\[0-9]+\\]'";
    $rows = $db->getTable("Option")->fetchAll($sql);
    if ($rows) {
      foreach ($rows as $row) {
        $this->options = maybe_unserialize($row["value"]);

        if (isset($row["name"])) {
          $id = str_replace(["exhibit_microsite[", "]"], "", $row["name"]);
          $exhibit = get_record_by_id("Exhibit", $id);
          if ($exhibit) {
            $ems[$exhibit->slug] = $exhibit->id;
          }
        }
      }
    }
    // We need to confirm the current request is a microsite before adding the routes.
    $uri = htmlentities($_SERVER["REQUEST_URI"]);
    foreach ($ems as $slug => $exhibit_id) {
      if (strpos($uri, $slug, 0) !== false) {
        return true;
      }
    }
    return false;
  }
} // End ExhbitMicrosite Class
