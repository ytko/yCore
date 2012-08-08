<?php defined ('_YEXEC')  or  die();

yCore::load('yClass');

class objectClass extends yClass {
	// sets values for external filters from POST or GET
	public function recieve($object, $row = 0) {
		if($object->fields) foreach($object->fields as $field) {
			if($value = $this->getRequest($field->key)) {
				$object->value($field->key, $value, $row);
			}
		}
		if($object->filters) foreach($object->filters as &$filter) {
			if($filter->scope == 'get' || $filter->scope == 'external')
				if($value = $this->getGet($filter->key)) {
					$filter->value = $value;
				}
			elseif($filter->scope == 'post' || $filter->scope == 'external')
				if($value = $this->getPost($filter->key)) {
					$filter->value = $value;
				}
		}
		return $this;
	}
	
	public
		$objectName = 'catalog';

	public function catalog($categoryID = NULL) {
		$object = yCore::createObject($objectName);
		if($categoryID)
			$object->filter('category',
				array('type' => 'field', 'field' => 'category', 'show' => false, 'value' => $categoryID) );
		$this->recieve($object);
		$model = yCore::catalogModel()->catalog($object);
		return yCore::catalogTemplate($object)->catalog();
	}
}

/*
 * Copyright 2012 Roman Exempliarov. 
 *
 * This file is part of yCore framework.
 *
 * yCore is free software: you can redistribute it and/or modify it under the
 * terms of the GNU Lesser General Public License as published by the Free
 * Software Foundation, either version 2.1 of the License, or (at your option)
 * any later version.
 * 
 * yCore is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU Lesser General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with yCore. If not, see http://www.gnu.org/licenses/.
 */

?>