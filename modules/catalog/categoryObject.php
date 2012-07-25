<?php defined ('_YEXEC')  or  die();

yCore::includeObject();

class catalogCategoryObject extends yObject {
	public function get() {
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
		return $this;
	}
}

?>