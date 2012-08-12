<?php defined ('_YEXEC')  or  die();

yCore::load('objectModel');

class structureTreeModel extends objectModel {
	public function get(&$object, $pid = NULL) {
		$where = $pid ? "`pid` = $pid" : "`pid` IS NULL OR `pid` = 0";
		$where = "($where) AND `enabled` = 1";
		$trunk = yCore::objectDb()->where($where)->select($object);
		$tree = array();

		foreach($trunk as $node) {
			$children = $this->get($object, $node->id);
			
			if($children) $node->children = $children;
			
			$tree[] = $node;
		}
		
		return $tree;
	}
	
	public function categoryID($object, $key1, $key2 = NULL) {

		$id = yCore::objectDb()->where("`keyword` LIKE '$key1'")->field('id')->selectCell($object);

		if(isset($key2))
			$id = yCore::objectDb()->where("`keyword` LIKE '$key2' AND `pid` = '$id'")->field('id')->selectCell($object);
		
		return $id;
	}
}

?>