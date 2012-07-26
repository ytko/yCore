<?php

//$micro_start = microtime(true);

define ('_YEXEC', ''); //Первая строка каждого php-файла фреймворка должена быть "<?php defined ('_YEXEC')  or  die();"

require_once('settings.php');
require_once(ySettings::$corePath.'/core.php');

header('Content-type: text/html; charset=utf-8');

//Получение контента страницы
echo yCore::structureClass()->show();

//echo microtime(true) - $micro_start;

?>