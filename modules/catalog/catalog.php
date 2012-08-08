<?php defined ('_YEXEC')  or  die();

yCore::load('objectClass');

class catalogClass extends objectClass {
	public
		$modelClass = 'catalogModel',
		$templateClass = 'catalogTemplate',
		$objectClass = 'catalogObject',
		$object = NULL,
		$admin = false;
	
	protected 
		$url = array();

// $params is array of parameters; $params keys:
	// - objectClass: name of object class //TODO: objectClass instance as parameter
	// - url: inner path (string or splitted to array)
	public function __construct($params = NULL) {
		//parent::__construct();
		if(is_array($params) || is_object($params)) {
			$params = (object)$params;
			if (isset($params->objectClass)) $this->setObjectClass($params->objectClass);
			if (isset($params->url)) $this->setUrl($params->url);
		}
		
		$this->object = yCore::create($this->objectClass);
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
	
	public function setUrl($url) {
		if (is_string($url))
			$this->url = explode('/', $url);
		elseif (is_array($url))
			$this->url = $url;
		return $this;
	}
	
	// selects method depending on $this->url
	public function get($url = NULL) {
		if (isset($url)) $this->setUrl($url);
		$page = end($this->url);

		if(!$this->admin)
			switch ($page) {
				case 'page':
					return $this->page();
				default:
					return $this->catalog($this->url);
			}
		else {
			switch ($page) {
				case 'page':
					return $this->edit();
				case 'add':
					return $this->pageAdd();
				case 'install':
					return $this->install();
				case 'uninstall':
					return $this->uninstall();
				case 'export':
					return $this->export();
				default:
					return $this->catalogEdit($this->url);
			}
		}
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