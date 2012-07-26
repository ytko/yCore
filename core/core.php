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
/** Includes component file
  * \param type Type of component 
  * \param name Name of component
  */
	protected static function includeComponent($type, $name = NULL) {
	/* include_once файла и возврат имени класса
	 * путь до файла и имени генерируется из $type ('model', 'view', etc) и $name вида '/module/name'
	*/	
		
		// если имя не передано, то подключается класс из ядра
		if (!isset($name)) {
			include_once ySettings::$corePath.'/'.$type.'.php';
			$result = 'y'.ucfirst($type);
		}
		// если имя передано, то подключается класс из соответствующего файла
		else {
			//если bean, то тип в пути и назввании опускается
			$type = ($type == 'bean') ? 'Class' : $type;
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

	public static function includeBean($name = NULL) {
		return
			yCore::includeComponent('bean', $name);		
	}
	
	public static function includeController($name = NULL) {
		return
			yCore::includeComponent('controller', $name);
	}

	public static function includeModel($name = NULL) {
		return
			yCore::includeComponent('model', $name);
	}		

	public static function includeTemplate($name = NULL) {
		return
			yCore::includeComponent('template', $name);
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
		$beanClassName = yCore::includeBean($name);
		return new $beanClassName();
	}
	
	public static function controller($name = NULL) {
		$controllerClassName = yCore::includeController($name);
		return new $controllerClassName($controllerName);
	}
	
	public static function model($name = NULL) {
		$modelClassName = yCore::includeModel($name);
		return new $modelClassName();
	}
		
	public static function template($name = NULL) {
		$templateClassName = yCore::includeTemplate($name);
		return new $templateClassName();
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