<?php

/**
 * Intercept get_theme_option calls to allow theme settings on a per-Exhibit basis.
 *
 * @param string $themeOptions Serialized array of theme options
 */
function microsite_theme_options($themeOptions)
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

/**
 * @description If $data is not a string,
 *  then returned value will always be false.
 *  Serialized data is always a string.
 * @param string $data  Required Value to check
 *  to see if was serialized.
 * @param bool $strict Optional Whether to be strict
 *  about the end of the string.
 *  Default: true
 * @return bool
 * https://developer.wordpress.org/reference/functions/is_serialized/
 */
if (!function_exists("is_serialized")) {
  function is_serialized($data, $strict = true)
  {
    // If it isn't a string, it isn't serialized.
    if (!is_string($data)) {
      return false;
    }
    $data = trim($data);
    if ("N;" === $data) {
      return true;
    }
    if (strlen($data) < 4) {
      return false;
    }
    if (":" !== $data[1]) {
      return false;
    }
    if ($strict) {
      $lastc = substr($data, -1);
      if (";" !== $lastc && "}" !== $lastc) {
        return false;
      }
    } else {
      $semicolon = strpos($data, ";");
      $brace = strpos($data, "}");
      // Either ; or } must exist.
      if (false === $semicolon && false === $brace) {
        return false;
      }
      // But neither must be in the first X characters.
      if (false !== $semicolon && $semicolon < 3) {
        return false;
      }
      if (false !== $brace && $brace < 4) {
        return false;
      }
    }
    $token = $data[0];
    switch ($token) {
      case "s":
        if ($strict) {
          if ('"' !== substr($data, -2, 1)) {
            return false;
          }
        } elseif (!str_contains($data, '"')) {
          return false;
        }
      // Or else fall through.
      case "a":
      case "O":
      case "E":
        return (bool) preg_match("/^{$token}:[0-9]+:/s", $data);
      case "b":
      case "i":
      case "d":
        $end = $strict ? '$' : "";
        return (bool) preg_match("/^{$token}:[0-9.E+-]+;$end/", $data);
    }
    return false;
  }
}

/**
 * @description Unserializes data only if it was serialized.
 * @param string $data Required
 * @return mixed Unserialized data can be any type.
 * https://developer.wordpress.org/reference/functions/maybe_unserialize/
 */
if (!function_exists("maybe_unserialize")) {
  function maybe_unserialize($data)
  {
    if (is_serialized($data)) {
      // Don't attempt to unserialize data that wasn't serialized going in.
      return @unserialize(trim($data));
    }

    return $data;
  }
}

if (!function_exists("currentExhibitThemeOptions")) {
  function currentExhibitThemeOptions()
  {
    try {
      $exhibit = get_current_record("exhibit", false);
      if ($exhibit) {
        $exhibitThemeOptions = $exhibit->getThemeOptions();
        if (!empty($exhibitThemeOptions)) {
          return $exhibitThemeOptions;
        }
      } else {
        return [];
      }
    } catch (Zend_Exception $e) {
      // no view available
    }
  }
}
if (!function_exists("themePalette")) {
  function themePalette()
  {
    $options = currentExhibitThemeOptions();
    if (isset($options["palette"])) {
      $str = str_replace(["#", " "], "", $options["palette"]);
      $str = "#" . str_replace(",", ",#", $str);
      $vals = explode(",", $str);
      foreach ($vals as $key => $val) {
        if (strlen($val) !== 7) {
          unset($vals[$key]);
        }
      }
      return $vals;
    }
    return [];
  }
}

if (!function_exists("collectionIds")) {
  function collectionIds()
  {
    $options = currentExhibitThemeOptions();
    if (isset($options["collection_ids"])) {
      $vals = explode(",", $options["collection_ids"]);
      foreach ($vals as $key => $val) {
        $vals[$key] = trim($val);
        if (!is_numeric($vals[$key])) {
          unset($vals[$key]);
        }
      }
      return $vals;
    }
    return [];
  }
}

if (!function_exists("exhibit_microsite_citation")) {
  function exhibit_microsite_citation($item, $options)
  {
    //$options["file_id"] = $options["active_file_id"];

    $publication = option("site_title");

    foreach ($options as $key => $row) {
      if (empty($row)) {
        unset($options[$key]);
      }
    }

    $title = metadata($item, ["Dublin Core", "Title"])
      ? metadata($item, ["Dublin Core", "Title"])
      : null;

    $source = metadata($item, ["Dublin Core", "Source"])
      ? " " . metadata($item, ["Dublin Core", "Source"])
      : null;

    $today = date("F j, Y");

    $url =
      WEB_ROOT .
      "/" .
      url(
        $options,
        "/exhibits/show/:exhibit_slug/:page_slug_1/:page_slug_2/items/:item_id/:file_id",
        [],
        false,
        $encode = true
      );
    $citation =
      $title .
      ". <em>" .
      ($source ? $source : $publication) .
      "</em>, accessed " .
      $today .
      ", " .
      $url;
    return $citation;
  }
}

