<?php defined ('_YEXEC')  or  die();

yFactory::linkBean();

class templateClass extends yBeanClass {
	public $moduleName = 'template';
	
	public $beanName = 'default';
	
	public $content;
	
	public function setBean($bean) {
		$this->content = $bean;
		return $this;
	}

	public function get($beanName = NULL) {
		if ($beanName) $this->setBean($beanName);
		
		$template = yFactory::getTemplate($this->moduleName);
		
		//Получение контента
		$template->indexModule = $this->content();

		return yFactory::getView()->getPage($template);								//не $view->getView()!!!
	}
}

?>