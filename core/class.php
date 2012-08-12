<?php defined ('_YEXEC')  or  die();

@require_once 'base.php';

class yClass extends yBase{
	private $post, $get, $request, $files, $url;

	function __construct() {
	}

	function __get($propertyName) {
		switch ($propertyName) {
			case 'post':	return (object)$_POST;
			case 'get':		return (object)$_GET;
			case 'request':	return (object)array_merge($_POST, $_GET);
			case 'files':	return (object)$_FILES;
			case 'url':		return $this->getUrl();
			default:		return NULL;
		}
	}

	public function getUrlString() {
		if(!isSet($url)) $url = $_SERVER['REQUEST_URI'];
		$uri = explode('?', $url);
		return trim($uri[0], '/');
	}

	public function getUrl() {
		if	(isSet($this->url))	return $this->url;
		else					return explode('/', $this->getUrlString());
	}

	public function setUrl($url) {
		if		(is_array($url))	$this->url = $url;
		elseif	(is_string($url))	$this->url = explode('/', $url);
		else						throw new Exception("\$url must be string, array or NULL");

		return $this;
	}

	public function getRequest($key) {
		if (!empty($_POST[$key]))		return $this->getPost($key);
		elseif (!empty($_GET[$key]))	return $this->getGet($key);
		elseif (isset($_POST[$key]))	return $this->getPost($key);
		elseif (isset($_GET[$key]))		return $this->getGet($key);
		else							return NULL;
	}

	public function getGet($key) {
		if(isSet($_GET[$key])) {
			$result = $_GET[$key];
			if(is_array($result))
				$result = (object)$result;
		} else {
			$result = NULL;
		}
		return $result;
	}

	public function getPost($key) {
		if(isSet($_POST[$key])) {
			$result = $_POST[$key];
			if(is_array($result))
				$result = (object)$result;
		} else {
			$result = NULL;
		}
		return $result;
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