<?php defined ('_YEXEC')  or  die();

class yBase {
    public function __call($methodName, $arguments) {
		if($this->bindedClasses && is_array($this->bindedClasses)) {
			// Call methods from binded classes
			foreach ($this->bindedClasses as $bindedClass) {
				if (method_exists($bindedClass, $methodName)) {
					return call_user_func_array(array($bindedClass, $methodName), $arguments);
				}
			}
		} else {
			// Call 'magic' methods get*/set*/add*/delete*
			$vars_args = get_class_vars(get_class($this));
			$key_args = array_keys($vars_args);

			if (!strncmp($methodName, 'get', 3) ||
				!strncmp($methodName, 'set', 3) ||
				!strncmp($methodName, 'add', 3)) {
					$cutLength = 3;
			} elseif(
				!strncmp($methodName, 'delete', 6)) {
					$cutLength = 6;
			}

			if(!empty($cutLength)) {
				$functionName = substr($methodName, 0, $cutLength);
				$propertyName = lcfirst(substr($methodName, $cutLength));
				if($functionName == 'add' || $functionName == 'delete') $propertyName.= 's';

				if($propertyName && in_array($propertyName, $key_args)) {
					switch ($functionName){
						case 'get':
							return $this->getProperty($propertyName);
						case 'set':
							return $this->setProperty($propertyName, $arguments[0]);
						case 'add':
							return $this->addToProperty($propertyName, $arguments[0], $arguments[1]);
						case 'delete':
							return $this->deleteFromProperty($propertyName, $arguments[0]);
					}
				}
			}
		}
		
		throw new Exception('Call to undefined method '.get_class($this).'::'.$methodName.'()');
		return NULL;
	}
/*
	public function __set($name, $value) {
		if($this->bindedClasses && is_array($this->bindedClasses)) {
			foreach ($this->bindedClasses as $bindedClass) {
				if (property_exists($bindedClass, $name)) {
					return $bindedClass->{$name};
				} else {
					$this->bindedClasses[$name] = $value;
				}
			}
		}
	}
        
	public function __get($name) {
		if($this->bindedClasses && is_array($this->bindedClasses)) {
			if (array_key_exists($name, $this->bindedClasses)) {
				return $this->bindedClasses[$name];
			}
		}
	}
*/	
	// Bind objects
	public function bind() {
		if(!$this->bindedClasses) $this->bindedClasses = array();
		$this->bindedClasses = array_merge($this->bindedClasses, func_get_args());
		return $this;
	}
	
	public function deleteFromProperty($property, $key) {
		if(is_array($this->$property)) {
			unSet($this->$property[$key]);
		} elseif(is_object($this->$property)) {
			unSet($this->$property->$key);
		} else {
			$class = get_class($this);
			throw new Exception("Can't delete key $key from $class::$property: property $class::$property() is not iterable.");
		}
		return $this;
	}
	
	public function addToProperty($property, $key, $value) {
		if(is_array($this->$property)) {
			$this->$property[$key] = $value;
		} elseif(is_object($this->$property)) {
			$this->$property->$key = $value;
		} else {
			$class = get_class($this);
			throw new Exception("Can't add key $key to $class::$property: property $class::$property() is not iterable.");
		}
		return $this;
	}
	
	public function getProperty($key) {
		return $this->$key;
	}
	
	public function setProperty($key, $value) {
		$this->$key = $value;
		return $this;
	}
}

/*
 * Copyright 2012 Roman Exempliarov, Maxim Denisov and Anna Chachaeva.
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