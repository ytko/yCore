<?php defined ('_YEXEC')  or  die();

yCore::load('yBase');

class csvClass extends yBase {
	public
		$fileName,
		$fileEncoding = 'UTF-8';
	
	public function getMap($fileName = NULL) {
		if($fileName) {
			$this->fileName = $fileName;
			$this->loadFile($fileName);
		}

		if ($this->fileContent)
			$values = 
				(object)$this->TableToAssociative(
					$this->Split($this->fileContent)
				);
		else
			$values = (object)array();

		return $values;
	}
	
	public function loadFile($fileName = NULL) {
		if($fileName) $this->fileName = $fileName;
		
		$fileData = '';
		$fd = fopen($this->fileName, 'r');
//utf8_fopen_read
		if (!$fd)   {
			echo "Error! Could not open file '{$this->fileName}'.";
			die;
		}
	
		while (!feof($fd))   {
			$fileData .= fgets($fd);
		}
	
		fclose ($fd);
	
		$this->fileContent = $fileData;
		
		if ($this->fileEncoding != 'UTF-8')
			$this->fileContent = iconv($this->fileEncoding, 'UTF-8', $this->fileContent);
		
		return $this;
	}
	
	protected function Split($subject) {
	
		$subject = str_replace('""', '&quot;', $subject); //переделать

		do {
			$pattern_br = '/(?<=^|;)(?>"([^"]*?)(\r\n)(.*?)")(?=\r\n|;)/ms';
			$subject = preg_replace_callback (
				$pattern_br,
				'replace_RN_callback',
				$subject,
				-1,
				$count
			);
		} while ($count);

		$pattern_nr = "/\r\n/"; 	//split to lines
		$pattern_comma = '/(?<=;|^)(?:"(.*?)"|([^"]*?))(?:;|$)/s';
	
		$lines = preg_split($pattern_nr, $subject);				//split to lines

		$cntr=0;
		foreach($lines as $key => $line) {
			preg_match_all($pattern_comma, $line, $matches, PREG_PATTERN_ORDER);	//split to cells
			foreach($matches[0] as $i => $v) {
				$matches[0][$i] = str_replace('&quot;', '"', $matches[2][$i].$matches[1][$i]); //переделать
			}
			
																					//echo($key.':'); print_r($matches);
			$lines[$key] = $matches[0];
			if ((count($lines[$key])==0)||empty($lines[$key][0])) unset($lines[$key]);
																//kill empty lines
		}
		
		return $lines;
	}
	
	//преобразует таблицу, подставляя значения из ячеек первой строки как названия ключей для ячеек в остальных строках
	//первая строка удаляется; если ключ пуст, весь столбец удаляется
	protected function TableToAssociative($array) {
		$result = array();

		foreach ($array as $line_key => $line) {
			if ($line_key == 0) continue;
	
			$result[$line_key] = Array();
			foreach ($line as $cell_key => $cell) {
				$newKey = $array[0][$cell_key];
				
				if (!empty($newKey))
					$result[$line_key][$newKey] = $cell;
			}
		}

		return $result;
	}
	
	protected function SplitAssociative($subject) {
		return $this->TableToAssociative($this->Split($subject));
	}
}

function replace_RN_callback($replace) {
	return preg_replace ('/(\r\n)/ms', '<br />', $replace[0]);
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
