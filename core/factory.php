<?php defined ('_YEXEC')  or  die();

class yFactory {
	public static
			$corePath = '/core/',
			$modulesPath = '/modules/';

	public function link($type, $name = NULL) {
	/* include_once файла и возврат имени класса
	 * путь до файла и имени генерируется из $type ('model', 'view', etc) и $name вида '/module/name'
	*/	
		// если имя не передано, то подключается класс из ядра
		if (!isset($name)) {
			include_once ySettings::$path.self::$corePath.$type.'.php';
			return 'y'.ucfirst($type).'Class';
		}
		// если имя передано, то подключается класс из соответствующего файла
		else {
			//если bean, то тип в пути и назввании опускается
			$type = ($type == 'bean') ? '' : $type;
			$Type = ucfirst($type); // первая буква заглавная
			
			// имя может быть готовым массивом, либо задано через '/'
			$name = (is_array($name)) ? $name : explode('/', $name);

			// Определение имени подключаемого модуля и класса
			if ($name[0]) {			// Указан относительный путь
				return;				// На данный момент не поддерживается
			}
			elseif ($name[1]) {		// Указан абсолютный путь
				$moduleName = $name[1];
				$className = ($name[2]) ? $name[2] : $name[1];
			}
			else return;			// Неправильно задано имя
			
			include_once (ySettings::$path.self::$modulesPath.$moduleName.'/'.$className.$Type.'.php');
			return
				yFactory::getClassPrefix($moduleName, $className).$Type.'Class';
		}	
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
	
	public function getController($moduleName = NULL, $controllerName = NULL) {
		$controllerClassName = yFactory::linkController($moduleName, $controllerName);
		return new $controllerClassName($controllerName);
	}
	
	public function getModel($moduleName = NULL, $modelName = NULL) {
		$db = new yDbClass(ySettings::$db->prefix, ySettings::$db->com_prefix, true, true);

		$modelClassName = yFactory::linkModel($moduleName, $modelName);
		return new $modelClassName($db);
	}
	
	public function getView($moduleName = NULL, $viewName = NULL) {
		$viewClassName = yFactory::linkView($moduleName, $viewName);
		return new $viewClassName();	
	}
	
	public function getTemplate($moduleName = NULL, $templateName = NULL) {
			$templateClassName = yFactory::linkTemplate($moduleName, $templateName);
			return new $templateClassName();
	}

	public function getBean($moduleName = NULL, $beanName = NULL) {
		$beanClassName = yFactory::linkBean($moduleName, $beanName);
		return new $beanClassName();
	}

	public function getDb(/*$type = 'MySQL'*/) { //TODO:getDb
		return
			//new yDbClass(ySettings::$db->prefix, ySettings::$db->com_prefix, true, true);
			new yDbClass(ySettings::$db->user, ySettings::$db->password, ySettings::$db->name, ySettings::$db->host);
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