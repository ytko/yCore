<?php defined ('_YEXEC')  or  die();

yFactory::includeController();

class objectControllerClass extends yControllerClass {
	// sets values fo external filters from POST or GET
	function get($object) {
		if($object->fields) foreach($object->fields as $field) {
			if($value = $this->getRequest($field->key)) {
				$object->value($field->key, $value, $row);
			}
		}
		if($object->filters) foreach($object->filters as &$filter) {
			if($filter->scope == 'external')
				if($value = $this->getRequest($filter->key)) {
					$filter->value = $value;
				}
		}
		return $this;
	}
}

?>