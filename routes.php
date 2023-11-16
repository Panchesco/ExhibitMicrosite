<?php

$routes_data = [
  [
    "route" => "/exhibits/show/:slug/:page_slug_1",
    "module" => "exhibit-microsite",
    "controller" => "page",
    "action" => "show",
  ],  [ // Landing Page
    "route" => "/exhibits/show/:slug",
    "module" => "exhibit-microsite",
    "controller" => "exhibit",
    "action" => "show",
  ],
];
