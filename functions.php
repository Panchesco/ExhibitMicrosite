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
