<?php defined ('_YEXEC')  or  die();

yFactory::includeBean();

class customformsClass extends yBeanClass {
	public function cat() {
		$object = yFactory::getObject('customforms')
				->full();

		$controller = yFactory::getController('object');

		$model = yFactory::getModel('object')
				//->install($object)
				->getCat($object);

		$template = yFactory::getTemplate('object')
				->setObject($object);
				//->setModel($model);
		
		return $template->cat();		
	}
	
	public function page() {
		$object = yFactory::getObject('customforms')
				->full();

		$controller = yFactory::getController('object')
				->getObject($object);

		$model = yFactory::getModel('object')
				->getCat($object);

		$template = yFactory::getTemplate('object')
				->setObject($object);
				//->setModel($model);
		
		return $template->page();		
	}

	public function edit() {
		$object = yFactory::getObject('customforms')
				->full();

		$controller = yFactory::getController('object')
				->getObject($object);

		$model = yFactory::getModel('object')
				->insert($object)
				->get($object);

		$template = yFactory::getTemplate('object')
				->setObject($object);
		
		return $template->form($object);
	}	
}

?>