<?php defined ('_YEXEC')  or  die();

class usersClass {
	public $moduleName = 'users';

	public function getModule() {
		$controller = yFactory::getController('default');

		$model = yFactory::getModel($this->moduleName);
		$_ = $model->getModel($controller);

		$view = yFactory::getView($controller, 'default');
		$view->templatePage = strtolower(_cGetSafeName($controller->get->showpage, 'default')); //!!!
		return $view->getPage('login');
	}
}

?>