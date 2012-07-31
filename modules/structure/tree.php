<?php defined ('_YEXEC')  or  die();

yCore::load('yClass');

class structureTreeClass extends yClass {
	public $moduleName = 'structure';

	public function show() {
		$object = yCore::catalogCategoryObject();

		$model = yCore::structureTreeModel();
		$tree = $model->get($object);

		$template = yCore::structureTreeTemplate();
		return ($template->get($tree));	
	}
}

?>