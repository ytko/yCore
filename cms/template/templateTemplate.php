<?php defined ('_YEXEC')  or  die();

yFactory::linkTemplate();

class templateTemplateClass extends yTemplateClass {
	protected $content;
	
	function setContent($content) {
		$this->content = $content;
		return $this;
	}
	 
	function get() {
		return
<<<HEREDOC
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" >
<head>
<title></title>
<script src='http://code.jquery.com/jquery-1.7.1.min.js' type="text/javascript"></script>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link href='http://fonts.googleapis.com/css?family=Ubuntu:400&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Actor' rel='stylesheet' type='text/css'></head>
<link rel="icon" type="image/png" href="/files/images/yourfav.png" />
<!-- Yandex.Metrika counter --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter14114260 = new Ya.Metrika({id:14114260, enableAll: true, webvisor:true}); } catch(e) {} }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/14114260" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter -->
<style>

body {
	background-color:#eee;
	margin:0px;
	font-family: "arial", "tahoma", "verdana";
}

.header {
	position:relative;
	margin:0 0 0 50%;
	left:-480px;
	width:960px;
	
	border-radius:0 0 6px 6px;
	
	display:inline-block;
	margin-bottom:30px;
	margin-top:0px;
	padding:10px;
	background-color:#fff;
}

.catalog {
	position:relative;
	margin:0 0 20px 50%;
	left:-250px;
	width:500px;
}

.catItem {
	border-radius:4px;
	padding:10px;
	margin:10px 0;
	
	background-color:#fff;
	font-size: 12px;
}

.catItem .price {
	float:right;
	color: #999;
}

.catItem .price span {
	color: #F7941C;
	font-size: 16px;
	font-weight: 700;
}

.catItem .description {
	color: #666;
	background-color:#eee;
	margin:10px 0;
	padding:10px;
	border-radius:4px;
}

.catItem .category {
	width:100%;
	text-align:right;
	font-size: 10px;
}

.pagination {
	font-size:12px;
}

.pagination a {
	color:#000;
	text-decoration:none;
}

.pagination a:hover {
	text-decoration:underline;
}
		
.search {
	font-size:12px;
	margin:10px 0;
	padding:10px;
	border-radius:4px;
	border:2px solid #fff;
}
		
.search .label {
	width:100px;
	display:inline-block;
}

.footer { 
	background-color: #E6E5EA;
	border-top: 1px solid #fff;
	bottom: 0px;
	padding: 5px 0px;
	text-align: center;
	width: 100%;
	height:40px;
}

.footerText {
	font-family: "Ubuntu", sans-serif;
	font-size:12px;
	float:left;
	width:60%;
}
		
.footerBanners {
	float:left;
	width:20%;
	text-align:left;
	padding-left:5px;
}

</style>
</head>
<body>
	<div class='header'>
		<div class="logo">
			<a href="/">
				<img alt="Интернет магазин ёGenius (yourgenius.ru)" align="left" border="0" width="150" height="80" title="Интернет магазин ёGenius (yourgenius.ru)" src="/files/images/logo.png">
			</a>
		</div>
		<div style="font-family:actor; display:inline-block; float:right; margin:10px 0 5px 0;">(812) <span style="font-size:1.8em; color:#f60;">987 1626</span></div>
	</div>
	<div>
		$this->content
	</div>
	<div class="footer">
		<div class="footerBanners">
<script type="text/javascript"><!--
document.write("<a href='http://www.liveinternet.ru/click' "+
"target=_blank><img src='//counter.yadro.ru/hit?t44.1;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";h"+escape(document.title.substring(0,80))+";"+Math.random()+
"' alt='' title='LiveInternet' "+
"border='0' width='31' height='31'><\/a>")
//--></script>
		</div>
		<div class="footerText">
			<a href="http://yourgenius.ru">yourGenius</a> — интернет-магазин <b>встраиваемой бытовой техники</b> в Санкт-Петребурге.
		</div>
<style type="text/css">
.ytko a {
	color:#000;
	background:transparent;
}
.ytko a:hover {
	color:#000;
	background:transparent;
}
</style>

		<div style="float:right; margin-right:10px;" class="ytko">
			<a href="http://www.ytko.ru" target="_blank">
				<img src="http://www.ytko.ru/logo.png" alt="разработано студией YTKO" title="разработано студией YTKO">
			</a>
<script type="text/javascript"><!-- 
$(document).ready(function(){
	$(".ytko").css({opacity:'0.3'});
	
	$(".ytko").mouseover(function(){
		$(this).stop().animate({opacity:'1.0'},600);
			});
	$(".ytko").mouseout(function(){
		$(this).stop().animate({opacity:'0.3'},600);
			});
});
//--></script>
		</div>
	</div>
</body>
</html>
HEREDOC;
	}

// ------------------------------------------------------------------------------------------------
} ?>