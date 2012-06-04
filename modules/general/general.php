<?php defined ('_YEXEC')  or  die();

yFactory::linkBean();

class generalClass extends yBeanClass {
	public
		$moduleName = 'general',
			
		$controller = '/extended',
		$modelName = '/general',
		$templateName = '/general';
	
/*	public function get () {
		$controller = yFactory::getController('/extended/extended');

		$model = yFactory::getModel($this->modelName)
				->setController($controller);

		$view = yFactory::getView();
		$template = yFactory::getTemplate($this->templateName)
				->setModel($model);

		return $view
				->setTemplate($template)
				->get();
	}*/
	
	/*
		public function get() {
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