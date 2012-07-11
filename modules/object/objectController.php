<?php defined ('_YEXEC')  or  die();

yFactory::linkController();

class objectControllerClass extends yControllerClass {
	
	// gets POST or GET values and writes them to yObject container
	function getObject($object, $row = 0) {
		if($object->fields) foreach($object->fields as $field) {
			if($value = $this->getRequest($field->key)) {
				$object->value($field->key, $value, $row);
			}
		}
		return $this;
	}
}

?>