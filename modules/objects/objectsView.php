<?php defined ('_YEXEC')  or  die();

yFactory::linkView('default');

class objectsViewClass extends defaultViewClass {
	public $className = 'objects'; 
	
	function __construct($controller) {
		parent::__construct(false);
		
		$this->controller =  $controller;
	}
	
}

?>