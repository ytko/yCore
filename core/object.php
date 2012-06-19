<?php defined ('_YEXEC')  or  die();

@require_once 'base.php';

class yObjectClass extends yBaseClass {//TODO: implements
	public
		$db,				// ySql object
		$table,				// Table name
	//private
		$fields = array(),	// Table fields
		$values = array();	// Table values
		
	// db initialization
	/*protected function dbInit() {
		// using ySql for db
		if (!$db)
			$this->db = yFactory::getDb();
	}*/
	
	public function table($tableName) {
		$this->table = $tableName;
		return $this;
	}
	
	public function field($field, $type) {
		$this->fields[$field] = $type;		
		return $this;
	}
	
	public function addRow($values) {
		$this->values[] = $values;		
		return $this;
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