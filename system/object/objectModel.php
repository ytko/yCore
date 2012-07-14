<?php defined ('_YEXEC')  or  die();

yFactory::includeModel();

class objectModelClass extends yModelClass {
	function install($object) {
		yFactory::db('object')->create($object);
		return $this;
	}
	
	function insert($object) {
		if ($object->values)
			yFactory::db('object')->insert($object);
		return $this;
	}
	
	//TODO: function updateObject($object)
	
	function getCat($object) {
		$object->values = yFactory::db('object')->select($object);
		return $this;
	}
	
	function get($object) {
		$object->values[0] = yFactory::db('object')->selectRow($object);
		return $this;
	}
}

?>