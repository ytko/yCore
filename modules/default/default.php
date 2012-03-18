<?php defined ('_YEXEC')  or  die();

class defaultClass {
	public $moduleName, $glueName;
	
	public function getModule () {
		$controller = yFactory::getController($this->moduleName);

		$model = yFactory::getModel($this->moduleName, $this->glueName);
		$_ = $model->getModel($controller);

		$view = yFactory::getView();
		$template = yFactory::getTemplate($this->moduleName, $this->glueName);
		return $view->getView($_, $template);
	}
}

?>