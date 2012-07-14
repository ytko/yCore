<?php defined ('_YEXEC')  or  die();

yFactory::includeObject();

class catalogObjectClass extends yObjectClass {
	public function full() {
		$this
			->table('catalog')
			//->name('Каталог')
			->field('id', 'id')
			->field('extid', 'int', 'Внешний ID')
			->field('distributor', 'string')
			->field('category', 'string')
			->field('vendor', 'string')
			->field('name', 'string')
			->field('price', 'currency')
			->field('description', 'text')
			->filter('id',
					array(
						'type' => 'field',
						'field' => 'id',
						'scope' => 'external')
					);
		return $this;
	}
	
	public function cat() {
		$this
			->table('catalog')
			//->name('Каталог')
			->field('id', 'id')
			->field('category', 'string', 'Категория')
			->field('name', 'string', 'Название')
			->field('price', 'currency')
			->field('description', 'text')
			->filter('id',
					array(
						'type' => 'field',
						'field' => 'id',
						'scope' => 'external')
					)
			->filter('name',
					array(
						'type' => 'field',
						'field' => 'name',
						'collation' => 'like',
						'scope' => 'external')
					)
			/*->filter('category',
					array(
						'type' => 'field',
						'field' => 'category',
						'collation' => 'like',
						'scope' => 'external')
					)*/
			->filter('page',
					array(
						'type' => 'page',
						'scope' => 'external',
						'rows' => '5')
					);
		return $this;
	}
}

?>