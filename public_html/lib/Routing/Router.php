<?php
Class Routing_Router
{
    private static $_instance;
    private $_routes = array();
    private function __construct() {}
    private function __clone(){}
    public static function instance()
    {
        if (empty (self::$_instance)) self::$_instance = new self();
        return self::$_instance;
    }
    public function connect($urlPattern, $route)
    {
        $this->_routes[$urlPattern] = $route;
    }
    private $_controller = '';
    private $_action = '';
    private $_params = array();
    public function getRoute($uri)
    {
        $routes = $this->_routes;
        $baseUri = trim(Config::instance()->get('base_uri'),'/');
        $uri = ltrim(substr(trim($uri, '/'),  strlen($baseUri)),'/');
        foreach($routes as $rUri => $rRoute)
        {
            $pattern = '`^'.$rUri.'$`i';
            if(preg_match($pattern, $uri))
            {
                $route = preg_replace($pattern, $rRoute, $uri);
                break;
            }
        }
        if(!isset($route)) return false;
        $route = explode('/', $route);
        $this->_controller = ucfirst(array_shift($route));
        $this->_action = array_shift($route);
        $this->_params = $route;
        return array(
            'controller' => $this->_controller,
            'action' => $this->_action,
            'params' => $this->_params
        );
    }
    public function controller()
    {
        return $this->_controller;
    }
    public function action()
    {
        return $this->_action;
    }
    public function params()
    {
        return $this->_params;
    }
}