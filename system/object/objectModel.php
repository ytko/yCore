<?php defined ('_YEXEC')  or  die();

yFactory::linkModel();

class objectModelClass extends yModelClass {
	function install($object) {
		yFactory::getDb()->create($object);
		return $this;
	}
	
	function insert($object) {
		if ($object->values)
			yFactory::getDb()->insert($object);
		return $this;
	}
	
	//TODO: function updateObject($object)
	
	function getCat($object) {
		$object->values = yFactory::getDb()->select($object);
		return $this;
	}
	
	function get($object) {
		$object->values[0] = yFactory::getDb()->selectRow($object);
		return $this;
	}
}

?>