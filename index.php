<?php

header('Content-type: text/html; charset=utf-8');

//$micro_start = microtime();

define ('_YEXEC', ''); //Первая строка каждого php-файла фреймворка должена быть  "<?php defined ('_YEXEC')  or  die();"
define ('DS', '/'); //Установка слеша; закомментить для joomla //удалить

require_once('settings.php');
require_once(ySettings::$corePath.'/factory.php');
require_once(ySettings::$corePath.'/functions.php');
require_once(ySettings::$corePath.'/db.php');

//Получение контента страницы
$mainResult = yFactory::getBean('/structure')
		->get();

//Вывод данных 
echo $mainResult->head;						//Вывод <head>
echo $mainResult->body;						//Вывод <body>

//echo microtime() - $micro_start;

?>