<?php

class yBeanClass {
	public
	// задаются при вызове из yFactory	
		$moduleName,
		$beanName,
		
	// задаются при необходимости потомком данного класса
	// например, $modelName = 'edit'
	// если параметр остаеся NULL, то создается экземпляр класса из '/core/'
		$controllerName = NULL,
		$modelName = NULL,
		$viewName = NULL,
		$templateName = NULL;

	// При попытке вызвать свойство как функцию вызывает __invoke объекта, присвоенного свойству
	function __call($name, $params) {
		if(is_callable($this->$name)) {
			return call_user_func_array($this->$name, $params);
		}
	}	

	public function __invoke() {
		return $this->get();
	}
	
	// Возвращает данные реализуя MVC по указанным в свойствах именам
	/*public function get() {
		$controller = $this->doFactory($this->controllerName, 'getController');

		$model = $this->doFactory($this->modelName, 'getModel')
				->setController($controller);
		//print_r($this->modelName);

		$view = $this->doFactory($this->viewName, 'getView');
		$template = $this->doFactory($this->templateName, 'getTemplate')
				->setModel($model);

		return $view
				->setTemplate($template)
				->get();
	}*/
	
	public function get () {
		$controller = yFactory::getController($this->controller);

		$model = yFactory::getModel($this->modelName)
				->setController($controller);

		$view = yFactory::getView($this->viewName);
		$template = yFactory::getTemplate($this->templateName)
				->setModel($model);

		return $view
				->setTemplate($template)
				->get();
	}
	
	
	// Задание свойств $controllerName, $modelName, $viewName, $templateName
	public function setModuleName($moduleName) {
		$this->moduleName = $moduleName;
		return $this;
	}
		
	public function setControllerName($controllerName) {
		$this->controllerName = $controllerName;
		return $this;
	}
	
	public function setModelName($modelName) {
		$this->modelName = $modelName;
		return $this;
	}
	
	public function setViewName($viewName) {
		$this->viewName = $viewName;
		return $this;
	}
	
	public function setTemplateName($templateName) {
		$this->templateName = $templateName;
		return $this;
	}

	// Подстановка параметров вызова yFactory
	protected function doFactory($name, $func) {
		yFactory::$func($name);
		
		
		/*if (isset($name)) {
			if ($name === false)
				return NULL;
			elseif ($name === '')
				return yFactory::$func($this->moduleName, $this->moduleName);
			else
				return yFactory::$func($this->moduleName, $name);
		}
		else
			return yFactory::$func();		*/
	}
}