<?php defined ('_YEXEC')  or  die();

yCore::includeDb();

class objectDb extends yDb {
	public function filters($filters) { //TODO: make filter yFilterClass, and then rename methon to "where"
		foreach($filters as $filter) // define WHERE from filters
			if($filter->type == 'field' && $filter->value)
				$this->where($filter->field,
						array(
							'value' => "$filter->value",
							'collation' => $filter->collation,
						));
			elseif($filter->type == 'page') {
				if(!$filter->value) $filter->value = 1;
				$this->limit($filter->rows, $filter->rows*($filter->value-1));
			}
			elseif($filter->type == 'order' || $filter->type == 'sort') {
				if($filter->direction)
					$direction = $filter->direction;
				else
					$direction = $filter->value; // safe, because order() checks $direction argument
				
				if ($direction)
					$this->order($filter->field, $direction);
			}

		return $this;
	}
	
	protected function valuesFromRow($values, $fields) {
		// add row values to query
		foreach($fields as $field) { // go through object fields
			$value = $values->{$field->key}; // value of field in current row
			if(isset($value)) // if value of field is defined add it to INSERT (or UPDATE) request
				$this->value($field->key, $value);
		}
		return $this;
	}

	public function create($object) { // create table
		$unique = array();
		$primary = array();
		
		$query = NULL;
		foreach($object->fields as $field) {
			//echo print_r($field, 1).'<br />';
			if ($query) $query.= ', ';
			
			if		($field->type == 'int')		$query.= "`{$field->key}` INT NOT NULL";
			elseif	($field->type == 'id') {
				$query.= "`{$field->key}` INT NOT NULL AUTO_INCREMENT";
				$unique[] = $field->key;
				$primary[] = $field->key;
			}
			elseif	($field->type == 'float')		$query.= "`{$field->key}` FLOAT NOT NULL";
			elseif	($field->type == 'currency')	$query.= "`{$field->key}` DECIMAL(18,2) NOT NULL";
			elseif	($field->type == 'string')		$query.= "`{$field->key}` VARCHAR(255) NOT NULL";
			elseif	($field->type == 'text')		$query.= "`{$field->key}` TEXT NOT NULL";
			elseif	($field->type == 'list')		$query.= "`{$field->key}` INT NOT NULL";
		}

		if($primary) {
			$primaryQuery = NULL;
			foreach($primary as $key) {
				if ($primaryQuery) $primaryQuery.= ', ';
				$primaryQuery.= "`$key`";
			}
			$primaryQuery = ", PRIMARY KEY ($primaryQuery)";
		}
		
		if($unique) {
			$uniqueQuery = NULL;
			foreach($unique as $key) {
				if ($uniqueQuery) $uniqueQuery.= ', ';
				$uniqueQuery.= "`$key`";
			}
			$uniqueQuery = ", UNIQUE ($uniqueQuery)";
		}
		//, PRIMARY KEY ( `{$field->key}` ) ,
		
		$query = "CREATE TABLE `{$object->table}` ({$query}{$primaryQuery}{$uniqueQuery}) ENGINE = MYISAM;";
				
		$this->sql->query($query);
		
		return $this;
	}

// ---- replace methods (like insert or update) ---------------------------------------------------

	public function replace($object = NULL, $mode = NULL) { // overrrides replace($object)
		if (is_a($object, 'yObject')) // if $object is child of yObject
			return $this->replaceObject($object, $mode);
		else { // in other case do not override
			$method = 'replace'.$mode; //TODO: yDb::replace()
			return parent::$method($object);
		}
	}

	public function replaceObject($object = NULL, $mode = '') { //insert or update
		$this
			->table($object->table) // define table
			->filters($object->filters); // set where

		foreach($object->values as $row) { // go through array of rows to insert
			$this
				->clearValues() // reset array of values
				->valuesFromRow($row, $object->fields); // set values

			// UPDATE if has where clause and INSERT if not
			if ($this->where)
				$method = 'update'.$mode;
			else
				$method = 'insert'.$mode;
			$iResult = parent::$method();
			if (is_string($iResult)) $result.= $iResult;
		}

		return $result;
	}

	public function replaceQuery($object) { //alias
		return $this->replace($object, 'Query');
	}	
	
// ---- replace methods (like insert or update) ---------------------------------------------------

	public function insert($object = NULL, $mode = NULL) { // overrrides replace($object)
		if (is_a($object, 'yObject')) // if $object is child of yObject
			return $this->replaceObject($object, $mode);
		else { // in other case do not override
			$method = 'insert'.$mode; //TODO: yDb::replace()
			return parent::$method($object);
		}
	}

	public function insertObject($object = NULL, $mode = '') { //insert or update
		$this
			->table($object->table); // define table

		foreach($object->values as $row) { // go through array of rows to insert
			$this
				->clearValues() // reset array of values
				->valuesFromRow($row, $object->fields); // set values

			// UPDATE if has where clause and INSERT if not
			$method = 'insert'.$mode;
			$iResult = parent::$method();
			if (is_string($iResult)) $result.= $iResult;
		}

		return $result;
	}

	public function insertQuery($object) { //alias
		return $this->insert($object, 'Query');
	}	

// ---- select methods ----------------------------------------------------------------------------

	public function select($object = NULL, $mode = 'Results') { // overrrides select($object)
		if (is_a($object, 'yObject')) // if $object is child of yObject
			return $this->selectObject($object, $mode);
		else { // in other case do not override
			$method = 'select'.$mode;
			return parent::$method($object);
		}
	}

	public function selectObject($object = NULL, $mode = 'Results') {
		$this
			->table($object->table)
			->filters($object->filters); // define table
			
			if(!$this->fields)
				foreach ($object->fields as $field) // go through object fields
					$this->field($field->key); // add field to selection
			
			// get total values count
			if ($mode == 'Results' || $mode == 'Cols') {
				$db = clone($this);
				$db->limit('');
				$db->fields = NULL;
				$db->field('COUNT(*) as `count`');
				$object->rowsTotal = $db->selectCell();
			}
			
			$method = 'select'.$mode; // method to use: selectResults, selectRow, etc.
			return parent::$method();
	}

	// Aliases

	public function selectResults($object = NULL) { //alias
		return $this->select($object, 'Results');
	}

	public function selectRow($object = NULL) { //alias
		return $this->select($object, 'Row');
	}

	public function selectCol($object = NULL) { //alias
		return $this->select($object, 'Col');
	}

	public function selectCell($object = NULL) { //alias
		return $this->select($object, 'Cell');
	}

	public function selectQuery($object = NULL) { //alias
		return $this->select($object, 'Query');
	}
}

?>
