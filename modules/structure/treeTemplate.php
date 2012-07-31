<?php defined ('_YEXEC')  or  die();

yCore::load('objectTemplate');

class structureTreeTemplate extends objectTemplate {
	public
		$key = 'keyword',
		$value = 'name';
	
	public function get($trunk, $level = 1, $path = '') {
		$result = "<ul class='level{$level}'>";
		
		foreach($trunk as $node) {
			$result.= "<li id='{$node->{$this->key}}'><a href='$path/{$node->{$this->key}}'>{$node->{$this->value}}</a>";

			if($node->children)
				$result.= $this->get($node->children, $level+1, $path.'/'.$node->{$this->key});
			
			$result.= "</li>";
		}
		
		$result.= '</ul>';
		
		return $result;
		
		/*
		foreach($categories as $category) {
			$subCategories = yCore::db('object')->where("`pid` = {$category->id}")->select($object);
			
			echo "<li id='{$category->keyword}'><a href='/{$category->keyword}'>{$category->name}</a>";
			echo "<ul class='level2'>";
			
				foreach($subCategories as $subCategory) {
					echo "<li id='{$subCategory->keyword}'><a href='/{$category->keyword}/{$subCategory->keyword}'>{$subCategory->name}</a>";
					echo "</li>";
				}
			
			echo "</ul>";
			echo "</li>";
			//print_r($subCategories);
		}*/
	}
}

?>