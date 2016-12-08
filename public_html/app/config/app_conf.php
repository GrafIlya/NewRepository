<?php
error_reporting(E_ALL ^ E_NOTICE);
$cnf = Config::instance();
$cnf->set('base_uri','');
$cnf->set('dev_mode',0);
$cnf->set('view_ext','.php');
$cnf->set('default_layout','default');
$cnf->set('qz_output',1);
$cnf->set('errors_in_files',1);
$cnf->set('cache_lifetime',60*60*24);
unset($cnf);