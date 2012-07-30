<?php defined ('_YEXEC')  or  die();

yCore::load('objectClass');

class catalogClass extends objectClass {
	public
		$modelClass = 'catalogModel',
		$templateClass = 'catalogTemplate',
		$objectClass = 'catalogObject';

	public function catalog($categoryID = NULL) {
		$object = yCore::create($this->objectClass);
		if($categoryID)
			$object->filter('category',
				array('type' => 'field', 'field' => 'category', 'show' => false, 'value' => $categoryID) );
		$this->recieve($object);
		$model = yCore::create($this->modelClass)->catalog($object);
		return yCore::create($this->templateClass, $object)->catalog();
	}

	public function page() {
		$object = yCore::create($this->objectClass);
		$this->recieve($object);
		yCore::create($this->modelClass)->page($object);
		return yCore::create($this->templateClass, $object)->page();
	}

	public function catalogEdit() {
		$object = yCore::create($this->objectClass);
		if($categoryID)
			$object->filter('category',
				array('type' => 'field', 'field' => 'category', 'show' => false, 'value' => $categoryID) );
		$this->recieve($object);
		$model = yCore::create($this->modelClass)->catalog($object);
		return yCore::create($this->templateClass, $object)->setMode('admin')->catalog();
	}

	public function pageEdit() {
		$object = yCore::create($this->objectClass);
		$this->recieve($object);
		$model = yCore::create($this->modelClass)->replace($object)->page($object);
		return yCore::create($this->templateClass, $object)->setMode('admin')->edit();
	}

	public function pageAdd() {
		$object = yCore::create($this->objectClass);
		$this->recieve($object);
		$model = yCore::create($this->modelClass)->insert($object);
		return yCore::create($this->templateClass, $object)->setMode('admin')->edit();
	}

	public function install() {
		$object = yCore::create($this->objectClass);
		yCore::create($this->modelClass)->install($object);
		/*$object = yCore::catalogCategoryObject();
		yCore::catalogModel()->install($object);*/
	}

	public function uninstall() {
		$object = yCore::create($this->objectClass);
		yCore::create($this->modelClass)->uninstall($object);
	}

	public function export() {
		$object = yCore::create($this->objectClass);
		$model = yCore::catalogModel()->export($object);		
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