<?php defined ('_YEXEC')  or  die();

class yBase {
	public $owner;
        
    public function __call($func_name, $args) {
                $vars_args = get_class_vars(get_class($this));
                $key_args = array_keys($vars_args);
                $functionName = substr($func_name,0,3);
                $propertyName = lcfirst(substr($func_name,3));
                $propertyValue = $args[0];
                
                if(!in_array($propertyName, $key_args)){
                    return NULL;
                };
                
                switch ($functionName){
                    case 'get':
                        return $this->$propertyName;
                    case 'set':
                        $this->$propertyName = $propertyValue;
                        return $this;
                    default: 
                        return NULL;
                }
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