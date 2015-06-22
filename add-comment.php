<?php
session_start();
require_once 'funcLib.php';

if ( isset( $_COOKIE['first_name'], $_COOKIE['last_name'] ) ) {
	$username['first_name'] = $_COOKIE['first_name'];
	$username['last_name']  = $_COOKIE['last_name'];
	$username['image']		= $_COOKIE['image'];
	$username['network']    = $_COOKIE['network'];
	$username['identity']   = $_COOKIE['identity'];
	$boolCheckCookie        = true;
} else {
		if ( isset( $_SESSION['first_name'], $_SESSION['last_name'] ) ) {
			$username['first_name'] = $_SESSION['first_name'];
			$username['last_name']  = $_SESSION['last_name'];
			$username['image']		= $_SESSION['image'];
			$username['network']    = $_SESSION['network'];
			$username['identity']   = $_SESSION['identity'];
		}
}
if(isset($_POST['pageUrl'])) $page_url=$_POST['pageUrl'];
else $page_url = $_SESSION['page_url'];

if(isset($_POST['currentComment'])){
	$comment = trim( $_POST['currentComment'] );
	$user_ip=$_SERVER["REMOTE_ADDR"];
	if ( isset( $username ) ) {
	$article_id = searchArticle( $page_url ); //Получаем идентификатор страницы на которой нужно разместить комментарий
	$user_id = searchUser( $username );//первоначально ищем пользователя
		if($user_id){//Пишем коммент
		 updateUser($username, $user_id, $user_ip);
		}
		else {//если юзера нет- добавляем и пишем коммент
			addUser($username, $user_ip);
			$user_id = searchUser($username);
		}
		if($comment != "") addComment($article_id, $user_id, $comment);
	}
}
$commentOut = getComments($page_url); //Получаем комментарии

//print_r($commentOut);
if(is_array($commentOut) && sizeof($commentOut)>0){
	$commentOut = array_reverse($commentOut, true);
	foreach($commentOut as $comment){
		echo "<div class='comment'>".
		/*вывод аватарки
		"<a href=\"http://vk.com/id".$comment['network_url']."\">".
		"<img src=\"".$comment['image']."\"/></a>".*/
		"<span> <h4>"."<a href=\"http://vk.com/id".$comment['network_url']."\">".
		$comment['first_name'] . " " . $comment['last_name'] . "</a> " 
		. $comment['add_time'] . "</h4>" . $comment['comment']."</span>".
		"</div>";
	}
}else{
	echo "<div class='comment'>
			<span><p> Пока нет комментариев...</p></span>
		  </div>";
}
?>