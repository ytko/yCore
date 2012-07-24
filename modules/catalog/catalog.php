<?php defined ('_YEXEC')  or  die();

yFactory::includeBean();

class catalogClass extends yBeanClass {
	public function cat($categoryID = NULL) {
		$object = yFactory::object('catalog')
				->cat();
		
		if($categoryID)
			$object->filter('category',
				array(
					'type' => 'field',
					'field' => 'category',
					'show' => false,
					'value' => $categoryID)
				);

		$controller = yFactory::controller('object')
				->get($object);

		$model = yFactory::model('object')
				->getCat($object);
	
		$template = yFactory::template('catalog')
				->setObject($object);

		return $template->cat();
	}
	
	public function page() {
		$object = yFactory::getObject('catalog')
				->get();

		$controller = yFactory::getController('object')
				->get($object);

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
				->get($object);
		
		$model = yFactory::model('object')
				->getCat($object);

		$template = yFactory::template('catalog')
				->setObject($object)
				->setMode('admin');

		return $template->cat();
	}
	
	public function pageEdit() {
		$object = yFactory::getObject('catalog')
				->get();

		$controller = yFactory::getController('object')
				->get($object);

		$model = yFactory::getModel('object')
				->replace($object)
				->get($object);

		$template = yFactory::getTemplate('catalog')
				->setObject($object)
				->setMode('admin');
		
		return $template->form($object);
	}
	
	public function pageAdd() {
		$object = yFactory::getObject('catalog')
				->get();

		$controller = yFactory::getController('object')
				->get($object);

		$model = yFactory::getModel('object')
				->insert($object);

		$template = yFactory::getTemplate('catalog')
				->setObject($object)
				->setMode('admin');
		
		return $template->form($object);
	}
	
	public function install() {
		yFactory::getModel('object')->install(
						yFactory::getObject('catalog')->get()
				);
		
		yFactory::getModel('object')->install(
						yFactory::getObject('catalog/category')->get()
				);
	}
	
	public function export() {
		$object = yFactory::getObject('catalog')->get();
		$model = yFactory::getModel('catalog')->export($object);		
	}
}

?>