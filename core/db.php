<?php defined ('_YEXEC')  or  die();

// Include ezSQL core
include_once "ezsql/ez_sql_core.php";
// Include ezSQL database specific component		
include_once "ezsql/ez_sql_mysql.php";

class yDbClass {
	public $Query;
	public $cms_prefix, $com_prefix;
	public $isUser = false, $isAdmin = false; // $isAdmin === can modify structure / $isUser === can modify records / evrybody can read records
	public $filters = NULL;
	
	public $fields = array(), $from = NULL, $where = NULL, $groupby = NULL, $orderby = NULL, $limit = NULL; //$from!!! - сделать

	function __construct($prefix, $com_prefix, $isUser = false, $isAdmin = false) {
		//$this->setObj($name);
		$this->isAdmin = $isAdmin;
		$this->isUser = $isUser;
		
		$this->cms_prefix = $prefix;
		$this->com_prefix = $com_prefix;
		
		//$this->Query = &$_q;

		$this->Query = new ezSQL_mysql(ySettings::$db->user, ySettings::$db->password, ySettings::$db->name, ySettings::$db->host);
	}
	
// $from, $where, $limit

	function addWhere($where, $logic = 'AND') {
		/*$this->Query->where.= ((empty($this->Query->where))
			? NULL
			: " $logic "
		)."($where)"; //!удалить
		*/
		$this->where.= ((empty($this->where))
			? NULL
			: " $logic "
		)."($where)"; //добавть отправку в _qClass
	}
	
	function setLimit($limit) {		
		$this->limit = $limit;
	}
	
	function setGroup($groupby) {
		$this->groupby = $groupby;
	}
	
	function setOrder($orderby) {
		$this->orderby = $orderby;
	}
	
	function addField($field, $key = NULL) {
		if (isset($key))
			$this->fields[$key] = $field;
		else
			$this->fields[] = $field;
	}
	
	function reset($property = NULL) {
		if ( (!isset($property)) || (!strcmp($property, 'fields')))
			$this->fields = array();
		if ( (!isset($property)) || (!strcmp($property, 'from')))			
			$this->from = NULL;
		if ( (!isset($property)) || (!strcmp($property, 'where')))			
			$this->where = NULL;
		if ( (!isset($property)) || (!strcmp($property, 'orderby')))			
			$this->orderby = NULL;
		if ( (!isset($property)) || (!strcmp($property, 'limit')))			
			$this->limit = NULL;
	}
	
// TABLES ------------------------------------------------------------

	function createTable($table, $fields = NULL) {
		if (!($this->isAdmin&&$this->isUser)) return false;
		if (empty($fields)) $fields = array();
		
		$query = 
			"CREATE TABLE `{$this->getTableName($table)}` ( ".
			"`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY";

		foreach ($fields as $field_key => $field_value) {
			if (!strcmp($field_value, 'text'))
				$query.= ", `$field_key` TEXT NOT NULL";
		}

		$query.=
			") ENGINE = MYISAM ;";

		return $this->Query->query($query);
	}
	
	function dropTable($table) {
		if (!($this->isAdmin&&$this->isUser)) return false;
		
		$query = "DROP TABLE `{$this->getTableName($table)}`";

		return $this->Query->query($query);
	}
	
	function tableAddFields($table, $fields) {
	//добавление полей объекта
		if (!($this->isAdmin&&$this->isUser)) return false;

		$query = "ALTER TABLE `{$this->getTableName($table)}` ";
		
		$i = 0;
		foreach ($fields as $key => $value) {
			$query.= ($i ? ', ' : NULL); $i++;			
			if (!strcmp($value['type'], 'text'))
				$query.= "ADD `$key` TEXT NOT NULL";
			if (!strcmp($value['type'], 'integer') || !strcmp($value['type'], 'int'))
				$query.= "ADD `$key` INT NOT NULL";
			if (!strcmp($value['type'], 'float'))
				$query.= "ADD `$key` FLOAT NOT NULL";
			if (!strcmp($value['type'], 'items'))
				$query.= "ADD `$key` INT NOT NULL";
			if (!strcmp($value['type'], 'time'))
				$query.= "ADD `$key` TIMESTAMP NOT NULL";
		}
		
		return $this->Query->query($query);
	}
	
