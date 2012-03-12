<?php defined ('_YEXEC')  or  die();

class usersClass {
	public $moduleName = 'users';

	public function getModule() {
		$controller = yFactory::getController('default');

		$model = yFactory::getModel($this->moduleName);
		$_ = $model->getModel($controller);

		$view = yFactory::getView($controller, $this->moduleName, $controller->viewName);
		return $view->getView($_, $this->moduleName);
	}
}

?>