<?php
/**
 * @package ExhibitMicrosite
 * @category plugin
 */

include_once "functions.php";

class ExhibitMicrositePlugin extends Omeka_Plugin_AbstractPlugin
{
  protected $_hooks = ["install", "uninstall","admin_head","public_head", "define_routes"];
  protected $_filters = ["exhibit_layouts","public_theme_name"];

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
    include "config_form.php";
  }
  
  public function hookPublicHead()
    {
      //;
    }
    
    
    
    /**
    * Display the CSS style and javascript for the exhibit in the admin head
    */
     public function hookAdminHead()
      {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        // Check if using Exhibits controller, and add the stylesheet for general display of exhibits
        if ($module == "exhibit-builder" && $controller == "exhibits") {
          queue_css_file(["styles", "palettes"], "screen");
          queue_js_file(["app", "blocks", "palettes"]);
          $options["api"] = get_option("api_enable");
          $options["palette"] = [];
          $options["collection_ids"] = [];
          $json = json_encode($options, JSON_PRETTY_PRINT);
          ?><script>const microsite = <?php echo $json; ?></script><?php
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
    $water["flex-file"] = [
      "name" => "Bootstrap Flex File Block",
      "description" => "A file block for display in a Bootstrap 5 Flex grid.",
    ];

    $water["flex-text"] = [
      "name" => "Bootstrap Flex Text Block",
      "description" => "A text block for display in a Bootstrap 5 Flex grid..",
    ];

    $water["flex-file-text"] = [
      "name" => "Flex File with Text Block",
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

    require_once "routes.php";

    foreach ($routes_data as $key => $data) {
      $router = $args["router"];
      $router->addRoute(
        $data["route"],
        new Zend_Controller_Router_Route($data["route"], [
          "module" => "ExhibitMicrosite",
          "controller" => $data["controller"],
          "action" => $data["action"],
        ])
      );
    }
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
      return [];//$themeOptions;
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
        
    if ($request && $request->getModuleName() == "ExhibitMicrosite") {
      $slug = $request->getParam("exhibit_slug");
      $exhibit = get_db()
        ->getTable("Exhibit")
        ->findBySlug($slug);
      if ($exhibit && $exhibit->theme) {
        // Save result in static for future calls
        $exhibitTheme = $exhibit->theme;
        add_filter("theme_options", [$this,"microsite_theme_options"]);
        return $exhibitTheme;
      }
    }
  
    // Short-circuit any future calls to the hook if we didn't change the theme
    $exhibitTheme = $themeName;
    return $exhibitTheme;
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
      $exhibit = get_record("Exhibit",["public"=>1,"slug" => $request->getParam("exhibit_slug")]);
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
