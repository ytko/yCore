<?php defined ('_YEXEC')  or  die();

yCore::load('yObject');

class catalogCategoryObject extends yObject {
	public function __construct() {
		parent::__construct();
		
		$this
			->table('catalog_category')
			//->name('Каталог')
			->field('id', 'id')
			->field('pid', 'int')
			->field('keyword', 'string')
			->field('name', 'string');
			/*->filter('id',
					array(
						'type' => 'field',
						'field' => 'id',
						'scope' => 'external')
					)
			->filter('pid',
					array(
						'type' => 'field',
						'field' => 'pid',
						'scope' => 'external')
					);*/
	}
}

?>