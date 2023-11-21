<?php

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


