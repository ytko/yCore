<?php defined ('_YEXEC')  or  die();

class yFactory {	
	static $basePath = '';
	
	public function getDb($type = 'MySQL') {
		if (strcasecmp($type, 'MySQL')) {
	
		}
	}
	
	public function getModule($moduleName) {
		$moduleName = yFactory::safeName($moduleName, 'default', 'error');
		include yFactory::$basePath.DS.'modules'.DS.$moduleName.DS.$moduleName.'.php';
		$moduleClassName =  $moduleName.'Class';
		return new $moduleClassName();
	}
	
	public function linkController($moduleName = NULL, $controllerName = NULL) {
		if (!isset($moduleName)) {
			include_once yFactory::$basePath.DS.'core'.DS.'controller.php';
			return 'yControllerClass';
		}
		else {
			$moduleName = yFactory::safeName($moduleName, 'default', 'error');
			$controllerName = yFactory::safeName($controllerName, $moduleName, 'error');
			include_once yFactory::$basePath.DS.'modules'.DS.$moduleName.DS.$controllerName.'Controller.php';
			return $controllerName.'ControllerClass';
		}
	}
		
	public function getController($moduleName, $controllerName = NULL) {
		$controllerClassName = yFactory::linkController($moduleName, $controllerName);
		return new $controllerClassName($controllerName);
	}
		
	public function linkModel($moduleName = NULL, $modelName = NULL) {
		if (!isset($moduleName)) {
			include_once yFactory::$basePath.DS.'core'.DS.'model.php';
			return 'yModelClass';
		}
		else {
			$moduleName = yFactory::safeName($moduleName, 'default', 'error');
			$modelName = yFactory::safeName($modelName, $moduleName, 'error');

			include_once yFactory::$basePath.DS.'modules'.DS.$moduleName.DS.$modelName.'Model.php';
			return $modelName.'ModelClass';
		}
	}	
	
	public function getModel($moduleName, $modelName = NULL) {
		$_q = new yDbQueryClass(ySettings::$db->resource);
		$db = new yDbClass($_q, ySettings::$db->prefix, ySettings::$db->com_prefix, true, true);

		$modelClassName = yFactory::linkModel($moduleName, $modelName);
		return new $modelClassName($db);
	}
	
	public function linkView($moduleName = NULL, $viewName = NULL) {
		if (!isset($moduleName)) {
			include_once yFactory::$basePath.DS.'core'.DS.'view.php';
			return 'yViewClass';
		}
		else {
			$moduleName = yFactory::safeName($moduleName, 'default', 'error');
			$viewName = yFactory::safeName($viewName, $moduleName, 'error');
			include_once yFactory::$basePath.DS.'modules'.DS.$moduleName.DS.$viewName.'View.php';		
			return $viewClassName = $viewName.'ViewClass';
		}
	}
	
	public function getView($controller, $moduleName, $viewName = NULL) {
		$viewClassName = yFactory::linkView($moduleName, $viewName);
		return new $viewClassName($controller);	
	}

	// Функции проверки безопасности имен и путей
	
	private function safeName($name, $defaultName = NULL, $errorName = NULL) {
		return strtolower(isset($name) && !empty($name)
				? (
						yFactory::isFileNameSafe($name)
						? $name
						: $errorName
				)
				: $defaultName);
	}
	
	private function isFileNameSafe($fileName) { //не должно быть слэшей
		return !(!(strpos($fileName, '/') === false) ||
				!(strpos($fileName, '\\') === false));
	}
	
	private function isPathNameSafe($path) { //не должно быть возвратов
	
	}
}

?>