<?php defined ('_YEXEC')  or  die();

class yFactory {	
	public function getDb($type = 'MySQL') {
		if (strcasecmp($type, 'MySQL')) {
	
		}
	}
	
	public function getModule($moduleName) {
		$moduleRequest = explode('/', $moduleName);
		
		$moduleName = $moduleRequest[0];
		$beanName = $moduleRequest[sizeOf($moduleRequest)-1];
		
		$moduleName = yFactory::safeName($moduleName, 'default', 'error');
		$beanName = yFactory::safeName($beanName, 'default', 'error');
		
		include_once ySettings::$path.'/modules/'.$moduleName.'/'.$beanName.'.php';
		$moduleClassName = yFactory::getClassPrefix($moduleName, $beanName).'Class';

		$moduleClass = new $moduleClassName();
		$moduleClass->moduleName = $moduleName;
		$moduleClass->beanName = $beanName;
		
		return $moduleClass;
	}
	
	public function linkController($moduleName = NULL, $controllerName = NULL) {
		if (!isset($moduleName)) {
			include_once ySettings::$path.'/core/controller.php';
			return 'yControllerClass';
		}
		else {
			$moduleName = yFactory::safeName($moduleName, 'default', 'error');
			$controllerName = yFactory::safeName($controllerName, $moduleName, 'error');
			include_once ySettings::$path.'/modules/'.$moduleName.'/'.$controllerName.'Controller.php';
			return yFactory::getClassPrefix($moduleName, $controllerName).'ControllerClass';
		}
	}
		
	public function getController($moduleName = NULL, $controllerName = NULL) {
		$controllerClassName = yFactory::linkController($moduleName, $controllerName);
		return new $controllerClassName($controllerName);
	}
		
	public function linkModel($moduleName = NULL, $modelName = NULL) {
		if (!isset($moduleName)) {
			include_once ySettings::$path.'/core/model.php';
			return 'yModelClass';
		}
		else {
			$moduleName = yFactory::safeName($moduleName, 'default', 'error');
			$modelName = yFactory::safeName($modelName, $moduleName, 'error');

			include_once ySettings::$path.'/modules/'.$moduleName.'/'.$modelName.'Model.php';
			return yFactory::getClassPrefix($moduleName, $modelName).'ModelClass';
		}
	}	
	
	public function getModel($moduleName = NULL, $modelName = NULL) {
		$db = new yDbClass(ySettings::$db->prefix, ySettings::$db->com_prefix, true, true);

		$modelClassName = yFactory::linkModel($moduleName, $modelName);
		return new $modelClassName($db);
	}
	
	public function linkView($moduleName = NULL, $viewName = NULL) {
		if (!isset($moduleName)) {
			include_once ySettings::$path.'/core/view.php';
			return 'yViewClass';
		}
		else {
			$moduleName = yFactory::safeName($moduleName, 'default', 'error');
			$viewName = yFactory::safeName($viewName, $moduleName, 'error');
			include_once ySettings::$path.'/modules/'.$moduleName.'/'.$viewName.'View.php';		
			return $viewClassName = $viewName.'ViewClass';
		}
	}
	
	public function getView($moduleName = NULL, $viewName = NULL) {
		$viewClassName = yFactory::linkView($moduleName, $viewName);
		return new $viewClassName();	
	}
	
	public function linkTemplate($moduleName = NULL, $templateName = NULL) {
		if (!isset($moduleName)) {
			include_once ySettings::$path.'/core/template.php';
			return 'yTemplateClass';
		}
		else {
			$moduleName = yFactory::safeName($moduleName, 'default', 'error');
			$templateName = yFactory::safeName($templateName, $moduleName, 'error');
			include_once ySettings::$path.'/modules/'.$moduleName.'/'.$templateName.'Template.php';
		
			return $templateName = yFactory::getClassPrefix($moduleName, $templateName).'TemplateClass';
		}
	}
	
	public function getTemplate($moduleName = NULL, $templateName = NULL) {
			$templateClassName = yFactory::linkTemplate($moduleName, $templateName);
			return new $templateClassName();
	}

	public function linkBean($moduleName = NULL, $beanName = NULL) {
		if (!isset($moduleName)) {
			include_once ySettings::$path.'/core/bean.php';
			return 'yBeanClass';
		}
		else {
			$moduleName = yFactory::safeName($moduleName, 'default', 'error');
			$beanName = yFactory::safeName($beanName, $moduleName, 'error');
			include_once ySettings::$path.'/modules/'.$moduleName.'/'.$beanName.'.php';
	
			return $beanName = yFactory::getClassPrefix($moduleName, $beanName).'BeanClass';
		}
	}	
	
	public function getBean($moduleName = NULL, $beanName = NULL) {
		$beanClassName = yFactory::linkBean($moduleName, $beanName);
		return new $beanClassName();
	}
	
	function getClassPrefix($moduleName, $name) { //генерирует префикс названия класса из названия модуля и названия файла класса
		$moduleName = strtolower($moduleName);
		$name = strtolower($name);
	
		if (!strcmp($moduleName, $name))
			return $moduleName;
		else {
			$name[0] = strtoupper($name[0]);
			return $moduleName.$name;
		}
	}
	
	// Функции проверки безопасности имен и путей
	
	function safeName($name, $defaultName = NULL, $errorName = NULL) {
		return strtolower($name ? (
					yFactory::isFileNameSafe($name)
						? $name
						: $errorName )
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