<?php defined ('_YEXEC')  or  die();

yFactory::includeBean();

class templateClass extends yBeanClass {
	public $moduleName = 'template';
//	public $beanName = 'default';

	public function get($content) {
		$template = yFactory::getTemplate($this->moduleName)
				->setContent($content);		//Получение контента

		return $template->get();
	}
}

?>