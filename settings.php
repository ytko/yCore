<?php defined ('_YEXEC')  or  die();

// Настройка
class ySettings {
	static public $get, $mvc, $path, $rootPath, $corePath, $db;
}

ySettings::$rootPath = $_SERVER['DOCUMENT_ROOT'];
ySettings::$path = ySettings::$rootPath.'';				//Путь к корню фреймворка //JPATH_COMPONENT_SITE
ySettings::$corePath = ySettings::$rootPath.'/core';	//Путь к ядру (core)
ySettings::$get = (object)array(						//Обязательная добавка ко всем get-запросам (нужно для joomla)
		//'option' => $_GET['option']
);
ySettings::$mvc = (object)array(						//Названия параметров, передаваемых в get-запросах
		'module' => 'mod',									//Параметр, отвечающий за название модуля
		'controller' => 'controller',						//...контроллера
		'model' => 'model',									//...модели
		'view' => 'view',									//...вида
		'template' => 'template'							//...шаблона
		//Пример: 'module' => 'mod', тогда запрос для модуля mmm с альтернативным видом vvv будет ?mod=mmm&view=vvv
); //TODO: проверить работоспособность
ySettings::$db = (object)array(							//Настройка базы данных
		//'resource' => JFactory::getDBO(),					//Ссылка на ресурс БД
		'host' => 'localhost',
		'name' => 'j1',
		'user' => 'j1',
		'password' => '12345',
		'prefix' => 'j7_ytko',
		'type' => 'mysql'									//TODO: Тип БД.
); //TODO: Оставить только prefix

// Debuger
require_once(ySettings::$path.DS.'include'.DS.'debug.php');
//*yDebug::$on = true;
yDebug::$all = true;
//*/


?>