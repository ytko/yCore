<?php defined ('_YEXEC')  or  die();

class yFactory {
	public function link($type, $name = NULL) {
	/* include_once файла и возврат имени класса
	 * путь до файла и имени генерируется из $type ('model', 'view', etc) и $name вида '/module/name'
	*/	
		
		// если имя не передано, то подключается класс из ядра
		if (!isset($name)) {
			include_once ySettings::$corePath.'/'.$type.'.php';
			$result = 'y'.ucfirst($type).'Class';
		}
		// если имя передано, то подключается класс из соответствующего файла
		else {
			//если bean, то тип в пути и назввании опускается
			$type = ($type == 'bean') ? '' : $type;
			$Type = ucfirst($type); // первая буква заглавная
			
			// имя может быть готовым массивом, либо задано через '/'
			$name = (is_array($name)) ? $name : explode('/', $name);

			// Определение имени подключаемого модуля и класса
			if ($name[0]) {		// Указан абсолютный путь
				$moduleName = $name[0];
				$className = (isset($name[1])) ? $name[1] : $name[0];
			}
			else return;			// Неправильно задано имя
			
			include_once (ySettings::$modulesPath.'/'.$moduleName.'/'.$className.$Type.'.php');
			$result = yFactory::getClassPrefix($moduleName, $className).$Type.'Class';
		}
		
		return $result;
	}

	public function linkController($name = NULL) {
		return
			yFactory::link('controller', $name);
	}

	public function linkModel($name = NULL) {
		return
			yFactory::link('model', $name);
	}		

	public function linkView($name = NULL) {
		return
			yFactory::link('view', $name);
	}

	public function linkTemplate($name = NULL) {
		return
			yFactory::link('template', $name);
	}

	public function linkBean($name = NULL) {
		return
			yFactory::link('bean', $name);		
	}
	
	public function linkDb($name = NULL) {
		return
			yFactory::link('sql', $name);	
	}
	
	public function linkObject($name = NULL) {
		return
			yFactory::link('object', $name);	
	}
	
	public function getController($name = NULL) {
		$controllerClassName = yFactory::linkController($name);
		return new $controllerClassName($controllerName);
	}
	
	public function getModel($name = NULL) {
		$modelClassName = yFactory::linkModel($name);
		return new $modelClassName();
	}
	
	public function getView($name = NULL) {
		$viewClassName = yFactory::linkView($name);
		return new $viewClassName();	
	}
	
	public function getTemplate($name = NULL) {
		$templateClassName = yFactory::linkTemplate($name);
		return new $templateClassName();
	}

	public function get($name = NULL) {
		$beanClassName = yFactory::linkBean($name);
		return new $beanClassName();
	}
	
	public function getBean($name = NULL) { //alias get
		$beanClassName = yFactory::linkBean($name);
		return new $beanClassName();
	}

	public function getDb($name = NULL) {
		$dbClassName = yFactory::linkDb($name);
		return
			new $dbClassName();
	}
	
	public function getObject($name = NULL) {
		$objectClassName = yFactory::linkObject($name);
		return
			new $objectClassName();
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

class yy extends yFactory {
	public function db($name = NULL) {
		return parent::getDb($name);
	}
}

?>