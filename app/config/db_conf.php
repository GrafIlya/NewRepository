<?php
$config = Dbconnect::instance(); // инстанс класса конфиг
$config->set(array(
    'host' => '',
    'user' => '',
    'pass' => '',
    'name' => ''
));
$config->connect(); // коннектимся

unset($config); // удалим инстанс
