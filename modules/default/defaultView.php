<?php defined ('_YEXEC')  or  die();

yFactory::linkView();

class defaultViewClass extends yViewClass {
	function __construct($controller) {
		parent::__construct(false);
		
		$this->controller =  $controller;
	}
	
	function setURI() {
		$_ = &$this->_;
		
		//генерация массива (объекта) uri для полученных элементов
		if (is_array($_->items)) foreach($_->items as $key => &$item) {
			$uri = (object)array();
		
			if(isset($this->controller->cms->get)) {
				foreach ($this->controller->cms->get as $getKey => $getValue)
					$uri->$getKey = $getValue;
			}
		
			if(isset($this->controller->cms->mvc)) {
				foreach ($this->controller->cms->mvc as $mvcKey => $mvcValue)
					if (isset($this->controller->get->$mvcValue))
					$uri->$mvcValue = $this->controller->get->$mvcValue;
			}
		
			if(isset($_->objectID))
				$uri->oid = $_->objectID;
			if(isset($item->id))
				$uri->id = $item->id;
		
			$item->uri = $uri;
		}
		
		//генерация массива (объекта) uri для полученного списка объектов
		if (is_array($_->objectList->items)) foreach($_->objectList->items as $key => &$item) {
			$uri = (object)array();
		
			if(isset($this->controller->cms->get)) {
				foreach ($this->controller->cms->get as $getKey => $getValue)
					$uri->$getKey = $getValue;
			}
		
			if(isset($this->controller->cms->mvc)) {
				foreach ($this->controller->cms->mvc as $mvcKey => $mvcValue)
					if (isset($this->controller->get->$mvcValue))
					$uri->$mvcValue = $this->controller->get->$mvcValue;
			}
		
			if(isset($item->id))
				$uri->oid = $item->id;
		
			$item->uri = $uri;
		}
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