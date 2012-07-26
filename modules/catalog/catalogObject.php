<?php defined ('_YEXEC')  or  die();

yCore::load('yObject');

class catalogObject extends yObject {
	public function __construct($mode = NULL) {
		parent::__construct();

		$categories = yCore::objectDb()
				->select(yCore::catalogCategoryObject());

		$categoriesList = array();
		$categoriesList[0] = 'Выбрать';
		foreach ($categories as $key => $value) {
			$categoriesList[$value->id] = $value->name;
		}

		$this
			->table('catalog')
			->name('Каталог')
			->field('id', 'id')
			->field('category', 'list',	array(
				'values' => $categoriesList, 'name' => 'Категория') )
			->field('price', 'currency', 'Цена')
			->field('name', 'string', 'Название')
			->field('description', 'text')
			->field('extid', 'int', 'Внешний ID')
			->field('distributor', 'string')
			->field('vendor', 'string')

			->filter('id', array(
				'field' => 'id', 'type' => 'field',
				'scope' => 'external', 'show' => false) )

			->filter('name', array(
				'field' => 'name', 'type' => 'field', 'collation' => 'like',
				'scope' => 'get', 'show' => true) )

			->filter('category', array(
				'field' => 'category', 'type' => 'field',
				'scope' => 'get', 'show' => true) )

			->filter('price', array(
				'field' => 'price', 'type' => 'order',
				'scope' => 'get', 'show' => true) )

			->filter('page', array(
				'type' => 'page', 'rows' => '5',
				'scope' => 'get', 'show' => true) );
	}
}

?>