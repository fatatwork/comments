<?php
require_once 'connectDB.php';
define('HASH_PREFIX', 'bsmu_');
function searchArticle( $page_adress ) {
	$query = "SELECT id FROM news WHERE link = '{$page_adress}';";
	$result = mysql_query( $query )
	or die( "<p>Невозможно получить адрес страницы: " . mysql_error()
	        . "</p>" );
	$row        = mysql_fetch_array( $result );
	$article_id = $row['id'];

	return $article_id;
}

function searchArticleById( $news_id ) {
	$query = "SELECT * FROM news WHERE id = '{$news_id}';";
	$result = mysql_query( $query )
	or die( "<p>Невозможно получить адрес страницы: " . mysql_error()
	        . "</p>" );
	$row = mysql_fetch_array( $result );

	return $row;
}

function searchUser(
	$username
) {//ищем юзера по url возвращаем в качестве результата всю строку row
	$query
		= "SELECT * FROM users WHERE network_url = '{$username['identity']}';";//ищем есть ли такой же url в базе
	$res = mysql_query( $query )
	or die( "<p>Невозможно сделать запрос поиска пользователя: " . mysql_error()
	        . "</p>" );
	$row = mysql_fetch_array( $res );//получение результата запроса из базы;
	return $row['user_id'];
}

function searchUserById( $user_id ) {
	$query
		= "SELECT * FROM users WHERE user_id = '{$user_id}'";//ищем есть ли такой же url в базе
	$res = mysql_query( $query )
	or die( "<p>Невозможно сделать запрос поиска пользователя: " . mysql_error()
	        . "</p>" );
	$row = mysql_fetch_array( $res );//получение результата запроса из базы;
	return $row;
}
function getUserByHash($hash){
	$query
		= "SELECT * FROM users WHERE user_hash = '{$hash}'";//ищем есть ли такой же url в базе
	$res = mysql_query( $query )
	or die( "<p>Невозможно сделать запрос поиска пользователя: " . mysql_error()
	        . "</p>" );
	$row = mysql_fetch_array( $res );//получение результата запроса из базы;
	return $row;
}
function addUser( $username, $user_ip ) {//добавление пользователя
	if ( isset( $username['first_name'], $username['last_name'], $username['identity'] ) ) {
		$hash_str = sha1(HASH_PREFIX.$username['identity']);
		$query
			=
			"INSERT INTO users (first_name, last_name, image, network, network_url,user_hash, user_ip)
			VALUES ('{$username['first_name']}', '{$username['last_name']}','{$username['image']}', 
				'{$username['network']}', '{$username['identity']}', '{$hash_str}', INET_ATON('{$user_ip}' ));";
		$result = mysql_query( $query )
		or die( "<p>Невозможно добавить пользователя " . mysql_error()
		        . "</p>" );
	}
}

function updateUser( $username, $user_id, $user_ip ) {
	$query  =
		"UPDATE users SET first_name='{$username['first_name']}',last_name='{$username['last_name']}', image='{$username['image']}', 
		user_ip=INET_ATON('{$user_ip}') WHERE user_id='{$user_id}';";
	$result   = mysql_query( $query );
}

function addComment( $article_id, $user_id, $comment ) {//добавляем комментарий
	$query    = "SELECT ban_time FROM users WHERE user_id='{$user_id}';";
	$res      = mysql_query( $query );
	$ban_time = mysql_fetch_row( $res );
	if ( $ban_time[0] != 0 ) {
		return false;
	}
	$query  = "INSERT INTO comments (news_id, user_id, comment, add_time) 
	          VALUES ('{$article_id}', '{$user_id}', '{$comment}', NOW());";
	$res = mysql_query( $query )
	or die( "<p>Невозможно сделать запись комментария: " . mysql_error()
	        . "</p>" );
	if ( $res )	return true;
    else return false;
}

function getComments( $page_adress ) {
	 //INET_ATON-преобразует ip В число и INET_NTOA-число в ip
	$newsID     = searchArticle( $page_adress );
	$actualTime = time();
	//Создаем запрос на слияние данных о пользователях с данными об их комментариях
	$query
		= "SELECT id, user_id, comment, add_time, first_name, last_name, image, network_url, network,ban_time, user_ip 
		FROM users NATURAL JOIN comments WHERE news_id='{$newsID}' AND deleted=false ORDER BY id;";
	$result_obj = mysql_query( $query )
	or die( "<p>Невозможно получить данные о комментариях: " . mysql_error()
	        . "</p>" );
	$commentArray = array();
	while ( $row = mysql_fetch_array( $result_obj ) ) { //Сюда должна лечь новая строка ассоциативного массива
		if($row!=null || $row!=false) array_push( $commentArray, $row );
	}

	return $commentArray;
}
function getUsers() {
	$query = "SELECT * FROM users ORDER BY last_name";
	$result = mysql_query( $query )
	or die( "<p>Невозможно получить данные о пользователях: " . mysql_error()
	        . "</p>" );
	$usersArray = array();
	while ( $row = mysql_fetch_array( $result ) ) {
		array_push( $usersArray, $row );
	}

	return $usersArray;
}

