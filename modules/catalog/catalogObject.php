<?php defined ('_YEXEC')  or  die();

yCore::load('yObject');

class catalogObject extends yObject {
	public function __construct($mode = NULL) {
		parent::__construct();

		$categories = yCore::objectDb()
				->select(yCore::catalogCategoryObject());

		$categoriesList = array();
		foreach ($categories as $key => $value) {
			$categoriesList[$value->id] = $value->name;
		}

		$this->
			table('catalog')
			//->name('Каталог')
			->field('id', 'id')
			->filter('id',
						array('type' => 'field', 'field' => 'id', 'scope' => 'external')
					)
			->field('category', 'list',
						array('values' => $categoriesList, 'name' => 'Категория')
					)
			->field('price', 'currency', 'Цена')
			->field('name', 'string', 'Название')
			->field('description', 'text');

		if(!$mode)
			$this
				->field('extid', 'int', 'Внешний ID')
				->field('distributor', 'string')
				->field('vendor', 'string');
		
		elseif($mode == 'cat') {
			$this
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
		}
	}
}

?>