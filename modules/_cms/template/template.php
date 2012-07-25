<?php defined ('_YEXEC')  or  die();

yCore::includeBean();

class templateClass extends yBean {
	public $moduleName = 'template';
//	public $beanName = 'default';

	public function get($content) {
		$template = yCore::template($this->moduleName)
				->setContent($content);		//Получение контента

		return $template->get();
	}
}

?>