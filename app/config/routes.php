<?php
$route = Routing_Router::instance(); // инстанс класса роутер

#$route->connect('page/(\d*)','Pages/index/$1');


$r = explode('/', $_SERVER['REQUEST_URI']); # строка $route разбивается на подстроки по разделителю /
array_shift($r); // возвращает 1 значение массива и удаляет со сдвигом
$route->connect($r[0],'Pages/'.$r[0]);
$route->connect($r[0].'/(.*)','Pages/'.$r[0].'/$1');

$route->connect('','Pages/start');

unset($route);
