<?php defined ('_YEXEC')  or  die();

yFactory::linkBean();

class generalClass extends yBeanClass {
	public
		$modelName = 'general',
		$templateName = 'general';
	
	/*public function get() {
		$controller = $this->doFactory($this->controllerName, 'getController');

		$model = $this->doFactory($this->modelName, 'getModel');
		$model->setController($controller);
		$_ = $model->getModel($controller);

		$view = $this->doFactory($this->viewName, 'getView');
		$template = $this->doFactory($this->templateName, 'getTemplate');

		return $view->getView($_, $template);
	}*/
}

?>