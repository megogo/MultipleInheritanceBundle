<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

$routes->add('home', new Route('/', array('_controller' => 'ParentBundle:Awesome:index')));

return $routes;