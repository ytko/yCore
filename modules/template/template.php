<?php defined ('_YEXEC')  or  die();

yCore::load('yClass');

class templateClass extends yClass {
	public $moduleName = 'template', $view;
//	public $beanName = 'default';

	public function __construct() {
		parent::__construct();
		
		//$this->bind(yCore::create($this->moduleName.'View'));
	}
}

?>