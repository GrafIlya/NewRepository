<?php
class Cache_Class
{
    private static $_instance;
    private function __construct(){}
    private function __clone(){}
    public static function instance ()
    {
        if(!isset(self::$_instance)) self::$_instance = new self();
        return self::$_instance;
    }
    public function set($id, $data, $lifetime = 3600)
    {
        $cacheFile = $this->cacheFullName($id);
        file_put_contents($cacheFile, serialize($data));
        touch($cacheFile, (time() + intval($lifetime)));
        if(!is_file(CACHE_ROOT.DS.'cache_clean'))
        {
            file_put_contents(CACHE_ROOT.DS.'cache_clean', '');
            touch(CACHE_ROOT.DS.'cache_clean' ,(time() + intval(Config::instance()->get('cache_lifetime'))));
        }
    }
    public function get($id)
    {

        if(is_file(CACHE_ROOT.DS.'cache_clean') AND filemtime(CACHE_ROOT.DS.'cache_clean') < time()) $this->clean();
        $cacheFile = $this->cacheFullName($id); # по id получаем полное имя файла
        if (file_exists($cacheFile))
        {
            if(filemtime($cacheFile) < time()) $this->delete($id);
            else return unserialize(file_get_contents($cacheFile));
        }
        return false;
    }
    public function delete($id)
    {
        $cacheFile = $this->cacheFullName($id);
        unlink($cacheFile);
    }
    private function cacheFullName($id)
    {
        return CACHE_ROOT.DS.rawurlencode($id).'.cache';
    }
    public function clean()
    {
        $files = scandir(CACHE_ROOT);
        foreach ($files as $file)
        {
            if (($file !== '.' ) AND ($file !== '..')) unlink(CACHE_ROOT.DS.$file);
        }
    }
}