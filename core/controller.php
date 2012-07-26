<?php defined ('_YEXEC')  or  die();

@require_once 'base.php';

class yController extends yBase{
	public $post, $get, $url, $files;
	public $controllerName;

	function __construct($controllerName = NULL) {	
		$this->post = (object) $_POST;
		$this->get = (object) $_GET;
		$this->files = $_FILES;
		$this->url = $_SERVER["REDIRECT_URL"];
		$this->controllerName = $controllerName;
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