<?php defined ('_YEXEC')  or  die();

class yBaseClass {
	public $owner = NULL;
	
	public function setOwner($owner) {
		$this->owner = $owner;
		return $this;
	}
	
	public function setProperty($key, $value) {
		$this->$key = $value;
		return $this;
	}
}

?>