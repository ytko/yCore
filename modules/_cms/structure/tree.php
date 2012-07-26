<?php defined ('_YEXEC')  or  die();

yCore::load('yClass');

class structureTreeClass extends yClass {
	public $moduleName = 'structure';

	public function show() {
		$object = yCore::catalogCategoryObject()->get();

		$model = yCore::structureTreeModel;
		$model->object = $object;
		$tree = $model->get(0);

		$template = yCore::structureTreeTemplate();
		return ($template->get($tree));	
	}
}

?>