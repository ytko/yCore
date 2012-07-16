<?php defined ('_YEXEC')  or  die();

class ySqlClass {
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
	public function where($key, $args = NULL) {
		// override:
		// function has two arguments: use them as `$key` field = $value
		if (isset($args)) {
			if (is_array($args) or is_object($args)) {
				$args = (object)$args;
				$value = $args->value;
				if(isset($args->collation)) $collation = $args->collation;
			} else {
				$value = $args;
				$collation = '=';
			}
			
			if ($collation == 'like') {
				$collation = 'LIKE';
				$value = "%$value%";
			} else {
				$collation = '=';
			}

			$value = $this->quote($value);
			$this->where.= ($this->where ? ' AND ' : '')."`$key` $collation '$value'";
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
	public function order($order, $direction = NULL) {
		if (isset($direction) && strtoupper($direction) == 'ASC')
			$direction = 'ASC';
		elseif (isset($direction) && strtoupper($direction) == 'DESC')
			$direction = 'DESC';
		else
			$direction = NULL;
		
		$this->order.= 
				($this->order ? ', ' : NULL).
				"`$order`".
				($direction ? ' '.$direction : NULL);
		
		return $this;		
	}

	// Set limit
	public function limit($limit, $offset = NULL) {
		if (isset($offset))
			//if (is_numeric($limit) && is_numeric($rows))
				$this->limit = "$offset, $limit";
		else
			//if (is_numeric($limit))
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
	
	public function values($values = NULL) {
		if(is_array($values) or is_object($values)) {
			foreach($values as $key => $value) {
				$this->value($key, $value);
			}
		}
		return $this;
	}
	
	public function value($field, $value) {
		if (is_array($this->values))
			$this->values = (object)$this->values;
		elseif (!is_object($this->values))
			$this->values = (object)array();
		
		$this->values->$field = $this->quote($value);
		
		return $this;
	}
	
	public function clearValues() {
		$this->values = (object)array();
		return $this;
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

class yDbClass extends ySqlClass {
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
		if(!is_string($query)) $query = $this->selectQuery($query);
		$result = $this->sql->get_results($query);
		if(!$result) $result = array();
		return $result;
	}

	public function selectCol($query = NULL) {
		if(!is_string($query)) $query = $this->selectQuery($query);
		$result = $this->sql->get_col($query);
		if(!$result) $result = array();
		return $result;
	}

	public function selectRow($query = NULL) {
		if(!is_string($query)) $query = $this->selectQuery($query);
		$result = $this->sql->get_row($query);
		if(!$result) $result = (object)array();
		return $result;
	}

	public function selectCell($query = NULL) {
		if(!is_string($query)) $query = $this->selectQuery($query);
		$result = $this->sql->get_var($query);
		return $result;
	}

	public function selectVar($query = NULL) { //alias for selectResults
		return $this->selectCell($query);
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

?>