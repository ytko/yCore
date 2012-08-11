<?php defined ('_YEXEC')  or  die();

class yBase {
    public function __call($func_name, $args) {
		$vars_args = get_class_vars(get_class($this));
		$key_args = array_keys($vars_args);
		
		if (!strncmp($functionName, 'get', 3) ||
			!strncmp($functionName, 'set', 3) ||
			!strncmp($functionName, 'add', 3)) {
				$cutLength = 3;
		} elseif (
			!strncmp($functionName, 'delete', 6)) {
				$cutLength = 6;
		}

		if ($cutLength) {
			$functionName = substr($func_name, 0, $cutLength);
			$propertyName = lcfirst(substr($func_name, $cutLength));
			if ($functionName == 'add' || $functionName = 'delete') $propertyName.= 's';
		}

		if($propertyName && in_array($propertyName, $key_args) {
			switch ($functionName){
				case 'get':
					return $this->getProperty($propertyName);
				case 'set':
					return $this->setProperty($propertyName, $args[0]);
				case 'add':
					return $this->addToProperty($propertyName, $args[0], $args[1]);
				case 'delete':
					return $this->deleteFromProperty($propertyName, $args[0]);
			}
		}

		throw new Exception('Call to undefined method '.get_class($this).'::'.$func_name.'()');
		return NULL;
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
 * Copyright 2012 Roman Exempliarov and Maxim Denisov. 
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