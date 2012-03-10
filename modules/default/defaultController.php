<?php defined ('_YEXEC')  or  die();

yFactory::linkController();

class defaultControllerClass extends yControllerClass {
	function __construct($controllerName) {
		parent::__construct($controllerName);
		
		$this->modelName =
			yFactory::safeName(
					$this->get->mod,
					$this->controllerName
		);
		
		$this->viewName =
			yFactory::safeName(
					$this->get->view,
					$this->modelName
			);
	}
}

?>