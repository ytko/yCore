<?php

//$micro_start = microtime();

define ('_YEXEC', ''); //Первая строка каждого php-файла фреймворка должена быть "<?php defined ('_YEXEC')  or  die();"

require_once('settings.php');
require_once(ySettings::$corePath.'/factory.php');

header('Content-type: text/html; charset=utf-8');

//Получение контента страницы
//$result = yFactory::getBean('structure')
//		->get();

yy::linkDb();

$sql = new ySqlGenClass;

$result = $sql->table('guns')->table('cars')->field('speed')->field('size')->createQuery();
//Вывод данных 
print_r($sql->tables);

echo "<br/><br/><br/>".$result;

//echo substr('lad',0,-1);

//echo microtime() - $micro_start;

?>