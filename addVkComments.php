<?php
session_start();
require_once 'funcLib.php';

define('APP_ID', '4832378'); // ID приложения
define('APP_SECRET', '7S006k5mPrcsGwGY7FCI'); // Защищённый ключ

//Парисим вконтактную куку, на момент парсинга в массиве пост уже лежит url страницы с новостью
//и текст самого комментария
$session = array();

function authOpenAPIMember( $APP_ID, $APP_secret ) {
	global $session;
	$member     = false;
	$valid_keys = array( 'expire', 'mid', 'secret', 'sid', 'sig' );
	$app_cookie = $_COOKIE[ 'vk_app_' . APP_ID ];
	if ( $app_cookie ) {
		$session_data = explode( '&', $app_cookie, 10 );
		foreach ( $session_data as $pair ) {
			list( $key, $value ) = explode( '=', $pair, 2 );
			if ( empty( $key ) || empty( $value )
			     || ! in_array( $key, $valid_keys )
			) {
				continue;
			}
			$session[ $key ] = $value;
		}
		foreach ( $valid_keys as $key ) {
			if ( ! isset( $session[ $key ] ) ) {
				return $member;
			}
		}
		ksort( $session );

		$sign = '';
		foreach ( $session as $key => $value ) {
			if ( $key != 'sig' ) {
				$sign .= ( $key . '=' . $value );
			}
		}
		$sign .= $APP_secret;
		$sign = md5( $sign );
		if ( $session['sig'] == $sign && $session['expire'] > time() ) {
			$member = array(
				'id'     => intval( $session['mid'] ),
				'secret' => $session['secret'],
				'sid'    => $session['sid']
			);
		}
	}
	return $member;
}
function loginFromVkSession() {
	global $session;
	if ( isset( $_COOKIE[ 'vk_app_' . APP_ID ] ) ) {
		$member = authOpenAPIMember( APP_ID, APP_SECRET );
		if ( $member !== false ) {
			/* Пользователь авторизован в Open API */
			$params = array(
				'user_ids' => $session['mid'],
				'fields'   => 'id,first_name,last_name,screen_name,photo_50'
			);
			//Получаем данные пользователя
			$userInfo
				= json_decode( file_get_contents( 'https://api.vk.com/method/users.get'
				                                  . '?'
				                                  . urldecode( http_build_query( $params ) ) ),
				true );
			if ( isset( $userInfo['response'][0]['uid'] ) ) {
				$userInfo = $userInfo['response'][0];
			}
			if ( isset( $userInfo ) ) {
				//допполняем массив пост данными из вконтакте,
				//на момент дополнения в массиве уже лежат текст комментария и урл страницы
				$_POST['first_name'] = $userInfo['first_name'];
				$_POST['last_name']  = $userInfo['last_name'];
				$_POST['image']      = $userInfo['photo_50'];
				$_POST['network']    = "vk.com";
				$_POST['identity']   = $userInfo['uid'];
				return true;
			} else return false;
		}
	}
}
function loginUser() {
	if ( isset( $_COOKIE['up_key_vk'] ) ) {
		$userInfo = getUserByHash( $_COOKIE['up_key_vk'] );
		if ( $userInfo ) {
			$_POST['first_name'] = $userInfo['first_name'];
			$_POST['last_name']  = $userInfo['last_name'];
			$_POST['image']      = $userInfo['photo_50'];
			$_POST['network']    = "vk.com";
			$_POST['identity']   = $userInfo['network_url'];
			addCommentFromPage();//добавляем коммент
		}
	} else {
		$res=loginFromVkSession();
		if($res) {
			addCommentFromPage();
			$str = getHashForUser($_POST['identity']);
			$life_time     = time() + ( 60 * 60 * 24 * 7 );
			$access_path   = "/";
			$access_domain = "comments.akson.by";
			setcookie( 'up_key_vk',$str , $life_time, $access_path,
				$access_domain );
      if($_COOKIE['up_key_fb']) setcookie('up_key_fb', $_COOKIE['up_key_fb'], time()-3600,  $access_path,
        $access_domain);
		}
	}
}

loginUser();

//получаем данные по пользователе от вконтакта

$comments = getCommentsFromPage();
foreach ( $comments as $comment ) {
	echo $comment;
}
?>