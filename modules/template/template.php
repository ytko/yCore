<?php defined ('_YEXEC')  or  die();

yFactory::linkBean();

class templateClass extends yBeanClass {
	public $moduleName = 'template';
//	public $beanName = 'default';
	
	protected $content;
	public function setContent($bean) {
		$this->content = $bean;
		return $this;
	}

	public function get($bean = NULL) {
		if ($bean) $this->setContent($bean);
		
		$template = yFactory::getTemplate('/'.$this->moduleName)
				->setContent($this->content());		//Получение контента

		return yFactory::getView()
				->setTemplate($template)
				->get();
	}
}

?>