<?php
 // функция превода текста с кириллицы в траскрипт
function encodestring($string){
    $table = array(
                'А' => 'A',
                'Б' => 'B',
                'В' => 'V',
                'Г' => 'G',
                'Д' => 'D',
                'Е' => 'E',
                'Ё' => 'Yo',
                'Ж' => 'Zh',
                'З' => 'Z',
                'И' => 'I',
                'Й' => 'J',
                'К' => 'K',
                'Л' => 'L',
                'М' => 'M',
                'Н' => 'N',
                'О' => 'O',
                'П' => 'P',
                'Р' => 'R',
                'С' => 'S',
                'Т' => 'T',
                'У' => 'U',
                'Ф' => 'F',
                'Х' => 'H',
                'Ц' => 'C',
                'Ч' => 'Ch',
                'Ш' => 'Sh',
                'Щ' => 'Csh',
                'Ь' => '',
                'Ы' => 'Y',
                'Ъ' => '',
                'Э' => 'E',
                'Ю' => 'Yu',
                'Я' => 'Ya',

                'а' => 'a',
                'б' => 'b',
                'в' => 'v',
                'г' => 'g',
                'д' => 'd',
                'е' => 'e',
                'ё' => 'yo',
                'ж' => 'zh',
                'з' => 'z',
                'и' => 'i',
                'й' => 'j',
                'к' => 'k',
                'л' => 'l',
                'м' => 'm',
                'н' => 'n',
                'о' => 'o',
                'п' => 'p',
                'р' => 'r',
                'с' => 's',
                'т' => 't',
                'у' => 'u',
                'ф' => 'f',
                'х' => 'h',
                'ц' => 'c',
                'ч' => 'ch',
                'ш' => 'sh',
                'щ' => 'csh',
                'ь' => '',
                'ы' => 'y',
                'ъ' => '',
                'э' => 'e',
                'ю' => 'yu',
                'я' => 'ya',
    );
    $output = str_replace(
        array_keys($table),
        array_values($table),$string
    );
    return $output;
}
function className2fileName($name)
{

    $fromSimple = array('_A','_B','_C','_D','_E','_F','_G','_H',
                        '_I','_J','_K','_L','_M','_N','_O','_P',
                        '_Q','_R','_S','_T','_U','_V','_W','_X',
                        '_Y','_Z');
    $fromCompound = array('A','B','C','D','E','F','G','H',
                        'I','J','K','L','M','N','O','P',
                        'Q','R','S','T','U','V','W','X',
                        'Y','Z','1','2','3','4','5','6','7','8','9','0');
    $toSimple = array(DS.'a',DS.'b',DS.'c',DS.'d',DS.'e',DS.'f',DS.'g',DS.'h',
        DS.'i',DS.'j',DS.'k',DS.'l',DS.'m',DS.'n',DS.'o',DS.'p',DS.'q',DS.'r',
        DS.'s',DS.'t',DS.'u',DS.'v',DS.'w',DS.'x',DS.'y',DS.'z');
   $toCompound = array('_a','_b','_c','_d','_e','_f','_g','_h',
        '_i','_j','_k','_l','_m','_n','_o','_p','_q','_r',
        '_s','_t','_u','_v','_w','_x','_y','_z','_1','_2','_3','_4',
       '_5','_6','_7','_8','_9','_0'); 
   $from = array_merge($fromSimple,$fromCompound);
   $to = array_merge($toSimple,$toCompound);
   $fileName = ltrim(str_replace($from, $to, $name),'_');
   return $fileName; # возвращение изменнёного пути
}
function _strtolower($string)
{
    $small = array('а','б','в','г','д','е','ё','ж','з','и','й',
                   'к','л','м','н','о','п','р','с','т','у','ф',
                   'х','ч','ц','ш','щ','э','ю','я','ы','ъ','ь',
                   'э', 'ю', 'я');
    $large = array('А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й',
                   'К','Л','М','Н','О','П','Р','С','Т','У','Ф',
                   'Х','Ч','Ц','Ш','Щ','Э','Ю','Я','Ы','Ъ','Ь',
                   'Э', 'Ю', 'Я');
    return str_replace($large, $small, $string); 
}
function errorPath($text)
{
    $controllerClass = 'Controller_Pages';
    $refl = new ReflectionClass($controllerClass);
    $controller = $refl->newInstance();
    $action = $refl->getMethod('error');
    $action->invokeArgs($controller, array($text));
    exit();
}
function dispatch($route)
{
    if(empty($route)) errorPath('Страница с таким адресом не существует. <br /> <br /> Код ошибки 4343 - Enter false rout.');
    $controllerClass = 'Controller_'.$route['controller'];
    if(class_exists($controllerClass)) $refl = new ReflectionClass($controllerClass);
    else errorPath('Страница с таким адресом не существует. <br /> <br /> Код ошибки 4332 - Class not found.');
    if ($refl->hasMethod($route['action']))
    {
        $controller = $refl->newInstance();
        $action = $refl->getMethod($route['action']);
        if($action->getNumberOfRequiredParameters() > count($route['action']))
        {
            errorPath('Страница с таким адресом не существует. <br /> <br /> Код ошибки 4321 -  Erroneous number of parameters.');
        }
        else $action->invokeArgs($controller,$route['params']);
    }
    else errorPath('Страница с таким адресом не существует. <br /> <br /> Код ошибки 4310 -  Error load controller and action.');
}
function errorReporting()
{
    if (Config::instance()->get('dev_mode') == 1)
    {
        ini_set('display_errors', 'On');
        ini_set('log_errors' , 'Off') ;
    }
    else
    {
        ini_set('display_errors', 'Off' );
        ini_set('error_log' , LOGS_ROOT.DS.'errors_'.date("Y_m-d").'.log');
        if (Config::instance()->get('errors_in_files') == 1) ini_set('log_errors','On');
        else ini_set('log_errors', 'Off');
    }
}
function databaseErrorHandler($message,$info)
{
    if(!errorReporting()) return;
    echo "SQL Error: $message<br /><pre>";
    print_r($info);
    echo "</pre>";
    exit();
}