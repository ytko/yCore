<?php defined ('_YEXEC')  or  die();

class yModelClass {
	public $db;
	public $controller;
	
	public function __construct(&$db = NULL) {
		if ($db)
			$this->db = &$db;
		else
			$this->db = yFactory::getDb();
	}
	
	// ---- set* methods -------------------------------------------------------
	
	public function setDb(&$db) {
		$this->db = &$db;
		return $this;
	}
	
	public function setController(&$controller) {
		$this->controller = &$controller;
		return $this;
	}
}

?>