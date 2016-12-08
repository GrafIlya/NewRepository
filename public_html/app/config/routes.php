<?php
$route = Routing_Router::instance();
$r = explode('/', $_SERVER['REQUEST_URI']);
array_shift($r);
$route->connect($r[0],'Pages/'.$r[0]);
$route->connect($r[0].'/(.*)','Pages/'.$r[0].'/$1');
$route->connect('','Pages/index');
unset($route);