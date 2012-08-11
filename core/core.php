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
			$className = lcfirst(substr($className,4));
			return self::load($className);
		}
		elseif (substr($className,0,6) == 'create') {
			$className = lcfirst(substr($className,6));
			return self::createArgs($className, $arguments);
		}
		else
			return self::createArgs($className, $arguments);
	}

/** Creates class $className instance
 * \param $className Name of class
 * \param $arguments (optional) Array of arguments for constructor
 */
	public static function createArgs($className, $arguments = NULL) {
		self::load($className);

		// Return class instance
		if (empty($arguments))
			return new $className();
		else { // If arguments are set pass them to constructor
			$classReflection = new ReflectionClass($className);
			return $classReflection->newInstanceArgs($arguments);
		}
	}

/** Creates class $className instance
 * \param $className Name of class
 * \param $argument1 (optional) First argument for constructor
 * \param $argument2 (optional) Second argument for constructor
 * \param (...)
 */	
	public static function create($className) {
		$arguments = func_get_args();
		array_shift($arguments);
		return self::createArgs($className, $arguments);
	}
	
/** Includes file with class $className if class doesn't defined yet
 * \param $className Name of class
 * \param $componentPath (optional) Path to file with class relatively to framework root
 * \return $componentPath or NULL if class already defined
 */
	public static function load($className, $componentPath = NULL) {
		if(class_exists($className))
			return NULL;
		
		if(isSet($componentPath)) {
			// Use path from parametors
			$componentPath = ySettings::$path.'/'.$componentPath;
		} else {
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
		}

		$result = include_once($componentPath);

		if(!$result)
			throw new Exception("Can't load class '$className' from '$componentPath'.");

		return $componentPath;
	}
	
	/// Returns path to current module depending on ySettings
	protected static function modulePath($moduleName) {
		if(isset(ySettings::$altPaths->$moduleName))
			return ySettings::$path.'/'.ySettings::$altPaths->$moduleName.'/';
		else
			return ySettings::$modulesPath.'/';
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