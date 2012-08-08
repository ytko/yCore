<?php defined ('_YEXEC')  or  die();

yCore::load('objectClass');

class catalogClass extends objectClass {
	public
		$modelClass = 'catalogModel',
		$templateClass = 'catalogTemplate',
		$objectClass = 'catalogObject',
		$object = NULL;
		
	public function __construct($object = NULL) {
		parent::__construct();

		if(isSet($object))
			$this->setObject($object);
	}
		
	public function setObject($object = NULL) {
		if(!isSet($object)) {
			if(!isSet($this->object)) 
				$this->object = yCore::create($this->objectClass);
		} elseif(is_string($object)) {
			$this->objectClass = $object;
			$this->object = yCore::create($this->objectClass);
		} elseif(is_object($object)) {
			$this->objectClass = get_class($object);
			$this->object = $object;
		} else {
			// throw exception
		}
		
		return $this;
	}
	
	public function getObject() {
		if(!isSet($this->object))
			$this->setObject();

		return $this->object;
	}

	public function catalog($categoryID = NULL) {
		$object = $this->getObject();
		if($categoryID)
			$object->filter('category',
				array('type' => 'field', 'field' => 'category', 'show' => false, 'value' => $categoryID) );
		$this->recieve($object);
		$model = yCore::create($this->modelClass)->catalog($object);
		return yCore::create($this->templateClass, $object)->catalog();
	}

	public function page() {
		$object = $this->getObject();
		$this->recieve($object);
		yCore::create($this->modelClass)->page($object);
		return yCore::create($this->templateClass, $object)->page();
	}

	public function catalogEdit() {
		$object = $this->getObject();
		if($categoryID)
			$object->filter('category',
				array('type' => 'field', 'field' => 'category', 'show' => false, 'value' => $categoryID) );
		$this->recieve($object);
		$model = yCore::create($this->modelClass)->catalog($object);
		return yCore::create($this->templateClass, $object)->setMode('admin')->catalog();
	}

	public function edit() {
		$object = $this->getObject();
		$this->recieve($object);
		$model = yCore::create($this->modelClass)->replace($object)->page($object);
		return yCore::create($this->templateClass, $object)->setMode('admin')->edit();
	}

	public function add() {
		$object = $this->getObject();
		$this->recieve($object);
		$model = yCore::create($this->modelClass)->insert($object);
		return yCore::create($this->templateClass, $object)->setMode('admin')->edit();
	}

	public function install() {
		$object = $this->getObject();
		yCore::create($this->modelClass)->install($object);
		/*$object = yCore::catalogCategoryObject();
		yCore::catalogModel()->install($object);*/
	}

	public function uninstall() {
		$object = $this->getObject();
		yCore::create($this->modelClass)->uninstall($object);
	}

	public function export() {
		$object = $this->getObject();
		$model = yCore::create($this->modelClass)->export($object, 'input.csv');		
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