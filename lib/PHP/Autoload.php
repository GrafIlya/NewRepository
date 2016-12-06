<?php
    // Библиотека мультизагрузки классов
     class PHP_Autoload
     {
         static $funcs = array(); // Список функций,которые вызываются при запросе autload'a
         static $ok = true; // переменная проверки главного обработчика
         static function register($func) // Регистрация новых функций в списке обработчиков
         {
             self::$funcs[] =& $func; // помещаем ссылку на функцию в массив функций
         }
         static function unregister($func) // де-регистрация функций в списке обработчиков
         {
             $f =& self::$funcs; // закинули список функций
             for($i = 0; $i < count($f); $i++) // Перебор списка
             {
                 if($f[$i] === $func) // если в списке есть совпадение
                 {
                     array_splice($f, $i, 1); // удалим из списка элемент на позиции $i
                     break; // выход из фора
                 }
             }
         }
         # Вызывается в момент запроса на autoload
         static function autoload($classname)
         {
             static $loading = array();
             # Если класс не загружен, авызывается class_exists(),
             # происходит повторный запрос на autoload, и программа зацикливается. Что бы
             # избежать этого,проверяем, чтобы вход в autoload() с тем же именем
             # класса не происходил дважды
             if(@$loading[$classname]) return;
             # Идет загрузка. Если autoload() будет вызвана рекурсивно,
             # сработает предыдущая строчка.
             $loading[$classname] = true;
             # array_reverse() - возврящает массив с элементами в обратном порядке
             foreach(array_reverse(self::$funcs) as $f){
                 # Здесь происходит рекурсивный вызов autoload(),
                 # когда класс еще не загружен
                 # class_exists() - проверяет, был ли загружен класс
                 if(class_exists($classname)) break;
                 # Вызывает обработчик. Если он вернет false, значит,
                 # произошла какая-то ошибка, и необходимо запустить
                 # следующий по списку обработчик.
                 # call_user_func() - вызывает пользовательскую функцию
                 if(call_user_func($f, $classname)) break;
             }
             # Загрузка закончена
             $loading[$classname] = false;
         }
     }
     # Код, выполняемый при подключении библиотеки.
     # Устанавливает собственный глобальный обработчик
     # на __autoload, но только в случае, если такой обработчик еще не был
     # установлен где-то еще
     if(!function_exists("__autoload"))
     {
         function __autoload($c)
         {
             PHP_Autoload::autoload($c);
         }
     }
     else PHP_Autoload::$ok = false;
