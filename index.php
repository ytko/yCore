<?php header('Content-type: text/html; charset=utf-8');

$micro_start = microtime();

/*
echo '<pre>';
print_r (get_defined_constants());
JPATH_COMPONENT
JPATH_COMPONENT_SITE
JPATH_COMPONENT_ADMINISTRATOR
*/

define ('_YEXEC', ''); //Первая строка каждого php-файла фреймворка должена быть  "<?php defined ('_YEXEC')  or  die();"
define ('DS', '/'); //Установка слеша; закомментить для joomla

// Настройка
class ySettings {
	static public $get, $mvc, $path, $rootPath, $ytkoPath, $db;
}

$link = mysql_connect('localhost', 'j1', '12345');

ySettings::$rootPath = $_SERVER['DOCUMENT_ROOT'];
ySettings::$path = ySettings::$rootPath.'/components/com_ytkocore';	//Путь к корню фреймворка
//ySettings::$path = JPATH_COMPONENT_SITE;						//Путь к корню фреймворка

//ySettings::$rootPath = 
//ySettings::$path = '/components/com_ytkocore';

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
		//'resource' => JFactory::getDBO(),	//Ссылка на ресурс БД
		'host' => 'localhost',
		'name' => 'j1',
		'user' => 'j1',
		'password' => '12345',
		'prefix' => 'j7_ytko',
		'type' => 'mysql'					//TODO: Тип БД.
); //TODO: Оставить только prefix

require_once(ySettings::$path.DS.'include'.DS.'debug.php');
/* Debugger settings
yDebug::$on = true;
yDebug::$all = true;
//*/

require_once(ySettings::$path.'/core/factory.php');
require_once(ySettings::$path.'/core/functions.php');
require_once(ySettings::$path.'/core/dbquery.php');
require_once(ySettings::$path.'/core/db.php');

yFactory::$basePath = ySettings::$path; //Путь к корню фреймворка


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