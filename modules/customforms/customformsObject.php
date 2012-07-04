<?php defined ('_YEXEC')  or  die();

yFactory::linkObject();

class customformsObjectClass extends yObjectClass {
	public function __construct() {
		$this
			->table('mod_customforms')
			//->name('customforms')
			->field('id', 'id')
			->field('name', 'text')
			->field('patronymic', 'string')
			->field('surename', 'string')
			->field('position', 'string')
			->field('cid', 'int')
			->field('uid', 'int');
	}
}

?>