<?php defined ('_YEXEC')  or  die();

@require_once 'base.php';

class yObjectClass extends yBaseClass {//TODO: implements
	public
		$key,
		$table,				// Table name
		$name,
	//private
		$fields = array(),	// Table fields
		$values = array(),	// Table values
		$filter = array();	// Table filter
	
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
	
	protected function makeRecord($key, $type = NULL, $properties = NULL) {
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
		if ($properties->type === NULL) $properties->type = $type ?: 'string';		
		
		return array($key, $properties);
	}
	
	public function field($key, $type = NULL, $properties = NULL) {
		list($key, $properties) = $result = $this->makeRecord($key, $type, $properties);

		$this->fields[$key] = $properties;
		
		return $this;
	}
	
	public function clearFields() {
		$this->fields = array();
	}
	
	public function filter($key, $type = NULL, $properties = NULL) {
		list($key, $properties) = $result = $this->makeRecord($key, $type, $properties);

		$this->filter[$key] = $properties;
		
		return $this;		
	}
	
	public function clearFilters() {
		$this->filters = array();
	}
	
	public function addRow($values) {
		$this->values[] = (object)$values;		
		return $this;
	}
	
	public function value($key, $value, $row = 0) {
		if (!is_object($this->values[$row]))
			$this->values[$row] = (object)array();
		
		//if(isset($this->fields->$key)) //uncomment after debug
			$this->values[$row]->$key = $value;
		
		return $this;
	}
	
	public function clearValues() {
		$this->values = array();
	}
	
	/*
	public function insert($values) {
		$this->dbInit();				// db initialization
		$this->db
			->table($this->table);		// set current table

		// single insert
		foreach($values as $field => $value) {
			$this->db
				->value($field, $value);
		}

		$this->db
			->insert();
		
		//TODO: multiple insert
	}
	*/
}

?>