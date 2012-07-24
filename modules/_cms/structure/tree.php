<?php defined ('_YEXEC')  or  die();

yCore::includeController('object');

class structureTreeClass extends yControllerClass {
	public $moduleName = 'structure';

	public function show() {
		$object = yCore::object('catalog/category')->get();

		$model = yCore::model('structure/tree');
		$model->object = $object;
		$tree = $model->get(0);

		$template = yCore::template('structure/tree');
		return ($template->get($tree));	
	}
}

?>