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

?>