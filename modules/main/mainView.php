<?php defined ('_YEXEC')  or  die();

yFactory::linkView();

class mainViewClass extends yViewClass {
	function __construct($controller) {
		parent::__construct(false);
		
		$this->controller =  $controller;
	}
	
	function getView(&$_, $template) {
		if (isset($_->items))
			$this->quoteRecursive($_->items);
		if (isset($_->item))
			$this->quoteRecursive($_->item);
		
		$templateClass = yFactory::linkTemplate($template);
		return $this->getPage($templateClass, $_);
	}
}

?>