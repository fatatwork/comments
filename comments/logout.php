<?php
session_start();
if($_POST['logout'] == '1'){ //Выход
	if(isset($_COOKIE['first_name'])){
		$first_name    = $_COOKIE['first_name'];
		$last_name     = $_COOKIE['last_name'];
		$network       = $_COOKIE['network'];
		$identity      = $_COOKIE['identity'];
		$page_adress   = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$life_time     = time() - 2592000; //Для удаления кук устанавливаем время в прошлом
		$access_path   = "/";
		$access_domain = "comments.akson.by";
		setcookie( 'first_name', $first_name, $life_time, $access_path, $access_domain );
		setcookie( 'last_name', $last_name, $life_time, $access_path, $access_domain );
		setcookie( 'network', $network, $life_time, $access_path, $access_domain );
		setcookie( 'identity', $identity, $life_time, $access_path, $access_domain );
		setcookie( 'page_adress', $page_adress, $life_time, $access_path, $access_domain );
	}
	if(session_id() != "" || isset($_COOKIE[session_name()])){
		unset($_SESSION['first_name']);
		unset($_SESSION['last_name']);
		unset($_SESSION['page_adress']);
		setcookie(session_name(), '', $life_time, $access_path, $access_domain);
	}
	$_GET['logout'] = 0;
	echo "<div id='Login'>
			<p>Вы не авторизированы. Войдите через соц-сеть</p><br />
			<a id='vk_auth' onClick='vk_auth()'><img src='../design/vk_icon.png'></a>
			</div>";
	/*$header_query = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];//Формируем URL
	header("Location: http://".$_SESSION['page_adress']); //После удаления данных авторизации перенаправляем на исходную страницу
	exit;*/
}
?>