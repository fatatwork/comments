<?
require_once '../funcLib.php';
require __DIR__ . '../fb-php-sdk-v4/autoload.php';
use Facebook\FacebookJavaScriptLoginHelper;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
define('FB_APP_ID', '403917006466762');
define('FB_APP_SECRET', '815900b1ab7d7a24a103464616c0badf');
define('FB_PHP_SDK_V4_DIR', '../fb-php-sdk-v4/src/Facebook/');
FacebookSession::setDefaultApplication(FB_APP_ID, FB_APP_SECRET);

//sdk facebook Facebook\FacebookJavaScriptLoginHelper;
$helper = new FacebookJavaScriptLoginHelper();
try {
	$session = $helper->getSession();
	//print_r($session);
} catch(FacebookRequestException $ex) {
	// When Facebook returns an error
	//print_r($ex);
} catch(\Exception $ex) {
	//print_r($ex);
	// When validation fails or other local issues
}
if ($session) {
	// Logged in
	try {
		$request     = new FacebookRequest( $session, 'GET', '/me' );
		$response    = $request->execute();
		$user = $response->getGraphObject(Facebook\GraphUser::className());
		$_POST['first_name']= $user->getFirstName();
		$_POST['last_name'] = $user->getLastName();
		$_POST['image']     = null;
		$_POST['identity']  = $user->getId();
		$_POST['network']   = 'facebook.com';
		//print_r($_POST);
	} catch(\Facebook\FacebookRequestExceptiontException $ex){
		echo "Exception occured, code: " . $e->getCode();
		echo " with message: " . $e->getMessage();
	}
}

//вывод существующих комментариев
?>