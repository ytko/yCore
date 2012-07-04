<?php defined ('_YEXEC')  or  die();

yFactory::linkModel();

class customformsModelClass extends yModelClass {
	function insertObject($object) {
		if ($object->values)
			return yFactory::getDb()->insert($object);
	}
	
	//TODO: function updateObject($object)
	
	function listObject($object) {
		$object->values = yFactory::getDb()->select($object);
		return $object;
	}
	
	function getObject($object) {
		$object->values[0] = yFactory::getDb()->selectRow($object);
		return $object;
	}
}

?>