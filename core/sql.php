<?php defined ('_YEXEC')  or  die();

class ySqlClass {
	public $sql;
	public static $static_sql;
	public $mode, $fields, $table, $join, $where, $group, $having, $order, $limit;
	
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
		
	function select($fields = NULL) {
		$this->mode = 'select';
		if($fields) $this->fields = $fields;
		return $this;
	}
	
	function insert($fields = NULL) {
		$this->mode = 'insert';
		if($fields) $this->fields = $fields;
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
	
	function getQuery() {
		if (!$this->table) return;

		switch ($this->mode):
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
				
/*INSERT INTO <название таблицы> ([<Имя столбца>, ... ]) VALUES (<Значение>,...)
 * 
 * 			UPDATE [top(x)] <объект> 
SET <присваивание1 [, присваивание2, ...]> 
[WHERE <условие>]
[OPTION <хинт1 [, хинт2, ...]>]*/
				
/*			case 'insert':
				return
					'INSERT '.($this->fields ? $this->fields : '*').
					' INTO '.$this->table.
					($this->join ? ' '.$this->join : NULL).
					($this->where ? ' WHERE '.$this->where : NULL).
					($this->group ? ' GROUP BY '.$this->group : NULL).
					($this->having ? ' HAVING '.$this->having : NULL).
					($this->order ? ' ORDER BY '.$this->order : NULL).
					($this->limit ? ' LIMIT '.$this->limit : NULL).
					';';*/
		endswitch;
	}
	
	function get() {
		return $this->sql->get_results($this->getQuery());
	}
		
	function getResults() {
		return $this->get();
	}
	
	function getRow() {
		return $this->sql->get_row($this->getQuery());
	}
	
	function getVar() {
		return $this->sql->get_var($this->getQuery());
	}
}

?>