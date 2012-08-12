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

		$id = yCore::yObjectField()
			->setKey('id')
			->setType('id');

		$this
			->table('catalog')
			->name('Каталог')

			->field('id', 'id')
			->field(yCore::yObjectField('category')
						->setType('list')
						->setName('Категория')
						->setValues($categoriesList))
			->field('price', 'currency', 'Цена')
			->field('name', 'string', 'Название')
			->field('description', 'text')
			->field('extid', 'int', 'Внешний ID')
			->field('distributor', 'string')
			->field('vendor', 'string')

			->filter(yCore::yObjectFilter('id')
						->setScope('external'))
			->filter(yCore::yObjectFilter('name')
						->setCollation('like'))
			->filter(yCore::yObjectFilter('category'))
			->filter(yCore::yObjectFilter('price')
						->setType('order'))
			->filter(yCore::yObjectFilter('page')
						->setType('page')
						->setRows(5));

	}
}

?>