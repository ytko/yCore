<?php defined ('_YEXEC')  or  die();

yFactory::linkModel();

class customformsModelClass extends yModelClass {
	function installObject($object) {
		yFactory::getDb()->create($object);
		return $this;
	}
	
	function insertObject($object) {
		if ($object->values)
			yFactory::getDb()->insert($object);
		return $this;
	}
	
	//TODO: function updateObject($object)
	
	function listObject($object) {
		$object->values = yFactory::getDb()->select($object);
		return $this;
	}
	
	function getObject($object) {
		$object->values[0] = yFactory::getDb()->selectRow($object);
		return $this;
	}
}

?>