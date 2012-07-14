<?php defined ('_YEXEC')  or  die();

class yFactory {
	protected static function includeComponent($type, $name = NULL) {
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
			if ($name[0]) {
				$moduleName = $name[0];
				$className = (isset($name[1])) ? $name[1] : $name[0];
			}
			else return;			// Неправильно задано имя

			if(isset(ySettings::$altPaths->$moduleName))
				$modulesPath = ySettings::$path.'/'.ySettings::$altPaths->$moduleName;
			else
				$modulesPath = ySettings::$modulesPath;
			
			include_once ($modulesPath.'/'.$moduleName.'/'.$className.$Type.'.php');
			$result = yFactory::getClassPrefix($moduleName, $className).$Type.'Class';
		}
		
		return $result;
	}

	public static function includeBean($name = NULL) {
		return
			yFactory::includeComponent('bean', $name);		
	}
	
	public static function includeController($name = NULL) {
		return
			yFactory::includeComponent('controller', $name);
	}

	public static function includeModel($name = NULL) {
		return
			yFactory::includeComponent('model', $name);
	}		

	public static function includeTemplate($name = NULL) {
		return
			yFactory::includeComponent('template', $name);
	}

	public static function includeDb($name = NULL) {
		return
			yFactory::includeComponent('db', $name);	
	}
	
	public static function includeObject($name = NULL) {
		return
			yFactory::includeComponent('object', $name);	
	}

	public static function getBean($name = NULL) { //alias get
		$beanClassName = yFactory::includeBean($name);
		return new $beanClassName();
	}
	
	public static function getController($name = NULL) {
		$controllerClassName = yFactory::includeController($name);
		return new $controllerClassName($controllerName);
	}
	
	public static function getModel($name = NULL) {
		$modelClassName = yFactory::includeModel($name);
		return new $modelClassName();
	}
		
	public static function getTemplate($name = NULL) {
		$templateClassName = yFactory::includeTemplate($name);
		return new $templateClassName();
	}

	public static function getDb($name = NULL) {
		$dbClassName = yFactory::includeDb($name);
		return
			new $dbClassName();
	}
	
	public static function getObject($name = NULL) {
		$objectClassName = yFactory::includeObject($name);
		return
			new $objectClassName();
	}
	
	// Aliases
	
	public static function get($name = NULL) {
		return yFactory::getBean($name);
	}
	
	public static function bean($name = NULL) {
		return yFactory::getBean($name);
	}
	
	public static function controller($name = NULL) {
		return yFactory::getController($name);
	}
	
	public static function model($name = NULL) {
		return yFactory::getModel($name);
	}
	
	public static function template($name = NULL) {
		return yFactory::getTemplate($name);
	}
	
	public static function db($name = NULL) {
		return yFactory::getDb($name);
	}
	
	public static function object($name = NULL) {
		return yFactory::getObject($name);
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
	public static function db($name = NULL) {
		return parent::getDb($name);
	}
}

?>