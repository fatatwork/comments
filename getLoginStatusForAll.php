<?php
require_once 'funcLib.php';
$networkPrefix = null;
if ( isset( $_COOKIE['up_key_vk'] ) ) {
	$networkPrefix    = 'vk';
	$networkPrefixUrl = "http://vk.com/id";
}
if ( isset( $_COOKIE['up_key_fb'] ) ) {
	$networkPrefix    = 'fb';
	$networkPrefixUrl = "http://www.facebook.com/app_scoped_user_id/";
}
if ( isset( $_COOKIE['up_key_gp'] ) ) {
	$networkPrefix    = 'gp';
	$networkPrefixUrl = "https://plus.google.com/u/0/";
}
if ( $networkPrefix != null ) {
	$userInfo = getUserByHash( $_COOKIE[ 'up_key_' . $networkPrefix ] );
	if ( $userInfo ) {
		$userInfo['user_link'] = $networkPrefixUrl . $userInfo['network_url'];
		echo json_encode( $userInfo );
	}
} else {
	echo "null";
}
?>