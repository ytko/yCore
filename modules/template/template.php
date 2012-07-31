<?php defined ('_YEXEC')  or  die();

yCore::load('yClass');

class templateClass extends yClass {
	public $moduleName = 'template';
//	public $beanName = 'default';

	public function show($content) {
		$template = yCore::create($this->moduleName.'Template')
				->setContent($content);		//Получение контента

		return $template->get();
	}
}

?>