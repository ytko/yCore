<?php defined ('_YEXEC')  or  die();

yFactory::linkObject();

class badgeObjectClass extends yObjectClass {
	public function __construct() {
		$this
			->setTable('mod_badges')
			->addField('id', 'id')
			->addField('firstName', 'text')
			->addField('midName', 'string')
			->addField('lastName', 'string')
			->addField('position', 'string')
			->addField('cid', 'int')
			->addField('uid', 'int')
			->addField('Message_ID', 'int');
	}
}

?>