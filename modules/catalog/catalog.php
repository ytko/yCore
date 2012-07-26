<?php defined ('_YEXEC')  or  die();

yCore::load('object');

class catalogClass extends objectClass {
	public function catalog($categoryID = NULL) {
		$object = yCore::catalogObject();
		if($categoryID)
			$object->filter('category',
				array('type' => 'field', 'field' => 'category', 'show' => false, 'value' => $categoryID) );
		$this->recieve($object);
		$model = yCore::catalogModel()->catalog($object);
		return yCore::catalogTemplate($object)->catalog();
	}
	
	public function page() {
		$object = yCore::catalogObject();
		$this->recieve($object);
		yCore::objectModel()->page($object);
		return yCore::catalogTemplate($object)->page();
	}

	public function catalogEdit() {
		$object = yCore::catalogObject();
		if($categoryID)
			$object->filter('category',
				array('type' => 'field', 'field' => 'category', 'show' => false, 'value' => $categoryID) );
		$this->recieve($object);
		$model = yCore::catalogModel()->catalog($object);
		return yCore::catalogTemplate($object)->setMode('admin')->catalog();
	}
	
	public function pageEdit() {
		$object = yCore::catalogObject();
		$this->recieve($object);
		$model = yCore::catalogModel()->replace($object)->page($object);
		return yCore::catalogTemplate($object)->setMode('admin')->edit();
	}
	
	public function pageAdd() {
		$object = yCore::catalogObject();
		$this->recieve($object);
		$model = yCore::catalogModel()->insert($object);
		return yCore::catalogTemplate($object)->setMode('admin')->edit();
	}
	
	public function install() {
		$object = yCore::catalogObject();
		yCore::catalogModel()->install($object);
		$object = yCore::catalogCategoryObject();
		yCore::catalogModel()->install($object);
	}
	
	public function export() {
		$object = yCore::catalogObject();
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