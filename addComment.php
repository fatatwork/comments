<?php
session_start();
require_once 'funcLib.php';
require_once 'app_config.php';

function loginUser() {
	$life_time = time() + ( 60 * 60 * 24 * 7 );
	if ( isset( $_COOKIE['up_key_vk'] ) ) {
		$networkPrefix = 'vk';
	}
	if ( isset( $_COOKIE['up_key_fb'] ) ) {
		$networkPrefix = 'fb';
	}
	if ( isset( $_COOKIE['up_key_gp'] ) ) {
		$networkPrefix = 'gp';
	}

	$userInfo = getUserByHash( $_COOKIE[ 'up_key_' . $networkPrefix ] );
	if ( $userInfo ) {
		$userInfo['identity'] = $userInfo['network_url'];
		addCommentFromPage( $userInfo );//добавляем коммент
		//обновляем куку
		setcookie( 'up_key_' . $networkPrefix, $userInfo['user_hash'],
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