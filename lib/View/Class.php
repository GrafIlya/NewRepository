<?php
class View_Class{

    private $_conf; # перменная с конфигурацией
    
    private $_layout = ''; # пременная шаблона
    private $_view = '';   # пременная вида

    private $_vars = array(); # массив для хранения видов
    private $_render; #рендер либо тру либо фалс

    public function render($title, $meta_k = "", $meta_d = "", $render = true){
        if($render===false) # проверка какой параметр передан
            $this->_render = false; # если фолс тогда запсываем фолс во флаг состояния _render
        if($this->_render === false) # если _render = фолс вернет фолс
            return false;

        $ext = $this->_conf->get('view_ext'); # достаём из _conf по ключу 'view_ext' значение расширения
        
        $this->_layout = APP_PATH.DS.'View'.DS.'_layout'.DS.$this->_layout.$ext; # формируем путь для шаблона
        $this->_view = APP_PATH.DS.'View'.DS.$this->_view.$ext; # формируем путь для вида
		if (!file_exists($this->_view))
		{
			$text = 'Страница с таким адресом не существует. <br /> <br /> Код ошибки 4310 -  Error load controller and action.';
			$this->_view = APP_PATH.DS.'View'.DS.'Pages'.DS.'error'.$ext; # формируем путь для вида
		}

        unset ($ext,$render); # выгружаем $ext и $render
        extract($this->_vars, EXTR_OVERWRITE); #при совпадении _vars происходит перезапись

        ob_start(); #сохраняем все в буфер
        include $this->_view; # подключаем файл
        $content_for_layout = ob_get_clean(); # записываем в переменную то что хранится в буфере и чистим буфер
		
		$files = scandir(APP_PATH.DS.'View'.DS.'_element'.DS);
		foreach($files as $val)
		{
			if($val!="." and $val != "..")
			{
				ob_start(); #опять все в буфер
				include APP_PATH.DS.'View'.DS.'_element'.DS.$val; # подключаем файл вида
				$variable[basename($val,".php")] = ob_get_clean();
			}
		}
		extract($variable, EXTR_OVERWRITE, "new_");

		
        if($this->_conf->get('qz_output')===1)
            ob_start ();

        else
            ob_start(); #включение, если оно оключено
    
        include_once $this->_layout; # включение файла шаблона в буфер
  
        header('Content-length: '.ob_get_length()); # отправка шапки куда записана длина контента
        $this->_render = false; # выключение _render
    }
	
    public function __construct($layout ='',$view = '') { # конструктор класса
        $this->_conf = config::instance(); # заполнение _conf из статической пременной
        $this->_layout = !empty($layout) ? $layout :
            $this->_conf->get('default_layout'); # если $layout пустой то по ключу получаем шаблон по умолч
        if(!empty ($view)){
            $this->_view = $view; # если $view не пустой то заносим в _view
        } else {
            $router = Routing_Router::instance();
            $this->_view = className2fileName($router->controller())
                    .DS.$router->action(); # иначе какая-то жесть с роутами
        }
    }
    
	public function set($var, $value = ''){ # для _vars
        if(is_array($var)){
            $keys = array_keys($var);
            $values = array_values($var);
            $this->_vars = array_merge($this->_vars,  
                                        array_combine($keys, $values));
        }else {
            $this->_vars[$var] = $value;
        }
    }
    
    public function __set($key,$value){ # для _vars
        $this->_vars[$key] = $value;        
    }

    public function view($view){
        $this->_view = $view;
    }
    
	public function layout($layout){
        $this->_layout = $layout;
    }
}