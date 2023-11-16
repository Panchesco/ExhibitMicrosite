<?php

$routes_data = [
  [ // Landing Page
    "route" => "/exhibits/show/:exhibit_slug",
    "module" => "ExhibitMicrosite",
    "controller" => "exhibit",
    "action" => "show",
  ],
  [ // Pages
    "route" => "/exhibits/show/:exhibit_slug/:page_slug_1/:page_slug_2/:page_slug_3",
    "module" => "ExhibitMicrosite",
    "controller" => "page",
    "action" => "show",
  ],
  [ 
    "route" => "/exhibits/show/:exhibit_slug/:page_slug_1/:page_slug_2",
    "module" => "ExhibitMicrosite",
    "controller" => "page",
    "action" => "show",
  ],
  [
    "route" => "/exhibits/show/:exhibit_slug/:page_slug_1",
    "module" => "ExhibitMicrosite",
    "controller" => "page",
    "action" => "show",
  ],
  [ // Items
    "route" => "/exhibits/show/:exhibit_slug/:page_slug_1/:page_slug_2/:page_slug_3/item/:item_id",
    "module" => "ExhibitMicrosite",
    "controller" => "item",
    "action" => "show",
  ],
  [ 
    "route" => "/exhibits/show/:exhibit_slug/:page_slug_1/:page_slug_2/item/:item_id",
    "module" => "ExhibitMicrosite",
    "controller" => "item",
    "action" => "show",
  ],
  [
    "route" => "/exhibits/show/:exhibit_slug/:page_slug_1/item/:item_id",
    "module" => "ExhibitMicrosite",
    "controller" => "item",
    "action" => "show",
  ],
  [
    "route" => "/exhibits/show/:exhibit_slug/items/:page_number",
    "module" => "ExhibitMicrosite",
    "controller" => "item",
    "action" => "browse",
  ],
  [
    "route" => "/exhibits/show/:exhibit_slug/items",
    "module" => "ExhibitMicrosite",
    "controller" => "item",
    "action" => "browse",
  ],
];
