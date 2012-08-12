<?php defined ('_YEXEC')  or  die();

@require_once 'yBase.php';

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

// ---- complex methods --------------------------------------------------------

	/** Renames field and appropriate values
	* @param string $oldKey
	* @param string $newKey
	* @param bool $clone = false Clones field if true
	* @return $this
	*/
	public function renameCol($oldKey, $newKey, $clone = false) {
		// Copying values
		foreach($this->values as $row) {
			if(isSet($row->$oldKey)) {
				if(!isSet($row->$newKey)) {
					$row->$newKey = $row->$oldKey;
					// unset old value in rename mode
					if(!$clone)
						unset($row->$oldKey);
				} else {
					//TODO: exception;
				}
			}
		}
		
		// Copying field parametors
		if (isSet($this->fields->$oldKey)) {
			if(!isSet($this->fields->$newKey)) {
				// clone field description in clone mode
				if($clone)
					$this->fields->$newKey = clone $this->fields->$oldKey;
				// copy and unset old field description in rename mode
				else {
					$this->fields->$newKey = $this->fields->$oldKey;
					unset($this->fields->$oldKey);
				}
				$this->fields->$newKey->key = $newKey;
			} else {
				//TODO: exception;
			}
		}
		
		return $this;
	}

	/** Clones field and appropriate values
	* @param string $oldKey
	* @param string $newKey
	* @return $this
	*/
	public function cloneCol($oldKey, $newKey) {
		return $this->renameCol($oldKey, $newKey, true);	
	}
	
	/** Deletes field and appropriate values
	* @param string $key
	* @return $this
	*/
	public function deleteCol($key) {
		// Deleting values
		foreach($this->values as $row) {
			if(isSet($row->$key)) unset($row->$key);
		}

		// Deleting field parametors
		if(isSet($this->fields->$key)) {
			unset($this->fields->$key);
		}

		return $this;
	}
	
// -----------------------------------------------------------------------------
	
	protected function makeRecord($key, $type = NULL, $name = NULL, $properties = NULL) { // compile field or filter record
		// overriding emulation:
		if(!isSet($properties)) {
			if(is_array($name) or is_object($name)) {
				$properties = (object)$name;
				$name = NULL;
			} elseif(is_array($type) or is_object($type)) {
				$properties = (object)$type;
				$type = NULL;
			} elseif(is_array($key) or is_object($key)) {
				$properties = (object)$key;
				$key = NULL;
			// taking properties from function parameters
			} else {
				$properties = (object)array();
			}
		}
		
		// $properties should be stdObject
		if(is_array($properties)) $properties = (object)$properties;

		if(isSet($key)) $properties->key = $key;
		if(isSet($type)) $properties->type = $type;
		if(isSet($name)) $properties->name = $name;
				
		return $properties;
	}

// ---- field set edit ---------------------------------------------------------

	public function field($key, $type = NULL, $name = NULL, $properties = NULL) {
		$properties = $this->makeRecord($key, $type, $name, $properties);
		$key = $properties->key;
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
	
	public function filter($key, $type = NULL, $name = NULL, $properties = NULL) {
		$properties = $this->makeRecord($key, $type, $name, $properties);
		$key = $properties->key;
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

		if (isSet($filter->$property)) {
			$value = $filter->$property;
		}
		else {
			// trying to get value from other places
			if ($filter->type == 'field') {
				switch ($property) {
					case 'name':
						$value = $this->fieldProperty($filter->field, name) ?: NULL;
					break;
				}
			}
		}

		if(!isSet($value)) $value = NULL;
		
		return $value;
	}

// ---- values set edit --------------------------------------------------------
	
	public function value($key, $value, $row = 0) {
		if (!isSet($this->values[$row]))
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