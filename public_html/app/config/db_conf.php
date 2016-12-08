<?php
$config = Dbconnect::instance();
$config->set(array(
    'host' => 'ovl.io',
    'user' => 'gratera',
    'pass' => '111111',
    'name' => 'gratera'
));
$config->connect();
unset($config);