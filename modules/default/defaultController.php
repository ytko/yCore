<?php defined ('_YEXEC')  or  die();

yFactory::linkController();

class defaultControllerClass extends yControllerClass {
	function __construct($controllerName) {
		parent::__construct($controllerName);
	}
}

?>