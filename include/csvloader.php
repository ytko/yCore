<?php

function replace_RN_callback($replace) {
	return preg_replace ('/(\r\n)/ms', '<br />', $replace[0]);
}

class csvClass {
	function Split($subject) {
	
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
	function TableToAssociative($array) {
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
	
	function SplitAssociative($subject) {
		return $this->TableToAssociative($this->Split($subject));
	}
	
	function GetFile($filename) {
		$file_data = "";
		$fd = fopen($filename, "r");
	//utf8_fopen_read
		if (!$fd)   {
			echo "Error! Could not open the file.";
			die;
		}
	
		while (!feof($fd))   {
			$file_data .= fgets($fd);
		}
	
		fclose ($fd);
	
		return $file_data;
	}
}
?>