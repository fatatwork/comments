<?php
require_once '../app_config.php';
if(true){ //Выход
	echo "logout";
	$access_path   = "/";
	$access_domain = "comments.akson.by";
	if($_COOKIE['up_key_vk']) setcookie('up_key_vk', $_COOKIE['up_key_vk'], time()-3600,  $access_path,
				$access_domain);
	if($_COOKIE['up_key_fb']) setcookie('up_key_fb', $_COOKIE['up_key_fb'], time()-3600,  $access_path,
				$access_domain);
	if($_COOKIE['vk_app'.$vkAppId]) setcookie('vk_app'.$vkAppId, $_COOKIE['vk_app'.$vkAppId], time()-3600);
	if($_COOKIE['fbsr_'.$fbAppId]) setcookie('fbsr_'.$fbAppId, $_COOKIE['fbsr_'.$fbAppId], time()-3600);
}
?>