<?php

//$micro_start = microtime();

define ('_YEXEC', ''); //Первая строка каждого php-файла фреймворка должена быть "<?php defined ('_YEXEC')  or  die();"

require_once('settings.php');
require_once(ySettings::$corePath.'/factory.php');

header('Content-type: text/html; charset=utf-8');

//Получение контента страницы


//$result = yFactory::getBean('structure')
//		->get();

print_r(yy::db()->table('Newtable')->fields('newfield')->createTmp());

//$result = yy::db()->init()->table('catalog');
//
//echo $result;


//echo microtime() - $micro_start;

?>