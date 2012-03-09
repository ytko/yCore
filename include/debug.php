<?php

function __p($expression) {
	echo '<pre>';
	$result = print_r($expression);
	echo '</pre>';
	return $result;
}

class yDebug {
	static $startMessage = 'Debug Mode is ON. Use yDebug::<em>$all</em> flag to activate all debugger functions or <em>$methods</em>, <em>$query</em>.';
	static $debugStarted = false;
	static $on = false;
	static $all = false;
	static $allMethods = false;
	static $query = false;
	
	function message($message) {
		if (!yDebug::$on) return;
	
		if(!yDebug::$debugStarted) {
			yDebug::$debugStarted = true;
			yDebug::message(yDebug::$startMessage);
		}
		
		echo $message.'<br />';
	}
	
	function method($method) { //yDebug::method(__METHOD__);
		if (!yDebug::$on) return;
		if (!yDebug::$allMethods && !yDebug::$all) return;
		
		yDebug::message($method);
	}
	
	//$yDbQuery == true
	
	function query($query) {
		if (!yDebug::$on) return;
		if (!yDebug::$query && !yDebug::$all) return;
		
		yDebug::message($query);
	}
}

?>