function banUser( $user_id, $ban_time ) {
	if($ban_time){
		switch($ban_time){
			case 'day': $ban_time=time()+24*3600;
				break;
			case 'week': $ban_time=time()+7*24*3600;
				break;
			case 'month': $ban_time=time()+31*24*3600;
				break;
			case 'year': $ban_time=time()+12*31*24*3600;
				break;
			case 'forever': $ban_time=-1;
				break;
			default: break;
		}
	}
	$query
		= "UPDATE users SET ban_time='{$ban_time}' WHERE user_id='{$user_id}';";
	$res = mysql_query( $query ) or die( "<p>Невозможно забанить пользователя: "
	                                     . mysql_error() . "</p>" );
}

function getBannedUsers() {
	$time = time();
	$query
	      = "SELECT user_id  FROM users WHERE ban_time!=0;";
	$result = mysql_query( $query )
	or die( "<p>Невозможно получить данные о пользователях: " . mysql_error()
	        . "</p>" );
	$usersArray = array();
	while ( $row = mysql_fetch_array( $result ) ) {
		array_push( $usersArray, $row );
	}

	return $usersArray;
}

function getArticles() {
	$query          = "SELECT * FROM news ORDER BY date;";
	$res            = mysql_query( $query )
	or die( "<p>Невозможно получить список новостей: " . mysql_error()
	        . "</p>" );
	$articles_array = array();
	while ( $row = mysql_fetch_array( $res ) ) {
		array_push( $articles_array, $row );
	}

	return $articles_array;
}
function deleteComment($comment_id){
	$query="UPDATE comments SET deleted=1 WHERE id='{$comment_id}';";
	$res            = mysql_query( $query )
	or die( "<p>Невозможно удалить комментарий: " . mysql_error()
	        . "</p>" );
}
function addCommentFromPage(){
	if ( isset( $_POST['first_name'], $_POST['last_name'] ) ) {
	$username['first_name'] = $_POST['first_name'];
	$username['last_name']  = $_POST['last_name'];
	$username['image']		= $_POST['image'];
	$username['network']    = $_POST['network'];
	$username['identity']   = $_POST['identity'];
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
}
function getCommentsFromPage(){
	if(isset($_POST['pageUrl']))$commentOut = getComments($_POST['pageUrl']); //Получаем комментарии
	else $commentOut=getComments($_SESSION['page_url']);
	$html_text=array();
	if(is_array($commentOut) && sizeof($commentOut)>0){
		$commentOut = array_reverse($commentOut, true);
		foreach($commentOut as $comment){
			switch ($comment['network']) {
				case 'vk.com':
					$networkPrefix='http://vk.com/id';
					break;
				case 'facebook.com':
					$networkPrefix='http://www.facebook.com/';
					break;
			}
			$text="<div class='comment'>".
			/*вывод аватарки
			"<a href=\"http://vk.com/id".$comment['network_url']."\">".
			"<img src=\"".$comment['image']."\"/></a>".*/
			"<span> <h4>"."<a href=".$networkPrefix.$comment['network_url'].">".
			$comment['first_name'] . " " . $comment['last_name'] . "</a> " 
			. $comment['add_time'] . "</h4>" . $comment['comment']."</span>".
			"</div>";
			array_push($html_text, $text);
		}
	}else{
		$text= "<div class='comment'>
				<span><p> Пока нет комментариев...</p></span>
			  </div>";
		array_push($html_text, $text);
	}
	return $html_text;
}
function getHashForUser($network_url){

	$query = "SELECT user_hash FROM users WHERE network_url = '{$network_url}'";//ищем есть ли такой же url в базе
	$res = mysql_query( $query )
		or die( "<p>Невозможно сделать запрос поиска пользователя: " . mysql_error()
		        . "</p>" );
	$row = mysql_fetch_array( $res );//получение результата запроса из базы;
	return $row[0];
}
?>