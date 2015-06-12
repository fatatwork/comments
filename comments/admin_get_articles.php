<?php
require_once '../funcLib.php';
$articles=getArticles();
if(is_array($articles)){
	foreach($articles as $article){
		echo $article['date']."\t";
		echo "<button type='submit' value='".$article['id'].
		"' onclick=\"insertNewData('article=".$article['id']."', 'admin_get_comments.php', 'list' ,'POST')\"/>".
		$article['title']."</button><br />";
	}
	echo "</ul>";
}
?>