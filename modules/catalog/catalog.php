<?php defined ('_YEXEC')  or  die();

yFactory::includeBean();

class catalogClass extends yBeanClass {
	public function cat() {
		$object = yFactory::object('catalog')
				->cat();

		$controller = yFactory::controller('object')
				->getObject($object);
	
		$model = yFactory::model('object')
				->getCat($object);
	
		$template = yFactory::template('catalog')
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

	public function catEdit() {
		$object = yFactory::object('catalog')
				->cat();

		$controller = yFactory::controller('object')
				->getObject($object);
		
		$model = yFactory::model('object')
				->getCat($object);

		$template = yFactory::template('catalog')
				->setObject($object);

		return $template->cat();
	}
	
	public function pageEdit() {
		$object = yFactory::getObject('catalog')
				->full();

		$controller = yFactory::getController('object')
				->getObject($object);

		$model = yFactory::getModel('object')
				//->install($object)
				->replace($object)
				->get($object);

		$template = yFactory::getTemplate('catalog')
				->setObject($object);
		
		return $template->form($object);
	}
	
	public function pageAdd() {
		$object = yFactory::getObject('catalog')
				->full();

		$controller = yFactory::getController('object')
				->getObject($object);

		$model = yFactory::getModel('object')
				//->install($object)
				->replace($object)
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