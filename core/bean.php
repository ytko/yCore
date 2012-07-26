<?php

@require_once 'base.php';

class yBean extends yBase {
	public
	// задаются при вызове из yCore	
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
	/*function __call($name, $args) {
		parent::__call($name, $args);

		if(is_callable($this->$name)) {
			return call_user_func_array($this->$name, $args);
		}
	}*/

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
		$controller = yCore::controller($this->controller);

		$model = yCore::model($this->modelName)
				->setController($controller);

		$view = yCore::view($this->viewName);
		$template = yCore::template($this->templateName)
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

	// Подстановка параметров вызова yCore
	protected function doFactory($name, $func) {
		yCore::$func($name);
		
		
		/*if (isset($name)) {
			if ($name === false)
				return NULL;
			elseif ($name === '')
				return yCore::$func($this->moduleName, $this->moduleName);
			else
				return yCore::$func($this->moduleName, $name);
		}
		else
			return yCore::$func();		*/
	}
}

/*
 * Copyright 2012 Roman Exempliarov. 
 *
 * This file is part of yCore framework.
 *
 * yCore is free software: you can redistribute it and/or modify it under the
 * terms of the GNU Lesser General Public License as published by the Free
 * Software Foundation, either version 2.1 of the License, or (at your option)
 * any later version.
 * 
 * yCore is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU Lesser General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with yCore. If not, see http://www.gnu.org/licenses/.
 */

?>