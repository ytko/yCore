<?php defined ('_YEXEC')  or  die();

class ySqlClass {
	public $sql;
	public static $static_sql;
	public
			$mode, // Can be 'select', 'insert', 'insert_or_update', etc. Changes with last ->select(), ->insert(), etc. method called.
			$fields, // Used for SELECT
			$table, // Used for SELECT FROM $table, INSERT INTO $table, etc.
			$values, // Used for INSERT and UPDATE
			$join, $where, $option, $group, $having, $order, $limit;
	
	function __construct($user = NULL, $password = NULL, $name = NULL, $host = NULL, $driver = 'mysql', $forced = false) {
		// Overriding emulation:
		if (is_object($user))
			// Second (not last) argument is $forced if object is given
			$forced = &$password;
		
		if (($forced or !$this->static_sql)) {
			// Create new ezSQL object if it's not crated yet or in forced mode
			$this->init($user, $password, $name, $host, $driver);
		}
		else {
			// Link object if already connected
			$this->sql = &$this->static_sql;
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
			$this->sql = new $className($user, $password, $name, $host);			
		}
		
		// Static copy of ezSQL object for optimization
		$this->static_sql = $this->sql;
		
		return $this;
	}

	// query generating
	
	function select($fields = NULL) {
		$this->mode = 'select';
		if($fields) $this->fields = $fields;
		return $this;
	}
	
	function insert($values = NULL) {
		$this->mode = 'insert';
		if($values) $this->values = $values;
		return $this;
	}
	
	function update($values = NULL) {
		$this->mode = 'update';
		if($values) $this->values = $values;
		return $this;
	}
	
	function set($values = NULL) { // alias for insertOrUpdate()
		return $this->insertOrUpdate($values);
	}
	
	function insertOrUpdate($values = NULL) {
		$this->mode = 'insert_or_update';
		if($values) $this->values = $values;
		return $this;
	}
	
	function from($table) {
		$this->table = $table;
		return $this;		
	}
	
	function join($join) {
		$this->join = $join;
		return $this;		
	}
	
	function where($where) {
		$this->where = $where;
		return $this;		
	}
	
	function option($option) {
		$this->option = $option;
		return $this;		
	}
	
	function group($group) {
		$this->group = $group;
		return $this;		
	}
	
	function having($having) {
		$this->having = $having;
		return $this;		
	}
	
	function order($order) {
		$this->order = $order;
		return $this;		
	}
	
	function limit($limit) {
		$this->limit = $limit;
		return $this;		
	}
	
	// query executing
	
	function getQuery($mode = NULL) {
		if (!$this->table) return;

		$mode = ($mode ? $mode : $this->mode);
		
		switch ($mode):
			case 'select':
				return
					'SELECT '.($this->fields ? $this->fields : '*').
					' FROM '.$this->table.
					($this->join ? ' '.$this->join : NULL).
					($this->where ? ' WHERE '.$this->where : NULL).
					($this->group ? ' GROUP BY '.$this->group : NULL).
					($this->having ? ' HAVING '.$this->having : NULL).
					($this->order ? ' ORDER BY '.$this->order : NULL).
					($this->limit ? ' LIMIT '.$this->limit : NULL).
					';';
			case 'insert':
			case 'insert_or_update':
				$fields = '';
				$values = '';
				
				// Chech if array $this->values is associative
				$keys = array_keys($this->values);
				$isAssociative = array_keys($keys) !== $keys;
				
				// Generating lists of fields and their values
				foreach($this->values as $key => $value) {
					if ($isAssociative)
						$fields.= ($fields ? ', ': NULL)."`$key`";
					
					$values.= ($value ? ', ': NULL).$value;
				}
				
				$insert =
					'INSERT INTO '.$this->table.
					($fields ? ' ('.$fields.')' : NULL).
					' VALUES ('.$values.')';
				
				// Do not return if 'insert_or_update'
				if ($mode == 'insert') return $insert.';';
			case 'update':
			//and 'insert_or_update': 
				$set = '';
								
				foreach($this->values as $key => $value) {
					$set.= ($set ? ', ': NULL)."`$key` = $value";
				}

				$update =
					'UPDATE '.$this->table.
					' SET '.$set.
					($this->where ? ' WHERE '.$this->where : NULL).
					($this->option ? ' WHERE '.$this->option : NULL);

				if ($mode == 'update') return $update.';';
			//case 'insert_or_update':
		endswitch;
	}
	
	function get($query = NULL) {
		return $this->sql->get_results($query ? $query : $this->getQuery());
	}
		
	function getResults($query = NULL) { // alias for get()
		return $this->get($query);
	}
	
	function getRow($query = NULL) {
		return $this->sql->get_row($query ? $query : $this->getQuery());
	}
	
	function getVar($query = NULL) {
		return $this->sql->get_var($query ? $query : $this->getQuery());
	}
}

?>