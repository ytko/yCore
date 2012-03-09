<?php defined ('_YEXEC')  or  die();

class yDbQueryClass {
	protected $db;
	
	function __construct(&$db) {
		$this->db = &$db;	
	}
	
	function quote($str) {
		/*if (!get_magic_quotes_gpc()) {
			$test = addslashes($test);
		}*/
		//echo get_magic_quotes_gpc();
		$str = addslashes($str);
		return $str;
	}
	
	function run($query) {
		yDebug::query($query);		

		$this->db->setQuery($query);
		return $this->db->Query();
	}
	
	function getRow($query) {
		$resource = $this->run($query);

		if (!$resource) return false;

		return (object) mysql_fetch_array($resource, MYSQL_ASSOC);
	}
	
	function getRows($query) {
		$result = array();
		$resource = $this->run($query);

		if (!$resource) return false;

		while ($row = mysql_fetch_array($resource, MYSQL_ASSOC)) {
		    $result[] = (object) $row;
		}
		
		return $result;
	}
	
	//getRow getRows -> getRecord/getTable ?
}

?>