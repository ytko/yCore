<?php defined ('_YEXEC')  or  die();

@require_once 'yBase.php';

class yTemplate extends yBase{
	public $model;

	public function setModel($model) {
		$this->model = $model;
		return $this;
	}

	public function head() {}

	public function body() {}

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
	
	static protected function input($type, $key, $value, $name = NULL) {
		return <<<HEREDOC
<div class='{$type}Input {$key}Input inputWrap'>
		<label for='{$key}Input'>$name</label>
		<input type='text' name='$key' id='{$key}Input' value='$value' />
</div>
HEREDOC;
	}
	
	static public function intInput($key, $value, $name = NULL) {
		return self::input('int', $key, $value, $name);
	}
	
	static public function hiddenInput($key, $value) {
		return
			"<input type='hidden' name='$key' id='{$key}Input' value='$value' />";
	}
	
	static public function stringInput($key, $value, $name = NULL) {
		return self::input('string', $key, $value, $name);
	}
		
	static public function floatInput($key, $value, $name = NULL) {
		return self::input('float', $key, $value, $name);
	}
	
	static public function currencyInput($key, $value, $name = NULL) {
		return self::input('currency', $key, $value, $name);
	}
	
	static public function textInput($key, $value, $name = NULL) {
				return <<<HEREDOC
<div class='textInputWrap inputWrap {$key}InputWrap'>
		<label for='{$key}Input'>$name</label>
		<textarea name='$key' id='{$key}Input'>$value</textarea>
</div>
HEREDOC;
	}
	
	static public function selectInput($key, $values, $name = NULL, $active = NULL) {
		$result = '';
		if(isset($values)) foreach ($values as $iKey => $iName) {
			$iKey = htmlspecialchars($iKey, ENT_QUOTES);
			$iName = htmlspecialchars($iName, ENT_QUOTES);
			$iSelected = ($iKey == $active) ? ' selected' : NULL;
			$result.= "<option value='$iKey'{$iSelected}>$iName</option>";
		}

		return <<<HEREDOC
<div class='selectInputWrap inputWrap {$key}InputWrap'>
		<label for='{$key}Input'>$name</label>
		<select name='$key' id='{$key}Input'>$result</select>
</div>
HEREDOC;
	}
	
	static public function multiselectInput($key, $values, $name = NULL, $active = NULL) {
		if(isset($values)) foreach ($values as $iKey => $iName) {
			$iKey = htmlspecialchars($iKey, ENT_QUOTES);
			$iName = htmlspecialchars($iName, ENT_QUOTES);
			$iSelected = (is_array($active) && in_array($iKey, $active)) ? ' selected' : NULL;
			$result.= "<option value='$iKey'{$iSelected}>$iName</option>";
		}

		return <<<HEREDOC
<div class='selectInputWrap inputWrap {$key}InputWrap'>
		<label for='{$key}Input'>$name</label>
		<select multiple='multiple' name='{$key}[]' id='{$key}Input'>$result</select>
</div>
HEREDOC;
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

			$result =
				($pagination->first != $pagination->current)
					? "<a href='".self::getURI($url, $query, array('page' => $pagination->first))."'>&#9668;&#9668;</a> "
					: NULL;

			$result.=
				(!empty($pagination->prev))
					? "<a href='".self::getURI($url, $query, array('page' => $pagination->prev))."'>&#9668;</a> "
					: NULL;

			foreach($pagination->before as $page)
				$result.= "<a href='".self::getURI($url, $query, array('page' => $page))."'>$page</a> ";      

			$result.= "<strong>{$pagination->current}</strong> ";

			foreach($pagination->after as $page)
				$result.= "<a href='".self::getURI($url, $query, array('page' => $page))."'>$page</a> ";      

			$result.=
				(!empty($pagination->next))
					? "<a href='".self::getURI($url, $query, array('page' => $pagination->next))."'>&#9658;</a> "
					: NULL;

			$result.=
				($pagination->last != $pagination->current)
					? "<a href='".self::getURI($url, $query, array('page' => $pagination->last))."'>&#9658;&#9658;</a> "
					: NULL;
		}
		return $result;
	}
}

/*
 * Copyright 2012 Roman Exempliarov. 
 *
 * This file is part of yCore framework.
 *
 * yCore is free software: you can redistribute it and/or modify it under the
 * terms of the GNU Lesser General Public License as published by the Free
 * Software Foundation, either version 2.1 of the License, or (at your option)
 * any later version.
 * 
 * yCore is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU Lesser General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with yCore. If not, see http://www.gnu.org/licenses/.
 */

?>