<?php defined ('_YEXEC')  or  die();

yFactory::includeObject();

class catalogObjectClass extends yObjectClass {
	public function full() {
		$categories = yFactory::db('object')
				->select(yFactory::object('catalog')->category());

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
		$categories = yFactory::db('object')
			->select(yFactory::object('catalog')->category());

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
	
	public function category() {
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