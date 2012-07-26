<?php defined ('_YEXEC')  or  die();
/** \file factory.php
 *  \author Roman Exempliarov
 *
 * Contains yCore class
 */

/// Factory static class
/**
 * Contains static methods generating objects components and including php-files.
 */
class yCore {
	public static function __callStatic($className, $arguments) {
		if (substr($className,0,4) == 'load') {
			$className = lcfirst(substr($className,7));
			return self::load($className);
		}
		else
			return self::create($className, $arguments);
	}

/** Creates class $className instance
  * \param $className Name of class
  * \param $arguments (optional) Array of arguments for constructor
  */
	public static function create($className, $arguments = NULL) {
		self::load($className);

		// Return class instance
		if (empty($arguments))
			return new $className();
		else { // If arguments are set pass them to constructor
			$classReflection = new ReflectionClass($className);
			return $classReflection->newInstanceArgs($arguments);
		}
	}
	
	public static function load($className = NULL) {
		// Split name by upper-case chars
		$splittedName = preg_split('/(?=[[:upper:]])/', $className);
		$moduleName = array_shift($splittedName);
		$componentType = array_pop($splittedName);

		// Genarating component's path+filename
		if($moduleName == 'y') {		// Core component (e.g. yModel)
			$componentPath = ySettings::$corePath.'/'.lcfirst($componentType).'.php';
		}
		else {
			if(empty($splittedName)) {	// Simple class name (e.g. fooModel)
				$componentPath = $moduleName.'/'.$moduleName;
			} else {					// Long class name (e.g. fooBarModel)
				foreach($splittedName as &$value)
					$value = lcfirst($value);
				$componentPath = $moduleName.'/'.implode('/', $splittedName);
			}
			$componentPath = self::modulePath($moduleName).$componentPath.($componentType != 'Class' ? $componentType : NULL).'.php';
		}
		
		include_once ($componentPath);
	}
	
	/// Returns path to current module depending on ySettings
	protected static function modulePath($moduleName) {
		if(isset(ySettings::$altPaths->$moduleName))
			return ySettings::$path.'/'.ySettings::$altPaths->$moduleName.'/';
		else
			return ySettings::$modulesPath.'/';
	}

/* Includes component file
  * \param type $type of component 
  * \param name $name of component
  */
/* include_once файла и возврат имени класса
	 * путь до файла и имени генерируется из $type ('model', 'view', etc) и $name вида '/module/name'
	*/
	protected static function includeComponent($type, $name = NULL) {
	
		
		// если имя не передано, то подключается класс из ядра
		if (!isset($name)) {
			include_once ySettings::$corePath.'/'.$type.'.php';
			$result = 'y'.ucfirst($type);
		}
		// если имя передано, то подключается класс из соответствующего файла
		else {
			//если bean, то тип в пути и назввании опускается
			$type = ($type == 'controller') ? 'Class' : $type;
			$Type = ucfirst($type); // первая буква заглавная
			
			// имя может быть готовым массивом, либо задано через '/'
			$name = (is_array($name)) ? $name : explode('/', $name);
			
			// Определение имени подключаемого модуля и класса
			if ($name[0]) {
				$moduleName = $name[0];
				$className = (isset($name[1])) ? $name[1] : $name[0];
			}
			else return;			// Неправильно задано имя

			if(isset(ySettings::$altPaths->$moduleName))
				$modulesPath = ySettings::$path.'/'.ySettings::$altPaths->$moduleName;
			else
				$modulesPath = ySettings::$modulesPath;
			
			include_once ($modulesPath.'/'.$moduleName.'/'.$className.($Type != 'Class' ? $Type : NULL).'.php');
			$result = yCore::getClassPrefix($moduleName, $className).$Type;
		}
		
		return $result;
	}
	
	public static function includeClass($name = NULL) {
		return
			yCore::includeComponent('class', $name);
	}

	public static function includeModel($name = NULL) {
		return
			yCore::includeComponent('model', $name);
	}		

	public static function includeDb($name = NULL) {
		return
			yCore::includeComponent('db', $name);	
	}
	
	public static function includeObject($name = NULL) {
		return
			yCore::includeComponent('object', $name);	
	}

	public static function get($name = NULL) { //alias get
		$className = yCore::includeClass($name);
		return new $className();
	}
	
	public static function model($name = NULL) {
		$modelClassName = yCore::includeModel($name);
		return new $modelClassName();
	}
		
	public static function template($name) {
		$modelClassName = yCore::includeTemplate($name);
		return new $modelClassName();
	}

	public static function db($name = NULL) {
		$dbClassName = yCore::includeDb($name);
		return
			new $dbClassName();
	}
	
	public static function object($name = NULL) {
		$objectClassName = yCore::includeObject($name);
		return
			new $objectClassName();
	}
	
	function getClassPrefix($moduleName, $name) { //генерирует префикс названия класса из названия модуля и названия файла класса
		$moduleName = strtolower($moduleName);
		$name = strtolower($name);
	
		if (!strcmp($moduleName, $name))
			return $moduleName;
		else {
			$name[0] = strtoupper($name[0]);
			return $moduleName.$name;
		}
	}
	
	// Функции проверки безопасности имен и путей
	
	function safeName($name, $defaultName = NULL, $errorName = NULL) {
		return strtolower($name ? (
					yCore::isFileNameSafe($name)
						? $name
						: $errorName )
				: $defaultName);
	}
	
	private function isFileNameSafe($fileName) { //не должно быть слэшей
		return !(!(strpos($fileName, '/') === false) ||
				!(strpos($fileName, '\\') === false));
	}
	
	private function isPathNameSafe($path) { //не должно быть возвратов
	
	}
}

/*
 * Copyright 2012 Roman Exempliarov. 
 *
 * This file is part of yCore framework.
 *
 * yCore is free software: you can redistribute it and/or modify it under the
 * terms of the GNU Lesser General Public License as published by the Free
 * Software Foundation, either version 2.1 of the License, or (at your option)
 * any later version.
 * 
 * yCore is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU Lesser General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with yCore. If not, see http://www.gnu.org/licenses/.
 */

?>