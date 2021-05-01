<?php
//require_once 'phpthumb/ThumbLib.inc.php';
require_once APPPATH.'/third_party/phpthumb/ThumbLib.inc.php';

$thumb = PhpThumbFactory::create($_GET["imagePath"]);
if(isset($_GET["w"]) && trim($_GET["w"])!="" && isset($_GET["w"]) && trim($_GET["w"])!=""){
	$thumb->resize($_GET["w"],$_GET["h"]);
}
$thumb->show();
?>