<?php
require_once 'app_config.php';
//Выход
if ( $_COOKIE['up_key_vk'] ) {
	setcookie( 'up_key_vk', $_COOKIE['up_key_vk'], time() - 3600, ACCESS_PATH,
		ACCESS_DOMAIN );
}
if ( $_COOKIE['up_key_fb'] ) {
	setcookie( 'up_key_fb', $_COOKIE['up_key_fb'], time() - 3600, ACCESS_PATH,
		ACCESS_DOMAIN );
}
if ( $_COOKIE['up_key_gp'] ) {
	setcookie( 'up_key_gp', $_COOKIE['up_key_gp'], time() - 3600, ACCESS_PATH,
		ACCESS_DOMAIN );
}
if ( $_COOKIE[ 'vk_app_' . $vkAppId ] ) {
	setcookie( 'vk_app_' . $vkAppId, $_COOKIE[ 'vk_app_' . $vkAppId ],
		time() - 3600, ACCESS_PATH, ACCESS_DOMAIN );
}
if ( $_COOKIE[ 'fbsr_' . $fbAppId ] ) {
	setcookie( 'fbsr_' . $fbAppId, $_COOKIE[ 'fbsr_' . $fbAppId ],
		time() - 3600 );
}
echo "logout";

?>