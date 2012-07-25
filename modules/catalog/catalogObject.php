<?php defined ('_YEXEC')  or  die();

yCore::includeObject();

class catalogObject extends yObject {
	public function get() {
		$categories = yCore::db('object')
				->select(yCore::object('catalog/category')->get());

		$categoriesList = array();
		foreach ($categories as $key => $value) {
			$categoriesList[$value->id] = $value->name;
		}

		$this
			->table('catalog')
			//->name('Каталог')
			->field('id', 'id')
			->field('extid', 'int', 'Внешний ID')
			->field('distributor', 'string')
			->field('category', 'list',
					array('values' => $categoriesList, 'name' => 'Категория')
				)
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
		$categories = yCore::db('object')
			->select(yCore::object('catalog/category')->get());

		$categoriesList = array();
		$categoriesList[''] = 'Все';
		foreach ($categories as $key => $value) {
			$categoriesList[$value->id] = $value->name;
		}
		
		$this
			->table('catalog')
			//->name('Каталог')
			->field('id', 'id')
			->field('category', 'list',
					array('values' => $categoriesList, 'name' => 'Категория')
				)
			->field('name', 'string', 'Название')
			->field('price', 'currency', 'Цена')
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
						'scope' => 'external',
						'show' => true)
					)
			->filter('category',
					array(
						'type' => 'field',
						'field' => 'category',
						'scope' => 'external',
						'show' => true)
					)
			->filter('price',
					array(
						'type' => 'order',
						'field' => 'price',
						'scope' => 'external',
						'show' => true)
					)
			->filter('page',
					array(
						'type' => 'page',
						'scope' => 'external',
						'rows' => '5',
						'show' => true)
					);
		return $this;
	}
}

?>