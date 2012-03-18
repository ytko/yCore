<?php defined ('_YEXEC')  or  die();

class defaultClass {
	public $moduleName = 'default';
	
	public function getModule () {
		$controller = yFactory::getController($this->moduleName);

		$model = yFactory::getModel($this->moduleName, $controller->modelName);
		$_ = $model->getModel($controller);

		$view = yFactory::getView($controller);
		$template = yFactory::getTemplate($this->moduleName);
		return $view->getView($_, $template);
	}
}

?>