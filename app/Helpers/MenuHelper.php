<?php

function setActive(string $route_name)
{
    return Route::currentRouteNamed( $route_name ) ?  'active' : '';
}

function setOpen(string $route_name)
{
    return Route::currentRouteNamed( $route_name ) ?  'nav-item-expanded nav-item-open' : '';
}