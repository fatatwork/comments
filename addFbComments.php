<?php
require_once 'funcLib.php';
require __DIR__ . '/fb-php-sdk-v4/autoload.php';
use Facebook\FacebookJavaScriptLoginHelper;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
define('FB_APP_ID', '403917006466762');
define('FB_APP_SECRET', '815900b1ab7d7a24a103464616c0badf');
define('FB_PHP_SDK_V4_DIR', '/fb-php-sdk-v4/src/Facebook/');
FacebookSession::setDefaultApplication(FB_APP_ID, FB_APP_SECRET);

//sdk facebook Facebook\FacebookJavaScriptLoginHelper;
function loginFromFbSession() {
	$helper = new FacebookJavaScriptLoginHelper();
	try {
		$session = $helper->getSession();
		//print_r($session);
	} catch ( FacebookRequestException $ex ) {
		// When Facebook returns an error
		//print_r($ex);
	} catch ( \Exception $ex ) {
		//print_r($ex);
		// When validation fails or other local issues
	}
	if ( $session ) {
		// Logged in
		try {
			$request  = new FacebookRequest( $session, 'GET', '/me' );
			$response = $request->execute();
			$user
			                     = $response->getGraphObject( Facebook\GraphUser::className() );
			$_POST['first_name'] = $user->getFirstName();
			$_POST['last_name']  = $user->getLastName();
			//$_POST['image'] инициализируется в яваскрипте
			$_POST['identity']   = $user->getId();
			$_POST['network']    = 'facebook.com';
			return true;
		} catch ( \Facebook\FacebookRequestExceptiontException $ex ) {
			echo "Exception occured, code: " . $ex->getCode();
			echo " with message: " . $ex->getMessage();
		}
	}else return false;
}
function loginUser(){
		if ( isset( $_COOKIE['up_key_fb'] ) ) {
			$userInfo = getUserByHash( $_COOKIE['up_key_fb'] );
			if ( $userInfo ) {
				$_POST['first_name'] = $userInfo['first_name'];
				$_POST['last_name']  = $userInfo['last_name'];
				$_POST['image']      = null;
				$_POST['network']    = "facebook.com";
				$_POST['identity']   = $userInfo['network_url'];
				addCommentFromPage();//добавляем коммент
			}
		} else {
			$res=loginFromFbSession();
			if($res) {
				addCommentFromPage($_POST);
				$str = getHashForUser($_POST['identity']);
				$life_time     = time() + ( 60 * 60 * 24 * 7 );
				$access_path   = "/";
				$access_domain = "comments.akson.by";
				setcookie( 'up_key_fb',$str , $life_time, $access_path,
					$access_domain );
				if($_COOKIE['up_key_vk']) setcookie('up_key_vk', $_COOKIE['up_key_vk'], time()-3600,  $access_path,
				$access_domain);
			}
		}
}

loginUser();
//вывод существующих комментариев
$comments=getCommentsFromPage();
foreach ($comments as $comment) {
  echo $comment;
}
?>