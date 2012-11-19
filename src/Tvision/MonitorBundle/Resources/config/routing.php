<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();
$collection->add('tvision_monitor_homepage', new Route('/hello/{name}', array(
    '_controller' => 'TvisionMonitorBundle:Default:index',
)));

return $collection;
