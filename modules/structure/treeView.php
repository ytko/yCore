<?php defined ('_YEXEC')  or  die();

yCore::load('objectView');

class structureTreeView extends objectView {
	public
		$tempalteName = 'structure',
		$pageName = 'recursive';
	public
		$key = 'keyword',
		$value = 'name';
	
	public function render($trunk = array(), $level = 1, $path = '') {
		$path = $path ? $path.'/' : NULL;

		include(yCore::template($this->tempalteName, $this->pageName));

		return $result;
	}
}

?>