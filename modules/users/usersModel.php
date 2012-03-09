<?php defined ('_YEXEC')  or  die();

yFactory::linkModel();

class usersModelClass extends yModelClass {
	private $userID, $login, $password; 
	
	public function getModel($controller) {
		$this->controller = $controller;
		
		$this->onceLogin();
		$this->run();
		echo $this->userID();
	}
	
	function hash($password) {
		return md5($_SERVER['HTTP_ACCEPT_LANGUAGE'].$password.$_SERVER['HTTP_USER_AGENT']);
	}
	
	function onceLogin() {
		if	(isset($_POST['login']) && isset($_POST['password'])) {

			$this->login = mysql_real_escape_string($_POST['login']);
			$this->password = $this->hash(md5($_POST['password']));
		
			setCookie("login", $login, time() + 3600, "/");
			setCookie("hash", $user_hash, time() + 3600, "/");
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
}

?>