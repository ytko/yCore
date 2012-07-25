<?php defined ('_YEXEC')  or  die();

yCore::includeBean();

class catalogClass extends yBean {
	public function cat($categoryID = NULL) {
		$object = yCore::object('catalog')
				->cat();
		
		if($categoryID)
			$object->filter('category',
				array(
					'type' => 'field',
					'field' => 'category',
					'show' => false,
					'value' => $categoryID)
				);

		$controller = yCore::controller('object')
				->get($object);

		$model = yCore::model('object')
				->getCat($object);
	
		$template = yCore::template('catalog')
				->setObject($object);

		return $template->cat();
	}
	
	public function page() {
		$object = yCore::getObject('catalog')
				->get();

		$controller = yCore::getController('object')
				->get($object);

		$model = yCore::getModel('object')
				->getCat($object);

		$template = yCore::getTemplate('catalog')
				->setObject($object);
		
		return $template->page();		
	}

	public function catEdit() {
		$object = yCore::object('catalog')
				->cat();

		$controller = yCore::controller('object')
				->get($object);
		
		$model = yCore::model('object')
				->getCat($object);

		$template = yCore::template('catalog')
				->setObject($object)
				->setMode('admin');

		return $template->cat();
	}
	
	public function pageEdit() {
		$object = yCore::getObject('catalog')
				->get();

		$controller = yCore::getController('object')
				->get($object);

		$model = yCore::getModel('object')
				->replace($object)
				->get($object);

		$template = yCore::getTemplate('catalog')
				->setObject($object)
				->setMode('admin');
		
		return $template->form($object);
	}
	
	public function pageAdd() {
		$object = yCore::getObject('catalog')
				->get();

		$controller = yCore::getController('object')
				->get($object);

		$model = yCore::getModel('object')
				->insert($object);

		$template = yCore::getTemplate('catalog')
				->setObject($object)
				->setMode('admin');
		
		return $template->form($object);
	}
	
	public function install() {
		yCore::getModel('object')->install(
						yCore::getObject('catalog')->get()
				);
		
		yCore::getModel('object')->install(
						yCore::getObject('catalog/category')->get()
				);
	}
	
	public function export() {
		$object = yCore::getObject('catalog')->get();
		$model = yCore::getModel('catalog')->export($object);		
	}
}

?>