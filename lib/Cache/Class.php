<?php
class Cache_Class{

private static $_instance;

private function __construct(){} # конструктор пустой
private function __clone(){} # клонирование пустое

public static function instance () {
    if(!isset(self::$_instance))
        self::$_instance = new self();
    return self::$_instance;
}

public function set($id, $data, $lifetime = 3600){ # устанавливает время жизни данных


$cacheFile = $this->cacheFullName($id); # по id получаем полное имя файла
file_put_contents($cacheFile, serialize($data)); # производим запись в файл
touch($cacheFile, (time() + intval($lifetime))); # устанавливает время модификации файла на текущее вместе с интервалом

if(!is_file(CACHE_ROOT.DS.'cache_clean')){ # если файл не существует
    file_put_contents(CACHE_ROOT.DS.'cache_clean', ''); # создаём его
    touch(CACHE_ROOT.DS.'cache_clean' ,
    (time() + intval(Config::instance()->get('cache_lifetime'))));  # устанавливает время редактирования файла
}
}

public function get($id){ # загружаем данные из кэша

if(is_file(CACHE_ROOT.DS.'cache_clean') # если файл существует и время последнего именения меньше чем текущее
    AND filemtime(CACHE_ROOT.DS.'cache_clean') < time())
{
    $this->clean(); # производим очищение
}

$cacheFile = $this->cacheFullName($id); # по id получаем имя файла
if (file_exists($cacheFile)){ # при существовании файла
    if(filemtime($cacheFile) < time()) # и времени существовании меньше текущего времени
        $this->delete($id); # удаляем кэш файл по id
    else
        return unserialize(file_get_contents($cacheFile));
        # возвращаем значение из полученного файла
}
return false; # возвращаем false если файл не существует
}

public function delete($id){ 
$cacheFile = $this->cacheFullName($id); # получаем путь к файлу по id
unlink($cacheFile); # удаляем файл
}

private function cacheFullName($id) { # имя кэш файла
    return CACHE_ROOT.DS.rawurlencode($id).'.cache';
}

public function clean() {
$files = scandir(CACHE_ROOT);#скинируем списко кэша
foreach ($files as $file){# Прокручиваем список в цикле
if (($file !== '.' ) AND ($file !== '..'))# Удаляем все содержимое
    unlink(CACHE_ROOT.DS.$file);
}

}
}