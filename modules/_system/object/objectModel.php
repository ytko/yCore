<?php defined ('_YEXEC')  or  die();

yCore::includeModel();

class objectModel extends yModel {
	function install($object) {
		yCore::db('object')->create($object);
		return $this;
	}
	
	function replace($object) {
		if ($object->values)
			yCore::db('object')->replace($object);
		return $this;
	}
	
	function insert($object) {
		if ($object->values)
			yCore::db('object')->insert($object);
		return $this;
	}
	
	//TODO: function updateObject($object)
	
	function getCat($object) {
		$object->values = yCore::db('object')->select($object);
		return $this;
	}
	
	function get($object) {
		$object->values[0] = yCore::db('object')->selectRow($object);
		return $this;
	}
}

?>