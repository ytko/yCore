<?php defined ('_YEXEC')  or  die();

yFactory::linkModel('object');

class catalogModelClass extends objectModelClass {
	function export($object) {
		include_once 'ignore/include/csvloader.php';
		$csv = new csvClass();
			
		$values = 
			$csv->TableToAssociative(
				$csv->Split(
					iconv('CP1251', "UTF-8", 
						$csv->getFile('input.csv')
					)
				)
			);
		
		$search = array(' ', ',');
		$replace = array('', '.');
		
		foreach($values as &$row) {
			$row = (object)$row;
			$row->price = str_replace($search, $replace, $row->price);
			$row->price = $row->price * 1.15;
			$row->distributor = 'Video City';
			/*$row->category = iconv('CP1251', "UTF-8", $row->category);
			$row->vendor = iconv('CP1251', "UTF-8", $row->vendor);
			$row->name = iconv('CP1251', "UTF-8", $row->name);
			$row->description = iconv('CP1251', "UTF-8", $row->description);*/
		}
		
		$object->values = $values;
		
		if ($object->values) 
			echo yFactory::getDb()->insert($object);
		
		return $this;
	}
}

?>