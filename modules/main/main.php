<?php defined ('_YEXEC')  or  die();

class mainClass {
	public $moduleName = 'main';

	public function getModule($_) {
		$controller = yFactory::getController('default');

		//$model = yFactory::getModel($this->moduleName);
		//$_ = $model->getModel($controller);

		$view = yFactory::getView($controller, $this->moduleName);
		return $view->getView($_, $this->moduleName);
	}
}

?>