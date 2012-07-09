<?php defined ('_YEXEC')  or  die();

// ------ Framework settings ----------------------------------------------------------------------
class ySettings {
	static public $get, $mvc, $path, $rootPath, $corePath, $modulesPath,
			$db,
			$altPaths;
}

// ------ Paths -----------------------------------------------------------------------------------
ySettings::$rootPath = $_SERVER['DOCUMENT_ROOT'];
ySettings::$path = ySettings::$rootPath.'';				//Путь к корню фреймворка //JPATH_COMPONENT_SITE
//ySettings::$path = ySettings::$rootPath.preg_replace("/\/([^\/]*)$/m", '', $_SERVER['PHP_SELF']);
ySettings::$corePath = ySettings::$path.'/core';		//Путь к ядру (core)
ySettings::$modulesPath = ySettings::$path.'/modules';	//Путь к модулям

// ------ Alternative paths for modules (redirection) ---------------------------------------------

ySettings::$altPaths = (object)array(
		'structure' => 'cms',
		'template' => 'cms',
		'users' => 'cms',
	
		'badge' => 'ignore',
		'extended' => 'ignore',
		'general' => 'ignore',
		'default' => 'ignore'
		
);

// ------ Data base -------------------------------------------------------------------------------
ySettings::$db = (object)array(
		'host' => 'localhost',
		'name' => 'j1',
		'user' => 'j1',
		'password' => '12345',
		'prefix' => 'j7_ytko',	//TODO: использовать
		'type' => 'mysql'		//Тип БД.
);

?>