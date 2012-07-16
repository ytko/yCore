<?php defined ('_YEXEC')  or  die();

yFactory::includeObject();

class catalogCategoryObjectClass extends yObjectClass {
	public function get() {
		$this
			->table('catalog_category')
			//->name('Каталог')
			->field('id', 'id')
			->field('pid', 'int')
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