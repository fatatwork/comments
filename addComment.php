<?php
session_start();
require_once 'funcLib.php';

function loginUser() {
	$life_time     = time() + ( 60 * 60 * 24 * 7 );
	$access_path   = "/";
	$access_domain = "comments.akson.by";
	if ( isset( $_COOKIE['up_key_vk'] ) )	$networkPrefix='vk';
	if ( isset( $_COOKIE['up_key_fb'] ) )	$networkPrefix='fb';

	$userInfo = getUserByHash( $_COOKIE['up_key_'.$networkPrefix] );
	if ( $userInfo ) {
		$_POST['first_name'] = $userInfo['first_name'];
		$_POST['last_name']  = $userInfo['last_name'];
		$_POST['image']      = $userInfo['image'];
		$_POST['network']    = $userInfo['network'];
		$_POST['identity']   = $userInfo['network_url'];
		addCommentFromPage();//добавляем коммент
		//обновляем куку
		setcookie( 'up_key_'.$networkPrefix, $userInfo['user_hash'], $life_time,
			$access_path,
			$access_domain );
	}
}
loginUser();
//получаем данные по пользователе от вконтакта

$comments = getCommentsFromPage();
foreach ( $comments as $comment ) {
	echo $comment;
}
?>