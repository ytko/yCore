<?php defined ('_YEXEC')  or  die();

class yViewClass {
	private $style = '', $script = '';
	public $view, $defaultview = 'default';

	function getStyle() {
		return $this->style;
	}

	function getScript() {
		return $this->script;
	}

	function inc($filename, &$_) {
		//$result = '';
		$style = '';
		$script = '';
		
		$view = &$this;
		$result = &$view->body; // убрать позже
		
		$errorReporting = error_reporting();
		error_reporting(E_ERROR | E_PARSE);
		if (!include(ySettings::$path.DS.'view'.DS.$this->view.DS.$filename)) {
			error_reporting($errorReporting);
			include(ySettings::$path.DS.'view'.DS.$this->defaultview.DS.$filename);
		}
		else error_reporting($errorReporting);
		
		//$this->html.= $result."\n";
		$this->style.= $style."\n";
		$this->script.= $script."\n";
		
		return $result;
		
		/*
		return (object)array(
			'result' => $result,
			'style' => $style,
			'script' => $script			
		);*/
	}
	
	function __construct($view = false) {
		$this->view = ($view) ? $view : $this->defaultview;
		
		$view->title = '';
		$view->breadcrumbs = array();
		$view->script = '';
		$view->style = '';
		$view->body = '';
	}
	
	function getPage($filename, &$_ = NULL) {
		/*
		if (isset($_->items)) _cQuoteRecursive($_->items);
		if (isset($_->item))  _cQuoteRecursive($_->item);
		*/
		$result = $this->inc($filename.'.php', $_);
		
		return $result;
	}
}

?>