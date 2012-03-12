<?php defined ('_YEXEC')  or  die();

class yDbQueryClass {
	protected $db;
	protected $link;
	
	function __construct(&$db) {
		$this->link = mysql_pconnect(ySettings::$db->host, ySettings::$db->user, ySettings::$db->password) OR DIE("Не могу создать соединение ");
		mysql_select_db(ySettings::$db->name, $this->link) or die(mysql_error());
		
		$this->db = &$link;
	}
	
	function __destruct() {
		mysql_close();
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

		//$this->db->setQuery($query);
		//return $this->db->Query();
		
		($result = mysql_query($query, $this->link)) or die(mysql_error());
		
		return $result;
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