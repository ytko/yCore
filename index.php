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

$result = '';
//$result = $sql->table('guns')->table('cars')->field('speed','char','16','NOT NULL')->field('speed2','char','15','NOT NULL')->createQuery();
$result.= $sql->table("guns")->field('name')->field('size')->createQuery();
$result.= $sql->table('guns')->values('mk-51','54x76')->insertQuery();
//Вывод данных 
print_r($sql->tables);

echo "<br/><br/><br/>".$result;

//echo substr('lad',0,-1);

//echo microtime() - $micro_start;

?>