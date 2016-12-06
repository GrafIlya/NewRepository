<?php

define('APP_PATH', realpath('../')); // путь до приложения

define('LIB_PATH', realpath('../../lib')); // путь до библиотек и классов

define('DS',DIRECTORY_SEPARATOR); // разделитель директорий

define('CACHE_ROOT',APP_PATH.DS.'temp'.DS.'cache'); // путь кэша
define('LOGS_ROOT',APP_PATH.DS.'temp'.DS.'logs'); // путь логов
define('TEMP',APP_PATH.DS.'temp'); // путь временных файлов

define('TABLE_PREFIX',''); // префикс таблиц
define('SERVER','http://corp.ovl.io/'); // имя домена
ini_set('session.cookie_lifetime', 0); // установка времени жизни куки

include_once APP_PATH.DS.'config'.DS.'bootstrap.php'; // начнинаем собирать приложение
