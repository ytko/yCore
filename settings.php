<?php defined ('_YEXEC')  or  die();

// ------ Framework settings ----------------------------------------------------------------------
class ySettings {
	static public
			$path, $rootPath, $corePath,
			$db,
			$modulesPath, $altPaths;
}

// ------ Paths -----------------------------------------------------------------------------------
ySettings::$rootPath = $_SERVER['DOCUMENT_ROOT'];
ySettings::$path = ySettings::$rootPath.'';				//Путь к корню фреймворка //JPATH_COMPONENT_SITE
//ySettings::$path = ySettings::$rootPath.preg_replace("/\/([^\/]*)$/m", '', $_SERVER['PHP_SELF']);
ySettings::$corePath = ySettings::$path.'/core';		//Путь к ядру (core)

// ------ Base path and alternative paths for modules (redirection) --------------------------------

ySettings::$modulesPath = ySettings::$path.'/modules';	//Путь к модулям
ySettings::$altPaths = (object)array();

// ------ Data base -------------------------------------------------------------------------------
ySettings::$db = (object)array(
		'host' => 'localhost',
		'name' => 'j1',
		'user' => 'j1',
		'password' => '12345',
		'type' => 'mysql'
);

?>