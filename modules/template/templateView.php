<?php defined ('_YEXEC')  or  die();

yCore::load('yView');

class templateView extends yView {
	protected $content, $menu;
		 
	function render($page = 'page') {		
		ob_start();
		include_once(yCore::template('template', $page));
		$result = ob_get_contents();
		ob_end_clean();
		return $result;
	}

// ------------------------------------------------------------------------------------------------
} ?>