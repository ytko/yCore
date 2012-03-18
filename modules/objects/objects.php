<?php defined ('_YEXEC')  or  die();

class objectsClass {
	public $moduleName = 'objects';
	
	public function getModule() {
		$controller = yFactory::getController('default');

		$model = yFactory::getModel($this->moduleName, $controller->modelName);
		$_ = $model->getModel($controller);

		$view = yFactory::getView($controller);
		$template = yFactory::getTemplate($this->moduleName);		
		return $view->getView($_, $template);
	}
}
?>