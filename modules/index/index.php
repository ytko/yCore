<?php defined ('_YEXEC')  or  die();

yFactory::linkGlue();

class indexClass extends yGlueClass {
	public $moduleName = 'index';

	public function getModule() {
		$controller = yFactory::getController();

		$template = yFactory::getTemplate($this->moduleName);		
		$module = yFactory::getModule($_GET[ySettings::$mvc->module]);	//Создание экземпляра модуля //TODO:безопасный запрос (как в factory)
		$template->indexModule = $module->getModule();					//Получение контента основного модуля

		$view = yFactory::getView();
		return $view->getPage($template);								//не $view->getView()!!!
	}
}

?>