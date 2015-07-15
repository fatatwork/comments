<?php
require_once 'funcLib.php';

$app_id = "1146120960";
$app_public = "CBAMGBFFEBABABABA";
$app_secret = "02512D53BCA1B025A431A034";
$fields = "FIRST_NAME,LAST_NAME,UID,PIC_1";
$redirect_path = "/setOkCookie.php";
if(isset($_GET['error'])){
	echo "null";
}
else{
	if(isset($_GET['code'])){
		$code = $_GET('code');
		$tokenInfo = json_decode( file_get_contents( 'https://api.odnoklassniki.ru/oauth/token.do?code=' . $code . 
			'&client_id=' . $app_key . 
			'&client_secret=' . $app_secret . 
			'&redirect_uri=' . $redirect_path .
			'&grant_type=authorization_code'),
		true);
		if(isset($tokenInfo['error']){
			echo "null";
		}
		else{
			if(isset($tokenInfo['access_token']){
				$access_token = $tokenInfo['access_token'];
				//Создаем подпись
				$signature = strtolower(md5('application_key=' . $app_public .
				'fields='. $fields . 
				'method=users.getCurrentUser'. $app_secret));
				
			//Получаем информацию о пользователе
			$userInfoFromServer = json_decode( file_get_contents( 'http://api.ok.ru/fb.do?application_key=' . $app_public .
			'&method=users.getCurrentUser'.
			'&fields='. $fields . 
			'&sig=' . $signature), true); 
			$userInfo = array();
			print_r($userInfoFromServer);
			/*$userInfo['first_name'] = $userInfo['first_name'];
			$userInfo['last_name']  = $userInfo['last_name'];
			$userInfo['image']      = $userInfo['photo_50'];
			$userInfo['network']    = "vk.com";
			$userInfo['identity']   = $userInfo['uid'];*/
			}
		}
	}
}

?>