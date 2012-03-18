<?php

header('Content-type: text/html; charset=utf-8');

//$micro_start = microtime();

define ('_YEXEC', ''); //Первая строка каждого php-файла фреймворка должена быть  "<?php defined ('_YEXEC')  or  die();"
define ('DS', '/'); //Установка слеша; закомментить для joomla //удалить

require_once('settings.php');
require_once(ySettings::$corePath.'/factory.php');
require_once(ySettings::$corePath.'/functions.php');
require_once(ySettings::$corePath.'/dbquery.php');
require_once(ySettings::$corePath.'/db.php');

//Работа основного модуля
//$module = yFactory::getModule($_GET[ySettings::$mvc->module]);	//Создание экземпляра модуля //TODO:безопасный запрос (как в factory)
//$moduleResult = $module->getModule();							//Получение контента основного модуля

//Работа модуля оформления (в него отпровляется контент основного модуля, который обрамляется в моответствии с шаблоном)
$module = yFactory::getModule('main');					//Создание экземпляра модуля оформления
$mainResult = $module->getModule();						//Получение данных модуля

//Вывод данных 
echo $mainResult->head;						//Вывод <head>
echo $mainResult->body;						//Вывод <body>

//echo microtime() - $micro_start;

?>