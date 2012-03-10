<?php defined('_JEXEC') or die('Restricted access');

$micro_start = microtime();

/*
echo '<pre>';
print_r (get_defined_constants());
JPATH_COMPONENT
JPATH_COMPONENT_SITE
JPATH_COMPONENT_ADMINISTRATOR
*/

define ('_YEXEC', ''); //Первая строка каждого php-файла фреймворка должена быть  "<?php defined ('_YEXEC')  or  die();"
//define ('DS', '/'); //Установка слеша; закомментить для joomla

// Настройка
class ySettings {
	static public $get, $mvc, $path, $db;
}

//mysql_connect("mysql_host", "mysql_user", "mysql_password");

ySettings::$path = JPATH_COMPONENT_SITE;						//Путь к корню фреймворка
ySettings::$get = (object)array('option' => $_GET['option']);	//Обязательная добавка ко всем get-запросам (нужно для joomla)
ySettings::$mvc = (object)array(								//Названия параметров, передаваемых в get-запросах
		'module' => 'mod',				//Параметр, отвечающий за название модуля
		'controller' => 'controller',	//...контроллера
		'model' => 'model',				//...модели
		'view' => 'view',				//...вида
		'template' => 'template'		//...шаблона		
		//Пример: 'module' => 'mod', тогда запрос для модуля mmm с альтернативным видом vvv будет ?mod=mmm&view=vvv
); //TODO: проверить работоспособность
ySettings::$db = (object)array(									//Настройка базы данных
		'resource' => JFactory::getDBO(),	//Ссылка на ресурс БД
		'prefix' => 'j7_',					//Префикс CMS
		'com_prefix' => 'ytko',				//Префикс фреймворка, добавляется к префиксу CMS
		'type' => ''						//TODO: Тип БД.
); //TODO: Оставить только prefix

require_once(ySettings::$path.DS.'include'.DS.'debug.php');
/* Debugger settings
yDebug::$on = true;
yDebug::$all = true;
//*/

require_once(ySettings::$path.DS.'core'.DS.'factory.php');
require_once(ySettings::$path.DS.'core'.DS.'functions.php');
require_once(ySettings::$path.DS.'core'.DS.'dbquery.php');
require_once(ySettings::$path.DS.'core'.DS.'db.php');

yFactory::$basePath = JPATH_COMPONENT_SITE; //Путь к корню фреймворка

//Работа основного модуля
$module = yFactory::getModule($_GET[ySettings::$mvc->module]);	//Создание экземпляра модуля //TODO:безопасный запрос (как в factory)
$moduleResult = $module->getModule();							//Получение контента основного модуля

//Работа модуля оформления (в него отпровляется контент основного модуля, который обрамляется в моответствии с шаблоном)
$module = yFactory::getModule('main');					//Создание экземпляра модуля оформления
$mainResult = $module->getModule($moduleResult->body);	//Получение данных модуля

//Вывод данных 
echo '<head>'.$mainResult->head.$moduleResult->head.'</head>';	//Вывод <head>
echo '<body>'.$mainResult->body.'</body>';						//Вывод <body>

echo microtime() - $micro_start;

?>