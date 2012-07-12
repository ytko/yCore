<?php defined ('_YEXEC')  or  die();

@require_once 'base.php';

class yTemplateClass extends yBaseClass{
	public $model;
	
	public function setModel($model) {
		$this->model = $model;
		return $this;
	}
	
	public function head(&$_) {}
	
	public function body(&$_) {}
	
	static function getURI($base, $query, $modify = NULL) {
		$result = $base;

		$query = (array)$query;

		if($modify)
		foreach ($modify as $key => $value)
			$query[$key] = $value;

		$first = true;
		foreach ($query as $key => $value)
			if (isset($value))
			if ($first) {
			$first = false;
			$result.= '?'.$key.'='.$value;
		} else
			$result.= '&amp;'.$key.'='.$value;

		return $result;
	}
	
	// ----- FORM FIELDS --------------------------------------------------------------------------
	
	static public function intInput($key, $value, $name = NULL) {
		return
			"<label for='{$key}Input'>$name</label><input type='text' name='$key' id='{$key}Input' value='$value' />";
	}
	
	static public function hiddenInput($key, $value) {
		return
			"<input type='hidden' name='$key' value='$value' />";
	}
	
	static public function stringInput($key, $value, $name = NULL) {
		return
			"<label for='{$key}Input'><div class='label {$key}InputLabel'>$name</div></label><input type='text' name='$key' id='{$key}Input' value='$value' />";
	}
		
	static public function floatInput($key, $value, $name = NULL) {
		return
			"<label for='{$key}Input'>$name</label><input type='text' name='$key' id='{$key}Input' value='$value' />";
	}
	
	static public function currencyInput($key, $value, $name = NULL) {
		return
			"<label for='{$key}Input'>$name</label><input type='text' name='$key' id='{$key}Input' value='$value' />";
	}
	
	static public function textInput($key, $value, $name = NULL) {
		return
			"<label for='{$key}Input'>$name</label><textarea name='$key' id='{$key}Input'>$value</textarea>";
	}
	
	static public function listInput($key, $values, $active = NULL, $name = NULL) {
		$result.= "{$name}:<br /><select name='{$key}'>";
			if(isset($values)) foreach ($values as $iKey => $iName) {
				$iKey = htmlspecialchars($iKey, ENT_QUOTES);
				$iName = htmlspecialchars($iName, ENT_QUOTES);
				$iSelected = ($iKey == $active) ? ' selected' : NULL;
				$result.= "<option value='$iKey'{$iSelected}>$iName</option>";
			}
		$result.= "</select>";
				
		return $result;
	}

	// ----- PAGINATION ---------------------------------------------------------------------------
	
	static public function getPaginationArray($page, $itemsPerPage, $itemsCount, $rad = 3) {
		$pagination = (object)array();
	
		$last = ceil($itemsCount / $itemsPerPage);
	
		$pagination->first = '1';
		$pagination->prev = (($page - 1) >= $pagination->first) ? $page - 1 : NULL;
	
		$pagination->before = array();
		for ($i = ((($page - $rad) > $pagination->first) ? ($page - $rad) : $pagination->first); $i < $page; $i++) {
			$pagination->before[$i] = $i;
		}
	
		$pagination->current = $page;
	
		$pagination->after = array();
		for ($i = $page + 1; $i <= ((($page + $rad) < $last) ? ($page + $rad) : $last); $i++) {
			$pagination->after[$i] = $i;
		}
	
		$pagination->next = (($page + 1) <= $last) ? $page + 1 : NULL;
		$pagination->last = $last;
	
		return $pagination;
	}
	
	static public function getPagination($page, $itemsPerPage, $itemsCount, $rad = 3) {
		$pagination = self::getPaginationArray($page, $itemsPerPage, $itemsCount, $rad);
		
		if($pagination->last != 1) {
			$url = '';
			$query = $_GET; //TODO: connect with controller!!!

			$result.=
				($pagination->first != $pagination->current)
					? "<a href='".self::getURI($url, $query, array('page' => $pagination->first))."'><<</a> "
					: NULL;

			$result.=
				(!empty($pagination->prev))
					? "<a href='".self::getURI($url, $query, array('page' => $pagination->prev))."'><</a> "
					: NULL;

			foreach($pagination->before as $page)
				$result.= "<a href='".self::getURI($url, $query, array('page' => $page))."'>$page</a> ";      

			$result.= "<strong>{$pagination->current}</strong> ";

			foreach($pagination->after as $page)
				$result.= "<a href='".self::getURI($url, $query, array('page' => $page))."'>$page</a> ";      

			$result.=
				(!empty($pagination->next))
					? "<a href='".self::getURI($url, $query, array('page' => $pagination->next))."'>></a> "
					: NULL;

			$result.=
				($pagination->last != $pagination->current)
					? "<a href='".self::getURI($url, $query, array('page' => $pagination->last))."'>>></a> "
					: NULL;
		}
		return $result;
	}
}

?>