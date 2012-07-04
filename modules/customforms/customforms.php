<?php defined ('_YEXEC')  or  die();

yFactory::linkBean();

class customformsClass extends yBeanClass {
	public function edit() {
		$object = yFactory::getObject('customforms');
		
		$controller = yFactory::getController('customforms');
		$object = $controller->getObject($object);
		
		$model = yFactory::getModel('customforms');
		$model->insertObject($object);
		
		$object->value('id', 2);
		print_r($model->getObject($object));
		
		$template = yFactory::getTemplate('customforms')
				->setProperty('model', $model);

		//$db->create($object);
		//$db->insert($object);
		
		return $template->form($object);
	}
	
	//TODO: public function add() (alias to edit(), but can be overrided)
	//TODO: public function show()
	//TODO: public function list()
	//TODO: public function admin()
}

?>