<?php defined ('_YEXEC')  or  die();

@require_once 'base.php';

class yModel extends yBase {
	public $db;
	public $controller;
	
	public function __construct(&$db = NULL) {
		if ($db)
			$this->db = &$db;
		else
			$this->db = yCore::getDb();
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