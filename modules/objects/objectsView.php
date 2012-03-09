<?php defined ('_YEXEC')  or  die();

yFactory::linkView('default');

class objectsViewClass extends defaultViewClass {
	public $className = 'objects'; 
	
	function __construct($controller) {
		parent::__construct(false);
		
		$this->controller =  $controller;
	}
	
	function getView(&$_, $template = 'default', $templatePage = NULL) {
		$this->view = $template;
		$this->_ = &$_;
		$this->setURI();
		
		if (isset($_->items))
			_cQuoteRecursive($_->items);
		if (isset($_->item))
			_cQuoteRecursive($_->item);

		$result.= $this->getPage('objects', $_);
	
		return $result;
	}
}

?>