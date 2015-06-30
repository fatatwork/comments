<?php
require_once 'funcLib.php';
if ( isset( $_COOKIE['up_key_vk'] ) ) {
		$userInfo = getUserByHash( $_COOKIE['up_key_vk'] );
		if ( $userInfo ) {
			$_POST['first_name'] = $userInfo['first_name'];
			$_POST['last_name']  = $userInfo['last_name'];
			$_POST['image']      = $userInfo['image'];
			$_POST['network']    = "vk.com";
			$_POST['identity']   = $userInfo['network_url'];
			$networkPrefix="http://vk.com/id";
		}
	}

if ( isset( $_COOKIE['up_key_fb'] ) ) {
			$userInfo = getUserByHash( $_COOKIE['up_key_fb'] );
			if ( $userInfo ) {
				$_POST['first_name'] = $userInfo['first_name'];
				$_POST['last_name']  = $userInfo['last_name'];
				$_POST['image']      = null;
				$_POST['network']    = "facebook.com";
				$_POST['identity']   = $userInfo['network_url'];
				$networkPrefix="http://www.facebook.com/";
			}
		}
if(isset($_COOKIE['up_key_fb']) || isset($_COOKIE['up_key_vk'])){
	echo "<div id='user_info'><div>
				<a href='".$networkPrefix.$POST['identity']."'> <img id='avatar' src='".$POST['image']."'> </a>
				<p>Вы вошли как:<a href='".$networkPrefix.$POST['identity']."'>".$_POST['first_name']." ".$_POST['last_name']."</a></p>
				<p><a id='vk_logout' onclick='vk_Logout()' href='#''>Выйти</a></p>
				</div></div>";
	} else {
	echo "<div id='user_info'>
				<div id='Login'>
					<p>Вы не авторизированы. Войдите через соц-сеть</p>
					<a id='vk_auth' onclick='vk_auth()''><img src='../design/vk_icon.png'></a>
					<a id='fb_auth' onclick='fb_auth()'><img src='../design/fb_icon.png'></a>
				</div>
		  </div>";	
	}
?>