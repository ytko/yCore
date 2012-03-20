<?php

class yGlueClass {
	public
	// задаются при вызове из yFactory	
		$moduleName,
		$glueName,
		
	// задаются при необходимости потомком данного класса
	// например, $modelName = 'edit'
	// если параметр остаеся NULL, то создается экземпляр класса из '/core/'
		$controllerName = NULL,
		$modelName = NULL,
		$viewName = NULL,
		$templateName = NULL;


	public function getModule () {
		return $this->get();
	}
	
	protected function doFactory($name, $func) {
		if (isset($name)) {
			if ($name === false)
				return NULL;
			elseif ($name === '')
				return yFactory::$func($this->moduleName, $this->moduleName);
			else
				return yFactory::$func($this->moduleName, $name);
		}
		else
			return yFactory::$func();		
	}
	
	public function get() {
		$controller = $this->doFactory($this->controllerName, 'getController');

		$model = $this->doFactory($this->modelName, 'getModel');
		$_ = $model->getModel($controller);

		$view = $this->doFactory($this->viewName, 'getView');
		$template = $this->doFactory($this->templateName, 'getTemplate');

		return $view->getView($_, $template);
	}
}