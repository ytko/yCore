<?php defined ('_YEXEC')  or  die();

yFactory::linkBean();

class catalogClass extends yBeanClass {
	public function cat() {
		$object = yFactory::getObject('catalog')
				->cat();

		$controller = yFactory::getController('object')
				->getObject($object);
		
		$model = yFactory::getModel('object')
				->getCat($object);

		$template = yFactory::getTemplate('catalog')
				->setObject($object);

		return $template->cat();		
	}
	
	public function page() {
		$object = yFactory::getObject('catalog')
				->full();

		$controller = yFactory::getController('object')
				->getObject($object);

		$model = yFactory::getModel('object')
				->getCat($object);

		$template = yFactory::getTemplate('catalog')
				->setObject($object);
		
		return $template->page();		
	}

	public function edit() {
		$object = yFactory::getObject('catalog')
				->full();

		$controller = yFactory::getController('object')
				->getObject($object);

		$model = yFactory::getModel('object')
				//->install($object)
				->insert($object)
				->get($object);

		$template = yFactory::getTemplate('catalog')
				->setObject($object);
		
		return $template->form($object);
	}
	
	public function install() {
		$object = yFactory::getObject('catalog')
				->full();

		$model = yFactory::getModel('object')
				->install($object);
	}
	
	public function export() {
		$object = yFactory::getObject('catalog')->full();
		$model = yFactory::getModel('catalog')->export($object);		
	}
}

?>