	function addIndex($table, $field, $type = '') { //сделать распознование $field - строка или массив(набор строк), сейчас только для строки
		if (!($this->isAdmin&&$this->isUser)) return false;
		
		$tableName = $this->getTableName($table);
		
		//проверка есть ли такой индекс
		//$query = "SHOW INDEX FROM `$tableName`";
		//foreach ($this->Query->get_results($query) as ... ) {	...
		//}
				
		if (!strcmp($type, 'fullText'))
			$query = "ALTER TABLE `$tableName` ADD FULLTEXT `$field` (`$field`)";
		else
			return;
			
		return $this->Query->query($query);				
	}
	
	function tableDropFields($table, $fields) { //setObjRec getObjRec
	//удаление полей объекта
		if (!($this->isAdmin&&$this->isUser)) return false;
	
		$query = "ALTER TABLE `{$this->getTableName($table)}` ";
		
		$i = 0;
		foreach ($fields as $key => $value) {
			$query.= ($i ? ', ' : NULL); $i++;
			if (is_int($key)) {
				$query.= "DROP `$value`";
				unset($fields[$key]);
				$fields[$value] = $value;
			}		
			else
				$query.= "DROP `$key`";
		}

		return $this->Query->query($query);
	}
	
// ITEMS --------------------------------------------------------------
	
	function getItemQuery($table) {
	// универсальная часть для getItems и getItem
		$query = "SELECT ";
		if (!count($this->fields))
			$query.= "*";
		else {
			$i = 0;
			foreach ($this->fields as $key => $value) {
				$query.= ($i ? ', ' : NULL); $i++;	
				if (is_int($key))
					$query.= "$value";
				else
					$query.= "$value AS `$key`";
			}
		}
		
		$query.= " FROM `{$this->getTableName($table)}`";
		
		if (!empty($this->from)) {
			$query.= " {$this->from}";
		}
		
		if (!empty($this->where)) {
			$query.= " WHERE {$this->where}";
		}
		
		if (!empty($this->groupby)) {
			$query.= " GROUP BY {$this->groupby}";
		}
				
		if (!empty($this->orderby)) {
			$query.= " ORDER BY {$this->orderby}";
		}	
		
		if (!empty($this->limit)) {
			$query.= " LIMIT {$this->limit}";
		}		

		return $query;
	}
	
	function getItems($table) {
	// Получить таблицу в виде массива объектов
	// список полей передается в виде массива $fields; если ключ текстовый, то он используется в запросе как для "AS $key"
		$query = $this->getItemQuery($table);
		
		$this->reset();
		
		return $this->Query->get_results($query);
	}

	function getItem($table, $id = NULL) {
	// Получить строку таблицы в виде объекта
	// список полей передается в виде массива $fields; если ключ текстовый, то он используется в запросе как для "AS $key"
		if(!empty($id)) $this->addWhere("`id` = '$id'");
		$this->setLimit(1);
		$query = $this->getItemQuery($table, $fields);

		$this->reset();

		return (object)$this->Query->get_row($query);
	}
	
	function insertItem($table, $values) {
		if (!$this->isUser) return false;	
		$queryColumns = '';
		$queryValues = '';
		$queryUpdate = '';
		
		$i = 0;
		foreach ($values as $key => $value) {
			//$value = $this->Query->quote($value);
			$queryColumns.= (($i)?', ':NULL)."`$key`";
			$queryValues.= (($i)?', ':NULL)."'$value'";
			$queryUpdate.= (($i)?', ':NULL)."`$key` = '$value'";
			$i++;
		}
				
		$query = "INSERT INTO `{$this->getTableName($table)}` ($queryColumns) VALUES ($queryValues) ".
				 "ON DUPLICATE KEY UPDATE $queryUpdate";

		if (!empty($this->where)) {
			$query.= " WHERE {$this->where}";
		}	
		
		if (!empty($this->limit)) {
			$query.= " LIMIT {$this->limit}";
		}	
		
		$this->reset();

		return $this->Query->query($query);		
	}
	
	function deleteItems($table) {
		if (!$this->isUser) return false;		
		$query = "DELETE FROM `{$this->getTableName($table)}`";
		
		if (!empty($this->from)) {
			$query.= " {$this->from}";
		}
		
		if (!empty($this->where)) {
			$query.= " WHERE {$this->where}";
		}	
		
		if (!empty($this->limit)) {
			$query.= " LIMIT {$this->limit}";
		}
		
		$this->reset();		
		
		return $this->Query->query($query);
	}
	

// inner --------------------------------------------------------------
	
	function getTableName($table) {
		return "{$this->cms_prefix}{$this->com_prefix}{$table}";
	}

}

?>