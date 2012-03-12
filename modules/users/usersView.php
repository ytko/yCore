<?php defined ('_YEXEC')  or  die();

yFactory::linkView();

class usersViewClass extends yViewClass {
	function __construct($controller) {
		parent::__construct(false);
		
		$this->controller =  $controller;
	}
	
}

?>