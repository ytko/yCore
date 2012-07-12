<?php defined ('_YEXEC')  or  die();

yFactory::linkObject();

class catalogObjectClass extends yObjectClass {
	public function full() {
		$this
			->table('catalog')
			//->name('Каталог')
			->field('id', 'id')
			->field('extid', 'int')
			->field('distributor', 'string')
			->field('category', 'string')
			->field('vendor', 'string')
			->field('name', 'string')
			->field('price', 'currency')
			->field('description', 'text');
		return $this;
	}
	
	public function cat() {
		$this
			->table('catalog')
			//->name('Каталог')
			->field('id', 'id')
			->field('category', 'string')
			->field('vendor', 'string')
			->field('name', 'string')
			->field('price', 'currency')
			->field('description', 'text')
			->filter('page', 'page', array('type' => 'page', 'rows' => '20'));
		return $this;
	}
}

?>