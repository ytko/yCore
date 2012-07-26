<?php defined ('_YEXEC')  or  die();

yCore::includeModel();

class usersModel extends yModel {
	private static $userID, $login, $password; 
	
	public function getModel($controller) {
		$this->controller = $controller;
		
		$this->login($_POST['login'], $_POST['password']);

		echo 'uid:'.$this->userID();
	}
	
	function hash($password) {
		return md5($_SERVER['HTTP_ACCEPT_LANGUAGE'].$password.$_SERVER['HTTP_USER_AGENT']);
	}
	
	function login($login, $password) {
		if	(isset($login) && isset($password)) {

			$this->login = mysql_real_escape_string($_POST['login']);
			$this->password = $this->hash(md5($_POST['password']));
		
			setCookie("login", $this->login, time() + 3600, "/");
			setCookie("hash", $this->password, time() + 3600, "/");
		}
	}
	
	function run() {
		if
			(	(!isset($_SESSION['user_id'])) &&
				(isset($_COOKIE['login']) && isset($_COOKIE['hash'])) ) {

			$this->login = mysql_real_escape_string($_COOKIE['login']);
			$this->password = mysql_real_escape_string($_COOKIE['hash']);
		}	
		
		if	(isset($this->login) && isset($this->password)) {
		    // getting userdata from DB ----------------------------------------------------------- 
		    
		    $this->objectKey('users');
		    
		    $this->set->pagination = false;
		    $this->set->getItem = true;
		    $this->set->getItems = true;
		    
		    $this->addFilterSQL("`login`='{$this->login}'");
		    
		    $_ = $this->getObject();
		    
		    $db_password = $this->hash($_->items[0]->password);

		    // ------------------------------------------------------------------------------------		    
		    
		    if (!strcmp($db_password, $this->password)) {
		        $_SESSION['user_id'] = $_->items[0]->id;
		    }
		    else {
		    	setcookie('login', '');
		    	setcookie('hash', '');
		        die('Wrong login');
		    }
		}
	}
	
	public function userID() {
		if (!isset($this->userID))
			$this->run();
		
		if (isset($_SESSION['user_id'])) {
			return($_SESSION['user_id']);
		}	
		else {
			return NULL;
		}
	}
	
	//interface
	
	public function id() { //stub: get user id
		return $this->userID();
	}
	
	public function is($group) { //stub: check user rights
		//'user', 'moder', 'admin'
		return true;
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