<?php defined ('_YEXEC')  or  die();

class ySqlGenClass {
	public
			$mode, // Can be 'select', 'insert', 'insert_or_update', etc. Changes with last ->select(), ->insert(), etc. method called.
			$fields, // Used for SELECT
			$table, // Used for SELECT FROM $table, INSERT INTO $table, etc.
			$values, // Used for INSERT and UPDATE
			$join, $where, $option, $group, $having, $order, $limit;
			
	// Query definition

	// Set table name
	public function table($table) {
		$this->table = $table;
		return $this;		
	}
	
	// Set join tables (for select)
	public function join($join) {
		$this->join = $join;
		return $this;		
	}
	
	// Set where
	public function where($key, $value = NULL) {
		// override:
		// function has two arguments: use them as `$key` field = $value
		if (isset($value)) {
			$value = $this->quote($value);
			$this->where.= ($this->where ? ' AND ' : '')."`$key` = '$value'";
		}
		// function has one argument: use it as simple string
		else
			$this->where = $key;
		
		return $this;
	}

	// Set option	
	public function option($option) {
		$this->option = $option;
		return $this;		
	}

	// Set group	
	public function group($group) {
		$this->group = $group;
		return $this;		
	}

	// Set having	
	public function having($having) {
		$this->having = $having;
		return $this;		
	}
	
	// Set order
	public function order($order) {
		$this->order = $order;
		return $this;		
	}

	// Set limit
	public function limit($limit) {
		$this->limit = $limit;
		return $this;		
	}

	// set query type and fields
	// TODO: safe values arrays
	// TODO: array and object input
	
	// query generating

	public function selectQuery($fields = NULL) {
		// Generating field list for sql-query
		$fields = '';
		if (is_array($this->fields) and !empty($this->fields))
			foreach($this->fields as $field) {
				$fields.= ($fields ? ', ': NULL)."$field";
			}
		else
			$fields = '*';

		// Return query
		return
			"SELECT $fields".
			" FROM `{$this->table}`".
			($this->join ? ' '.$this->join : NULL).
			($this->where ? ' WHERE '.$this->where : NULL).
			($this->group ? ' GROUP BY '.$this->group : NULL).
			($this->having ? ' HAVING '.$this->having : NULL).
			($this->order ? ' ORDER BY '.$this->order : NULL).
			($this->limit ? ' LIMIT '.$this->limit : NULL).
			';';		
	}
	
	public function insertQuery($values = NULL) {
		// Check if $this->values array is associative
		//$keys = array_keys($this->values);
		//$isAssociative = array_keys($keys) !== $keys;
		
		// Generating lists of fields and their values
		// TODO: exception on empty $this->values
		$fields = '';
		$values = '';
		foreach($this->values as $key => $value) {
			if (isset($value)) {
				$key = $this->quote($key);
				$value = $this->quote($value);
				// Generating field list if $this->values array is associative
				//if ($isAssociative)
					$fields.= ($fields ? ', ': NULL)."`$key`";

				// Generating values list in both cases
				$values.= ($values ? ', ': NULL)."'$value'";
			}
		}
		
		
		return
			"INSERT INTO `{$this->table}`".
			($fields ? ' ('.$fields.')' : NULL).
			($values ? ' VALUES ('.$values.')' : NULL).
			';';
	}
	
	public function updateQuery() {
		$set = '';
						
		foreach($this->values as $key => $value) {
			$key = $this->quote($key);
			$value = $this->quote($value);
			$set.= ($set ? ', ': NULL)."`$key` = '$value'";
		}

		if ($set)
			return
				"UPDATE `{$this->table}`".
				' SET '.$set.
				($this->where ? ' WHERE '.$this->where : NULL).
				($this->option ? ' OPTION '.$this->option : NULL).
				';';
		else
			return NULL;
	}
	
	public function deleteQuery() {
/*
DELETE [LOW_PRIORITY] [QUICK] [IGNORE] FROM tbl_name
    [PARTITION (partition_name,...)]
    [WHERE where_condition]
    [ORDER BY ...]
    [LIMIT row_count]
*/
		return
			"DELETE FROM `{$this->table}`".
			($this->where ? ' WHERE '.$this->where : NULL).
			($this->order ? ' ORDER BY '.$this->order : NULL).
			($this->limit ? ' LIMIT '.$this->limit : NULL).
			';';
	}
	
	// Field values defenition
	
	public function values($values = NULL) { // TODO: merge with existed values
		if($values) $this->values = $values;
		return $this;
	}
	
	public function value($field, $value) {
		if(is_object($this->values))
			$this->values->$field = $this->quote($value);
		elseif(is_array($this->values))
			$this->values[$field] = $this->quote($value);
		else {
			$this->values = array();
			$this->values[$field] = $this->quote($value);
		}
		
		return $this;
	}
	
