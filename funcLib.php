<?php
require_once 'connectDB.php';
require_once 'VKclass.php';

function searchActicle( $page_adress ) {
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

function addUser( $username, $user_ip ) {//добавление пользователя
	if ( isset( $username['first_name'] ) ) {
		$query
			=
			"INSERT INTO users (first_name, last_name, image, network, network_url, user_ip) 
			VALUES ('{$username['first_name']}', '{$username['last_name']}','{$username['image']}', 
				'{$username['network']}', '{$username['identity']}', INET_ATON('{$user_ip}'));";
		$result = mysql_query( $query )
		or die( "<p>Невозможно добавить пользователя " . mysql_error()
		        . "</p>" );
	}
}

function updateUser( $username, $user_id, $user_ip ) {
	//$user_id айди пользователя внутри нашей собственной базы
	$app_id   = '4832378';
	$vk       = new vk( $token, $delta, $app_id, $group_id );
	$userinfo = $vk->getOneUser( $username['identity'] );
	$userinfo = $userinfo[0];//т.к вернется массив из 1 пользователя
	$query
	          =
		"UPDATE users SET first_name='{$userinfo->first_name}',last_name='{$userinfo->last_name}', image='{$userinfo->photo_50}', 
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
	$newsID     = searchActicle( $page_adress );
	$actualTime = time();
	//Создаем запрос на слияние данных о пользователях с данными об их комментариях
	$query
		= "SELECT id, user_id, comment, add_time, first_name, last_name, image, network_url, ban_time, user_ip 
		FROM users NATURAL JOIN comments WHERE news_id='{$newsID}' AND deleted=false ORDER BY id;";
	$result_obj = mysql_query( $query )
	or die( "<p>Невозможно получить данные о комментариях: " . mysql_error()
	        . "</p>" );
	$commentArray = array();
	while ( $row = mysql_fetch_array( $result_obj ) ) { //Сюда должна лечь новая строка ассоциативного массива
		//$row['add_time'] = date( "d.m.y - H:i", $row['add_time'] ); //преобразуем время к формату
		array_push( $commentArray, $row );
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
?>