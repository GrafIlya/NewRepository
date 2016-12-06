<?php
class View_Class{

    private $_conf; # ��������� � �������������
    
    private $_layout = ''; # ��������� �������
    private $_view = '';   # ��������� ����

    private $_vars = array(); # ������ ��� �������� �����
    private $_render; #������ ���� ��� ���� ����

    public function render($title, $meta_k = "", $meta_d = "", $render = true){
        if($render===false) # �������� ����� �������� �������
            $this->_render = false; # ���� ���� ����� ��������� ���� �� ���� ��������� _render
        if($this->_render === false) # ���� _render = ���� ������ ����
            return false;

        $ext = $this->_conf->get('view_ext'); # ������ �� _conf �� ����� 'view_ext' �������� ����������
        
        $this->_layout = APP_PATH.DS.'View'.DS.'_layout'.DS.$this->_layout.$ext; # ��������� ���� ��� �������
        $this->_view = APP_PATH.DS.'View'.DS.$this->_view.$ext; # ��������� ���� ��� ����
		if (!file_exists($this->_view))
		{
			$text = '�������� � ����� ������� �� ����������. <br /> <br /> ��� ������ 4310 -  Error load controller and action.';
			$this->_view = APP_PATH.DS.'View'.DS.'Pages'.DS.'error'.$ext; # ��������� ���� ��� ����
		}

        unset ($ext,$render); # ��������� $ext � $render
        extract($this->_vars, EXTR_OVERWRITE); #��� ���������� _vars ���������� ����������

        ob_start(); #��������� ��� � �����
        include $this->_view; # ���������� ����
        $content_for_layout = ob_get_clean(); # ���������� � ���������� �� ��� �������� � ������ � ������ �����
		
		$files = scandir(APP_PATH.DS.'View'.DS.'_element'.DS);
		foreach($files as $val)
		{
			if($val!="." and $val != "..")
			{
				ob_start(); #����� ��� � �����
				include APP_PATH.DS.'View'.DS.'_element'.DS.$val; # ���������� ���� ����
				$variable[basename($val,".php")] = ob_get_clean();
			}
		}
		extract($variable, EXTR_OVERWRITE, "new_");

		
        if($this->_conf->get('qz_output')===1)
            ob_start ();

        else
            ob_start(); #���������, ���� ��� ��������
    
        include_once $this->_layout; # ��������� ����� ������� � �����
  
        header('Content-length: '.ob_get_length()); # �������� ����� ���� �������� ����� ��������
        $this->_render = false; # ���������� _render
    }
	
    public function __construct($layout ='',$view = '') { # ����������� ������
        $this->_conf = config::instance(); # ���������� _conf �� ����������� ���������
        $this->_layout = !empty($layout) ? $layout :
            $this->_conf->get('default_layout'); # ���� $layout ������ �� �� ����� �������� ������ �� �����
        if(!empty ($view)){
            $this->_view = $view; # ���� $view �� ������ �� ������� � _view
        } else {
            $router = Routing_Router::instance();
            $this->_view = className2fileName($router->controller())
                    .DS.$router->action(); # ����� �����-�� ����� � �������
        }
    }
    
	public function set($var, $value = ''){ # ��� _vars
        if(is_array($var)){
            $keys = array_keys($var);
            $values = array_values($var);
            $this->_vars = array_merge($this->_vars,  
                                        array_combine($keys, $values));
        }else {
            $this->_vars[$var] = $value;
        }
    }
    
    public function __set($key,$value){ # ��� _vars
        $this->_vars[$key] = $value;        
    }

    public function view($view){
        $this->_view = $view;
    }
    
	public function layout($layout){
        $this->_layout = $layout;
    }
}