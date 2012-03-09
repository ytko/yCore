<?php defined ('_YEXEC')  or  die();

yFactory::linkController();

class defaultControllerClass extends yControllerClass {
	function __construct($controllerName) {
		parent::__construct($controllerName);
		
		$this->modelName =
			strtolower(_cGetSafeName(
					$this->get->mod,
					$this->controllerName
		));
		
		$this->viewName =
			strtolower(_cGetSafeName(
					$this->get->view,
					$this->modelName
			));
	}
}

?>