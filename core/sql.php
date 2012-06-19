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
	function table($table) {
		$this->table = $table;
		return $this;		
	}
	
	// Set join tables (for select)
	function join($join) {
		$this->join = $join;
		return $this;		
	}
	
	// Set where
	function where($where) {
		$this->where = $where;
		return $this;		
	}

	// Set option	
	function option($option) {
		$this->option = $option;
		return $this;		
	}

	// Set group	
	function group($group) {
		$this->group = $group;
		return $this;		
	}

	// Set having	
	function having($having) {
		$this->having = $having;
		return $this;		
	}
	
	// Set order
	function order($order) {
		$this->order = $order;
		return $this;		
	}

	// Set limit
	function limit($limit) {
		$this->limit = $limit;
		return $this;		
	}

	// set query type and fields
	// TODO: safe values arrays
	// TODO: array and object input
	
	// query generating

	function selectQuery($fields = NULL) {
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
	
	function insertQuery($values = NULL) {
		// Check if $this->values array is associative
		//$keys = array_keys($this->values);
		//$isAssociative = array_keys($keys) !== $keys;
		
		// Generating lists of fields and their values
		// TODO: exception on empty $this->values
		$fields = '';
		$values = '';
		foreach($this->values as $key => $value) {
			if (isset($value)) {
				// Generating field list if $this->values array is associative
				//if ($isAssociative)
					$fields.= ($fields ? ', ': NULL)."`$key`";

				// Generating values list in both cases
				$values.= ($values ? ', ': NULL)."'".$this->quote($value)."'";
			}
		}
		
		return
			"INSERT INTO `{$this->table}`".
			($fields ? ' ('.$fields.')' : NULL).
			' VALUES ('.$values.')'.
			';';
	}
	
	function updateQuery() {
		$set = '';
						
		foreach($this->values as $key => $value) {
			$set.= ($set ? ', ': NULL)."`$key` = $value";
		}

		return
			"UPDATE `{$this->table}`".
			' SET '.$set.
			($this->where ? ' WHERE '.$this->where : NULL).
			($this->option ? ' OPTION '.$this->option : NULL).
			';';
	}
	
	function deleteQuery() {
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
	
	function values($values = NULL) { // TODO: merge with existed values
		if($values) $this->values = $values;
		return $this;
	}
	
	function value($field, $value) {
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
	
	function fields($fields = NULL) { // TODO: merge with existed values
		if($fields) $this->fields = $fields;
		return $this;
	}
	
	function field($field) {
		if(is_array($this->fields))
			$this->fields[] = $this->quote($field);
		else {
			$this->fields = array();
			$this->fields[] = $this->quote($field);
		}
		
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
	
	function __construct($user = NULL, $password = NULL, $name = NULL, $host = NULL, $driver = 'mysql', $forced = false) {
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
	
	function init($user = NULL, $password = NULL, $name = NULL, $host = NULL, $driver = 'mysql') {
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
	
	function select($query = NULL) {
		return
			$this->sql->get_results(
				($query and is_string($query))
					? $query
					: $this->selectQuery($query)
			);
	}
	
	function selectCol($query = NULL) {
		return
			$this->sql->get_col(
				($query and is_string($query))
					? $query
					: $this->selectQuery($query)
			);
	}
	
	function selectRow($query = NULL) {
		return
			$this->sql->get_row(
				($query and is_string($query))
					? $query
					: $this->selectQuery($query)
			);
	}
	
	function insert($query = NULL) {
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
	
	function update($query = NULL) {
		return
			$this->sql->query(
				($query and is_string($query))
					? $query
					: $this->updateQuery($query)
			);
	}
	
	function delete($query = NULL) {
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
	
	function get($query = NULL) {
		return $this->sql->get_results($query ? $query : $this->getQuery());
	}
	
	function getRow($query = NULL) {
		return $this->sql->get_row($query ? $query : $this->getQuery());
	}
	
	function getVar($query = NULL) {
		return $this->sql->get_var($query ? $query : $this->getQuery());
	}
*/
}

// yObjectClass processing
class ySqlClass extends yDbClass {
	/*function insert($query = NULL) {
		// Override if $query is object
		if (!is_object($query))
			return parent::insert($query);
		else
		{
			$this->table($query->table);

			foreach($query->values as $row) {
				$this->values = $row;
				parent::insert();
			}
			
			return;
		}
	}*/
}

?>