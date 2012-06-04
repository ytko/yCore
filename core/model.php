<?php defined ('_YEXEC')  or  die();

class yModelClass {
	public $db;
	public $controller;
	
	function __construct(&$db) {
		$this->db = &$db;
	}
	
	function setController(&$controller) {
		$this->controller = &$controller;		
	}
}

?>