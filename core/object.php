<?php defined ('_YEXEC')  or  die();

@require_once 'base.php';

class yObjectClass extends yBaseClass {//TODO: implements
	public
		$db,				// ySql object
		$table,				// Table name
	//private
		$fields = array(),	// Table fields
		$values = array();	// Table values
		
	public function table($tableName) { //TODO: make it safe
		$this->table = $tableName;
		return $this;
	}
	
	public function field($key, $properties = NULL, $name = NULL) {		
		// overriding emulation:
		if (is_array($properties) or is_object($properties)) {
			// taking properties from $properties array
			$properties = (object)$properties;
		}
		else {
			// taking properties from function parameters
			$properties = (object)array('key' => $key, 'type' => $properties, 'name' => $name);
		}

		if ($properties->name === NULL) $properties->name = $key;
		if ($properties->type === NULL) $properties->type = 'string';

		$this->fields[$key] = $properties;
		
		return $this;
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