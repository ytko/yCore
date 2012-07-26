<?php defined ('_YEXEC')  or  die();

@require_once 'base.php';

class yObject extends yBase {//TODO: implements
	public
		$key,
		$table,				// Table name
		$name,
	//private
		$fields = array(),	// Table fields
		$values = array(),	// Table values
		$filters = array();	// Table filter
	
	public function __construct() {
		$this->fields = (object)array();	// Table fields
		$this->filters = (object)array();	// Table filter
	}
	
// ---- setters ----------------------------------------------------------------
	
	public function table($table) { //TODO: make it safe
		$this->table = $table;
		if(!isset($this->key)) $this->key = $table;
		if(!isset($this->name)) $this->name = $table;
		return $this;
	}
	
	public function key($key) { //TODO: make it safe
		$this->key = $key;
		if(!isset($this->name)) $this->name = $key;
		return $this;
	}
	
	public function name($name) {
		$this->name = $name;
		return $this;
	}

// -----------------------------------------------------------------------------
	
	protected function makeRecord($key, $type = NULL, $properties = NULL) { // compile field or filter record
		// overriding emulation:
		if (is_array($type) or is_object($type))
			$properties = $type;
		
		if (is_array($properties) or is_object($properties)) {
			// taking properties from $properties array
			$properties = (object)$properties;
		}
		else {
			// taking properties from function parameters
			$properties = (object)array('key' => $key, 'type' => $type, 'name' => $properties);
		}

		if ($properties->key === NULL) $properties->key = $key;
		if ($properties->name === NULL) $properties->name = $key;
		
		return array($key, $properties);
	}

// ---- field set edit ---------------------------------------------------------

	public function field($key, $type = NULL, $properties = NULL) {
		list($key, $properties) = $this->makeRecord($key, $type, $properties);
		if ($properties->type === NULL) $properties->type = $type ? $type : 'string'; // default value of type is 'string'
		$this->fields->$key = $properties;
		return $this;
	}
	
	public function deleteField($key) {
		unset($this->fields->$key);
		return $this;
	}
	
	public function clearFields() {
		$this->fields = (object)array();
		return $this;
	}
	
	public function fieldProperty($key, $property) {
		if (!isset($this->fields->$key)) {
			throw new Exception("Field '$key' not set in ".get_class($this));
			return NULL;
		}

		$value = $this->fields->$key->$property;
		return $value;
	}

// ---- filter set edit --------------------------------------------------------
	
	public function filter($key, $type = NULL, $properties = NULL) {
		list($key, $properties) = $this->makeRecord($key, $type, $properties);
		if ($properties->type === NULL) $properties->type = $type ? $type : 'field'; // default value of type is 'field'
		$this->filters->$key = $properties;
		return $this;
	}
	
	public function deleteFilter($key) {
		unset($this->filters->$key);
		return $this;
	}
	
	public function clearFilters() {
		$this->filters = (object)array();
		return $this;
	}
	
	public function filterProperty($key, $property) {
		if (!isset($this->filters->$key)) {
			throw new Exception("Filter '$key' not set in ".get_class($this));
			return NULL;
		}

		$filter = $this->filters->$key;
		$value = $filter->$property;

		// trying to get value from other places
		if (!isset($value)) {
			if ($filter->type == 'field') {
				switch ($property) {
					case 'name':
						$value = $this->fieldProperty($filter->field, name) ?: NULL;
					break;
				}
			}
		}
		
		return $value;
	}

// ---- values set edit --------------------------------------------------------
	
	public function value($key, $value, $row = 0) {
		if (!is_object($this->values[$row]))
			$this->values[$row] = (object)array();
		
		//if(isset($this->fields->$key)) //uncomment after debug
			$this->values[$row]->$key = $value;
		
		return $this;
	}
	
	public function deleteValue($key, $row = 0) {
		unset($this->values[$row]->$key);
		return $this;
	}	
	
	public function addRow($values) {
		$this->values[] = (object)$values;		
		return $this;
	}
	
	public function deleteRow($row) {
		unset($this->values[$row]);
		return $this;
	}
	
	public function clearValues() {
		$this->values = array();
		return $this;
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