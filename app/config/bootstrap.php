<?php

$includePath = array(LIB_PATH, APP_PATH.DS.'classes', get_include_path()); // парсинг путей для инклюдов
$includePath = implode(PATH_SEPARATOR,$includePath); //собираем путь со слешами
set_include_path($includePath); // установка дефолтного пути для инклюдов

require_once 'PEAR'.DS.'NameScheme'.DS.'Autoload.php'; // прицепили автозагрузчик

include_once APP_PATH.DS.'config'.DS.'app_conf.php'; // конфиг приложения
include_once APP_PATH.DS.'config'.DS.'routes.php'; // наши роуты
include_once LIB_PATH.DS.'function.php'; # подключение функции className2fileName и др. вспомогательных функций
include_once APP_PATH.DS.'config'.DS.'db_conf.php'; // конфиг дб

$router = Routing_Router::instance(); // инстанс класса роутеров
$route = $router->getRoute($_SERVER['REQUEST_URI']); // получаем текущий путь и направляем в класс на обработку

errorReporting(); // кастом функция вывода ошибок
dispatch($route); # запускаем функцию dispatch


 # запуск функции отчёта об ошибках
/* При dev_mode = 0 в зависимости от errors_in_files будут или не будут печататься
 * ошибки в файл
 * При dev_mode = 1, errors_in_files не влияет
 */
