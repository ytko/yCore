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

// ---- values set edit --------------------------------------------------------
	
	public function value($key, $value, $row = 0) {
		if (!is_object($this->values[$row]))
			$this->values[$row] = (object)array();
		
		//if(isset($this->fields->$key)) //uncomment after debug
			$this->values[$row]->$key = $value;
		
		return $this;
	}
	
	public function clearValues() {
		$this->values = array();
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
}

?>