<?php
class Dbconnect{
    private static $_instance;
    private static $_idConnect;

    private $_configs = array();

    private function __construct(){}
    private function __clone(){}

    public static function instance () {
    if(empty(self::$_instance))
        self::$_instance = new self();
    return self::$_instance;
	}

    public function set($array){
        $this->_configs = array(
           'host' => $array['host'],
           'user' => $array['user'],
           'pass' => $array['pass'],
           'name' => $array['name']
        );
    }

    public function connect (){

        $this->_idConnect = DbSimple_Generic::connect("mysql://".
        $this->_configs['user'].":".
        $this->_configs['pass']."@".
        $this->_configs['host']."/".
        $this->_configs['name']);

        $this->_idConnect->setIdentPrefix(TABLE_PREFIX);

        if(Config::instance()->get('dev_mode')==0){
            $this->_idConnect->setErrorHandler('databaseErrorHandler');
        }
    }

    public function getConnect(){
        return $this->_idConnect;
    }
}