	public function clearValues() {
		$this->values = array();
	}
	
	public function fields($fields = NULL) { // TODO: merge with existed values
		if($fields) $this->fields = $fields;
		return $this;
	}
	
	public function field($field) {
		if(!is_array($this->fields))
			$this->fields = array();

		$this->fields[] = $this->quote($field);
		
		return $this;
	}

	// Safe query self methods
	
	function quote($string) {
		return
			addslashes($string);
	}
}

class yDbClass extends ySqlGenClass {
	public $sql;
	public static $static_sql;
	
	public function __construct($user = NULL, $password = NULL, $name = NULL, $host = NULL, $driver = 'mysql', $forced = false) {
		// Overriding emulation:
		if (is_object($user))
			// Second (not last) argument is $forced if object is given
			$forced = $password;
				
		if ($forced or is_null(self::$static_sql)) {
			// Create new ezSQL object if it's not crated yet or in forced mode
			$this->init($user, $password, $name, $host, $driver);
		}
		else {
			// Link object if already connected
			$this->sql = &self::$static_sql;
		}
	}
	
	public function init($user = NULL, $password = NULL, $name = NULL, $host = NULL, $driver = 'mysql') {
		// Overriding emulation:
		if (is_object($user)) {
			// Use ezSQL object if object is given
			$this->sql = $user;
			//get_class($user); //TODO: add class check
		}
		else {
			// Create ezSQL object if no object is given
			
			// Include ezSQL core and ezSQL database specific component	
			include_once "ezsql/ez_sql_core.php";
			include_once "ezsql/ez_sql_$driver.php";	
			
			$className = "ezSQL_$driver";
			
			if (isset($user))
				$this->sql = new $className($user, $password, $name, $host);
			elseif (isset(ySettings::$db))
				$this->sql = new $className(ySettings::$db->user, ySettings::$db->password, ySettings::$db->name, ySettings::$db->host);
		}
		
		// Static copy of ezSQL object for optimization
		self::$static_sql = &$this->sql;
		return $this;
	}
	
	// Query processing functions
	
	public function select($query = NULL) { //alias for selectResults
		return $this->selectResults($query);
	}
	
	public function selectResults($query = NULL) {
		return
			$this->sql->get_results(
				($query and is_string($query))
					? $query
					: $this->selectQuery($query)
			);		
	}
	
	public function selectCol($query = NULL) {
		return
			$this->sql->get_col(
				($query and is_string($query))
					? $query
					: $this->selectQuery($query)
			);
	}
	
	public function selectRow($query = NULL) {
		return
			$this->sql->get_row(
				($query and is_string($query))
					? $query
					: $this->selectQuery($query)
			);
	}
	
	public function selectVar($query = NULL) {
		return
			$this->sql->get_var(
				($query and is_string($query))
					? $query
					: $this->selectQuery($query)
			);
	}
	
	public function insert($query = NULL) {
		// case input ($query) is array of rows
		if(is_array($query)) {
			foreach ($query as $row) {
				$this->insert($row);
			}
		}
		// case input ($query) is a single row
		elseif(is_object($query)) {
			$this->values = $query;
			$this->insert();
		}
		// case input ($query) is not set or is a string
		else
			return
				$this->sql->query(
					($query and is_string($query))
						? $query
						: $this->insertQuery($query)
				);
	}
	
	public function update($query = NULL) {
		$query = ($query and is_string($query))
					? $query
					: $this->updateQuery($query);
		
		return $query ? $this->sql->query($query) : NULL;
	}
	
	public function delete($query = NULL) {
		return
			$this->sql->query(
				($query and is_string($query))
					? $query
					: $this->deleteQuery($query)
			);
	}
	
/*
	function query($query = NULL) {
		return $this->sql->query($query ? $query : $this->getQuery());
	}
*/
}

// yObjectClass processing
class ySqlClass extends yDbClass {
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
			elseif	($field->type == 'string')	$query.= "`{$field->key}` VARCHAR(255) NOT NULL";
			elseif	($field->type == 'text')	$query.= "`{$field->key}` TEXT NOT NULL";
			elseif	($field->type == 'list')	$query.= "`{$field->key}` INT NOT NULL";
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
				$value = $object->values[0]->{$field->key}; // TODO: select from all rows for selectResults
				// if type of this field is 'id' add it to WHERE
				if($field->type == 'id' && isset($value)) {
					$this->where($field->key, $value);
				}
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

	public function selectRow($object = NULL) { //alias
		return $this->select($object, 'Row');
	}

	public function selectQuery($object = NULL) { //alias
		return $this->select($object, 'Query');
	}
}

?>