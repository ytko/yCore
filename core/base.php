<?php defined ('_YEXEC')  or  die();

class yBaseClass {
	public $owner = NULL;
	
	public function setOwner($owner) {
		$this->owner = $owner;
		return $this;
	}
}

?>