<?php defined ('_YEXEC')  or  die();

yCore::load('yModel');

class objectModel extends yModel {
	function install($object) {
		yCore::objectDb()->create($object);
		return $this;
	}

	function uninstall($object) {
		yCore::objectDb()->drop($object);
		return $this;
	}

	function replace($object) {
		if ($object->values)
			yCore::objectDb()->replace($object);
		return $this;
	}

	function insert($object) {
		if ($object->values)
			yCore::objectDb()->insert($object);
		return $this;
	}

	//TODO: function updateObject($object)

	function catalog($object) {
		$object->values = yCore::objectDb()->select($object);
		return $this;
	}

	function page($object) {
		$object->values[0] = yCore::objectDb()->selectRow($object);
		return $this;
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