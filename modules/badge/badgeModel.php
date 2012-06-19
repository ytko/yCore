<?php defined ('_YEXEC')  or  die();

yFactory::linkModel();

class badgeModelClass extends yModelClass {
	public $classID, $messageID, $userID;

	public function set($key, $value) {
		$this->$key = $value;
		return $this;
	}
	
	public function addBadges($values) {
		$db = yFactory::getDb();
		$object = yFactory::getObject('badge');
		
		foreach($values as $key => $item) {
			if ($item['lastName'] and $item['firstName'] and $item['midName'] and $item['position']) {
				$item['cid'] = $this->classID;
				$item['Message_ID'] = $this->messageID;
				$item['uid'] = $this->userID;				

				$object->addRow($item);
			}
		}
		
		$db->insert($object);
		
		return $this;
	}
}

?>