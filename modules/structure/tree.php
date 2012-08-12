<?php defined ('_YEXEC')  or  die();

yCore::load('yClass');

class structureTreeClass extends yClass {
	public $moduleName = 'structure';

	public function show() {
		$object = yCore::catalogCategoryObject();

		$model = yCore::structureTreeModel();
		$tree = $model->get($object);

		$view = yCore::structureTreeView();
		return ($view->get($tree));	
	}
}

?>