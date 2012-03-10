<?php defined ('_YEXEC')  or  die();

class yViewClass {
	public
		$body,
		$style,
		$script,
		$title,
		$breadcrumbs;
	
	public $view, $defaultview = 'default';

	function getStyle() {
		return $this->style;
	}

	function getScript() {
		return $this->script;
	}

	function __construct() {
		$view->title = '';
		$view->breadcrumbs = array();
		$view->script = '';
		$view->style = '';
		$view->body = '';
	}
	
	function getPage($template, &$_ = NULL) {
		$this->view = ($view) ? $view : $this->defaultview;

		include_once ySettings::$path.DS.'modules/default/templates/defaultTemplate.php';
		
		ob_start();
		defaultTemplate::singleBody($_);
		$this->body.= ob_get_contents();
		ob_end_clean();
		
		ob_start();
		defaultTemplate::loopBody($_);
		$this->body.= ob_get_contents();
		ob_end_clean();

		ob_start();
		defaultTemplate::script($_);
		$this->script.= ob_get_contents();
		ob_end_clean();		
		
		ob_start();
		defaultTemplate::style($_);
		$this->style.= ob_get_contents();
		ob_end_clean();
		
		return $this;
	}
}

?>