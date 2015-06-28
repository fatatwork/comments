<?php
require_once 'funcLib.php';
$comments=getCommentsFromPage();
foreach ($comments as $comment) {
	echo $comment;
}
?>