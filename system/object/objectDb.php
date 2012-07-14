<?php defined ('_YEXEC')  or  die();

yFactory::includeDb();

class objectDbClass extends yDbClass {
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
		
		echo $query;
		
		$this->sql->query($query);
	}

	public function insert($object = NULL, $mode = '') { //insert or update
		// override:
		// if $object is child of yObjectClass
		if (is_a($object, 'yObjectClass')) {
			// define table
			$this->table($object->table);
			// go through array of rows to insert
			foreach($object->values as $row) {
				// reset array of values
				$this->clearValues();
				// go through object fields
				foreach($object->fields as $field) {
					// value of field in current row
					$value = $row->{$field->key};
					// if value of field is defined add it to request
					if(isset($value)) {
						// if type of this field is 'id' add it to WHERE
						if ($field->type == 'id') {
							$this->where($field->key, $value);
						}
						// in other case add it as a value to INSERT (or UPDATE)
						else {
							$this->value($field->key, $value);
						}
					}
				}
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
		// in other case do not override
		else {
			$method = 'insert'.$mode;
			return parent::$method($object);
		}	
				/*if($object->fields) foreach($object->fields as $field) {
			if($value = $this->getRequest($field->key)) {
				$object->value($field->key, $value, $row);
			}
		}*/
	}

	public function insertQuery($object) { //alias
		return $this->insert($object, 'Query');
	}	

	public function select($object = NULL, $mode = 'Results') {
		// override:
		// if $object is child of yObjectClass
		if (is_a($object, 'yObjectClass')) {
			// define table
			$this->table($object->table);
			// go through object fields
			foreach ($object->fields as $field) {
				// add field to selection
				$this->field($field->key);
				
				
				// get value of field in the first row
				//$value = $object->values[0]->{$field->key}; // TODO: select from all rows for selectResults
				// if type of this field is 'id' add it to WHERE
				/*if($field->type == 'id' && isset($value)) {
					$this->where($field->key, $value);
				}*/
			}
			
			foreach($object->filters as $filter) {
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
			}
			
			if ($mode == 'Results' || $mode == 'Cols') { // get total values count
				$db = clone($this);
				$db->limit('');
				$db->fields = NULL;
				$db->field('COUNT(*) as `count`');
				$object->rowsTotal = $db->selectCell();
			}
			
			// method to use: selectResults, selectRow, etc.
			$method = 'select'.$mode;
			return parent::$method();
		}
		// in other case do not override
		else {
			$method = 'select'.$mode;
			return parent::$method($object);
		}
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
