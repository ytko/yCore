<?php defined ('_YEXEC')  or  die();

yCore::load('objectClass');

class catalogClass extends objectClass {
	public
		$modelClass = 'catalogModel',
		$templateClass = 'catalogTemplate',
		$objectClass = 'catalogObject',
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
					return $this->pageEdit();
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
	
	public function catalog() {
		$this->recieve($this->object);
		$model = yCore::create($this->modelClass)->catalog($this->object);
		return yCore::create($this->templateClass, $this->object)->catalog();
	}

	public function page() {
		$this->recieve($this->object);
		yCore::create($this->modelClass)->page($this->object);
		return yCore::create($this->templateClass, $this->object)->page();
	}

	public function catalogEdit() {
		$this->recieve($this->object);
		$model = yCore::create($this->modelClass)->catalog($this->object);
		return yCore::create($this->templateClass, $this->object)->setAdmin(true)->catalog();
	}

	public function pageEdit() {
		$this->recieve($this->object);
		$model = yCore::create($this->modelClass)->replace($this->object)->page($this->object);
		return yCore::create($this->templateClass, $this->object)->setAdmin(true)->edit();
	}

	public function pageAdd() {
		$this->recieve($this->object);
		$model = yCore::create($this->modelClass)->insert($this->object);
		return yCore::create($this->templateClass, $this->object)->setAdmin(true)->edit();
	}

	public function install() {
		yCore::create($this->modelClass)->install($this->object);
		/*$this->object = yCore::catalogCategoryObject();
		yCore::catalogModel()->install($this->object);*/
	}

	public function uninstall() {
		yCore::create($this->modelClass)->uninstall($this->object);
	}

	public function export() {
		$model = yCore::create($this->modelClass)->export($this->object, 'input.csv');		
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