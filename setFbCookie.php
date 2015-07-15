<?php
require_once 'funcLib.php';
require __DIR__ . '/fb-php-sdk-v4/autoload.php';
use Facebook\FacebookJavaScriptLoginHelper;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;

define( 'FB_APP_ID', '403917006466762' );
define( 'FB_APP_SECRET', '815900b1ab7d7a24a103464616c0badf' );
define( 'FB_PHP_SDK_V4_DIR', '/fb-php-sdk-v4/src/Facebook/' );
FacebookSession::setDefaultApplication( FB_APP_ID, FB_APP_SECRET );

//sdk facebook Facebook\FacebookJavaScriptLoginHelper;
function loginFromFbSession() {
	$username = array();
	$helper   = new FacebookJavaScriptLoginHelper();
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
			$username['first_name'] = $user->getFirstName();
			$username['last_name']  = $user->getLastName();
			//$username['image'] инициализируется в яваскрипте
			$username['identity'] = $user->getId();
			$username['network']  = 'facebook.com';

			return $username;
		} catch ( \Facebook\FacebookRequestExceptiontException $ex ) {
			echo "Exception occured, code: " . $ex->getCode();
			echo " with message: " . $ex->getMessage();
		}
	} else {
		return false;
	}
}

$userName = loginFromFbSession();
if(sizeof($userName)>0) setUserCookie( $userName, 'up_key_fb' );


?>