if (!function_exists("convert_to_readable_size")) {
  function convert_to_readable_size($size)
  {
    $base = log($size) / log(1024);
    $suffix = ["", "KB", "MB", "GB", "TB"];
    $f_base = floor($base);
    return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
  }
}

if (!function_exists("emsIdStem")) {
  function emsIdStem($str)
  {
    $str = str_replace("blocks[", "blocks-", $str);
    $str = trim(str_replace(["[", "]"], "-", $str), "_");
    return trim(str_replace(["__", "--"], "-", $str), "-");
  }
}

if (!function_exists("inlineStylesString")) {
  function inlineStylesString($atts)
  {
    if (empty($atts)) {
      return "";
    }
    $str = ' style="';
    foreach ($atts as $prop => $value) {
      $str .= $prop . ":" . $value . ";";
    }
    $str .= '"';
    return $str;
  }
}

if (!function_exists("emsIdStem")) {
  function emsIdStem($str)
  {
    $str = str_replace("blocks[", "blocks-", $str);
    $str = trim(str_replace(["[", "]"], "-", $str), "_");
    return trim(str_replace(["__", "--"], "-", $str), "-");
  }
}

if (!function_exists("emsBreadcrumbArray")) {
  function emsBreadcrumbArray($info)
  {
    $data["landing"] = [
      "url" => url(
        ["action" => "show", "slug" => $info["exhibit"]->slug],
        "ems_exhibitLanding"
      ),
      "title" => $info["exhibit"]->title,
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

/**
 * Echoes " checked" attribute for form checkboxes/radio buttons if value
 * equals or in comparison param.
 * @param mixed $value int or string
 * @param mixed $comparison int/str/array
 */
function isChecked($value, $comparison)
{
  if (is_array($comparison)) {
    if (in_array($value, $comparison)) {
      echo " checked";
    }
  } elseif ($value == $comparison) {
    echo " checked";
  }
}

/**
 * Are all elements of non-empty $array numeric?
 * @param array $data
 * @return boolean
 */
function allNumeric($data)
{
  if (!is_array($data) || empty($data)) {
    return false;
  } else {
    foreach ($data as $elem) {
      if (empty($elem) && 1 == 0) {
        return false;
      } elseif (!is_numeric($elem)) {
        return false;
      }
    }
  }
  return true;
}

/**
 * Returns  element names in an associative array
 * of element id => element name
 * @return array
 */
function ems_element_names()
{
  $data = [];
  $db = get_db();
  $rows = $db
    ->getTable("Element")
    ->fetchAll("SELECT `id`,`name` FROM {$db->prefix}elements");

  foreach ($rows as $element) {
    $data[$element["id"]] = $element["name"];
  }

  ksort($data);
  return $data;
}

function in_arrayi($needle, $haystack)
{
  return in_array(strtolower($needle), array_map("strtolower", $haystack));
}

function ems_search_for_key($needle, $haystack)
{
  foreach ($haystack as $index => $row) {
    if (gettype($row == "object")) {
      $row = (array) get_object_vars($row);
    }
    if (strtolower($row["element_name"]) === strtolower($needle)) {
      return $row;
    }
  }
  return null;
}

/**
 * We want to customize the order of element texts for an item
 * @param object $item Omeka item object.
 * @param array $elements array of element text names to place.
 * @param boolean $start place element names at the start of the
 *  returned array or at the end?
 * @return array
 */
function ems_element_texts($item, $elements = [], $start = 0)
{
  $data = [];
  $ordered = [];
  $sorted = [];
  $elementTexts = $item->getAllElementTexts();
  $elems = ems_element_names();
  foreach ($elementTexts as $row) {
    $row->element_name = isset($elems[$row->element_id])
      ? $elems[$row->element_id]
      : "";

    if (in_arrayi($row->element_name, $elements)) {
      $ordered[] = $row;
    } else {
      $data[] = $row;
    }
  }

  // Create the sorted elements array
  foreach ($elements as $element_name) {
    $sorted[] = ems_search_for_key($element_name, $ordered);
  }

  if ($start === 1) {
    return array_merge($sorted, $data);
  } else {
    return array_merge($data, $sorted);
  }
}

/**
 * Loop through an array of attribute name value pairs and return a
 * a string
 * @param array $atts
 * @return string
 */
function ems_array_to_attributes($atts = [])
{
  $str = "";
  if (!is_array($atts)) {
    return $str;
  }
  foreach ($atts as $key => $val) {
    $str .= " " . $key . '="' . $val . '"';
  }
  return $str;
}
