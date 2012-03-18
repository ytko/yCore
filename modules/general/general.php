<?php defined ('_YEXEC')  or  die();

class generalClass {
	public $moduleName, $glueName;
	
	public function getModule () {
		$controller = yFactory::getController();
				
		$model = yFactory::getModel($this->moduleName, $this->glueName);
		$_ = $model->getModel($controller);

		$view = yFactory::getView();
		$template = yFactory::getTemplate($this->moduleName, $this->glueName);		
		return $view->getView($_, $template);
	}
}

?>