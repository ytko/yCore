<?php defined ('_YEXEC')  or  die();

class yViewClass {
	public
		$body,
		$head;
	
	public $view, $defaultview = 'default';

	function getStyle() {
		return $this->style;
	}

	function getScript() {
		return $this->script;
	}

	function __construct() {
		$view->body = '';
		$view->head = '';
		$view->breadcrumbs = array();
	}
		
	function getPage($templateClass, &$_ = NULL) {
		ob_start();
		$templateClass::body($_);
		$this->body.= ob_get_contents();
		ob_end_clean();
		
		ob_start();
		$templateClass::head($_);
		$this->head.= ob_get_contents();
		ob_end_clean();
		
		return $this;
	}
	
	function getView(&$_, $template) {
		if (isset($_->items))
			$this->quoteRecursive($_->items);
		if (isset($_->item))
			$this->quoteRecursive($_->item);
		
		$templateClass = yFactory::linkTemplate($template);
		return $this->getPage($templateClass, $_);
	}
	
	protected function quoteRecursive(&$data) {
		// Рекурсивное преобразование всех строк в html-безопасные в объекте/массиве
		if (is_array($data)||is_object($data)) {
			foreach ($data as $key => &$value)
				if (is_string($value))
				$value = htmlspecialchars($value, ENT_QUOTES);
			else
				$this->quoteRecursive($value);
		}
		elseif (is_string($data)) {
			$data = htmlspecialchars($data, ENT_QUOTES);
		}
	}
}

?>