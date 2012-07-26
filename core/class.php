<?php defined ('_YEXEC')  or  die();

@require_once 'base.php';

class yClass extends yBase{
	private $post, $get, $request, $url, $files;
	
	function __get($propertyName) {
		switch ($propertyName) {
			case 'post':
				return (object)$_POST;
				break;
			case 'get':
				return (object)$_GET;
				break;
			case 'request':
				return (object)array_merge($_POST, $_GET);
				break;
			case 'files':
				return (object)$_FILES;
				break;
		}
		
		return NULL;
	}
	
	public function getUrl() {
		return rtrim($_SERVER[REDIRECT_URL], '/');
	}
	
	public function getRequest($key) {
		if (!empty($_POST[$key]))
			return $_POST[$key];
		elseif (!empty($_GET[$key]))
			return $_GET[$key];
		elseif (isset($_POST[$key]))
			return $_POST[$key];
		elseif (isset($_GET[$key]))
			return $_GET[$key];
		else
			return NULL;
	}
		
	public function isRequestSet($key) {
		return (isset($this->post->$key) || isset($this->get->$key));
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