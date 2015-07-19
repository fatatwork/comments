<?php
session_start();
require_once 'funcLib.php';
require_once 'app_config.php';

function loginUser() {
	$life_time = time() + ( 60 * 60 * 24 * 7 );
	global $cookieArray;
	$cookie = null;
	foreach ( $cookieArray as $c ) {
		if ( $_COOKIE[ $c ] ) {
			$cookie = $c;
		}
	}
	$userInfo = getUserByHash( $_COOKIE[ $cookie ] );
	if ( $userInfo ) {
		$userInfo['identity'] = $userInfo['network_url'];
		addCommentFromPage( $userInfo );//добавляем коммент
		//обновляем куку
		setcookie( $cookie, $userInfo['user_hash'],
			$life_time,
			ACCESS_PATH,
			ACCESS_DOMAIN );
	}
}

loginUser();
//получаем данные по пользователе от вконтакта

$comments = getCommentsFromPage();
foreach ( $comments as $comment ) {
	echo $comment;
}
?>