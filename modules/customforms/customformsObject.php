<?php defined ('_YEXEC')  or  die();

yFactory::linkObject();

class customformsObjectClass extends yObjectClass {
	/*public function full() {
		$xidValues = array(1 => 'xxx', 2 => 'yyy');
		
		$this
			->table('mod_customforms')
			//->name('customforms')
			->field('id', 'id')
			->field('name', 'text')
			->field('patronymic', 'string')
			->field('surename', 'string')
			->field('position', 'string')
			->field('cid', 'int')
			->field('uid', 'int')
			->field('xid', 'list', array('values' => $xidValues));
		
		return $this;
	}*/
	
	public function full() {
		$xidValues = array(1 => 'xxx', 2 => 'yyy');
		
		$this
			->table('')
			//->name('customforms')
			->field('id', 'id')
			->field('name', 'text')
			->field('patronymic', 'string')
			->field('surename', 'string')
			->field('position', 'string')
			->field('cid', 'int')
			->field('uid', 'int')
			->field('xid', 'list', array('values' => $xidValues));
		
		return $this;
	}
	
	
}

?>