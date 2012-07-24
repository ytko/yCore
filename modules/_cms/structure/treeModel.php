<?php defined ('_YEXEC')  or  die();

yCore::includeModel('object');

class structureTreeModelClass extends objectModelClass {
	public function get($pid = NULL) {
		$where = $pid ? "`pid` = $pid" : "`pid` IS NULL OR `pid` = 0";
		$trunk = yCore::db('object')->where($where)->select($this->object);
		
		foreach($trunk as $node) {
			$children = $this->get($node->id);
			
			if($children) $node->children = $children;
			
			$tree[] = $node;
		}
		
		return $tree;
	}
	
	public function categoryID($object, $key1, $key2 = NULL) {
		$id = yCore::db('object')->where("`keyword` LIKE '$key1'")->field('id')->selectCell($object);

		if(isset($key2))
			$id = yCore::db('object')->where("`keyword` LIKE '$key2' AND `pid` = '$id'")->field('id')->selectCell($object);
		
		return $id;
	}
}

?>