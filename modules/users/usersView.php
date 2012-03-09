<?php defined ('_YEXEC')  or  die();

yFactory::linkView();

class defaultViewClass extends yViewClass {
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

		
		if (isset($templatePage))
			$this->templatePage = $templatePage;

		$result = $this->getPage(
				((isset($this->templatePage))
					? $this->templatePage
					:'default'),
				$this->_
			);
	
		return $result;
	}
}

?>