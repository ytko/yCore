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
		$object = yCore::object('catalog')
				->get();

		$controller = yCore::controller('object')
				->get($object);

		$model = yCore::model('object')
				->getCat($object);

		$template = yCore::template('catalog')
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
		$object = yCore::object('catalog')
				->get();

		$controller = yCore::controller('object')
				->get($object);

		$model = yCore::model('object')
				->replace($object)
				->get($object);

		$template = yCore::template('catalog')
				->setObject($object)
				->setMode('admin');
		
		return $template->form($object);
	}
	
	public function pageAdd() {
		$object = yCore::object('catalog')
				->get();

		$controller = yCore::controller('object')
				->get($object);

		$model = yCore::model('object')
				->insert($object);

		$template = yCore::template('catalog')
				->setObject($object)
				->setMode('admin');
		
		return $template->form($object);
	}
	
	public function install() {
		yCore::model('object')->install(
						yCore::object('catalog')->get()
				);
		
		yCore::model('object')->install(
						yCore::object('catalog/category')->get()
				);
	}
	
	public function export() {
		$object = yCore::object('catalog')->get();
		$model = yCore::model('catalog')->export($object);		
	}
}

?>