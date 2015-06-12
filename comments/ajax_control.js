sucessful = false;
errorDelay = 10000;
var blockedSendBtn = false;
var defaultSendBtnText = document.getElementById("send_button").innerHTML;
var approve = true; //Разрешение на повторное нажатие кнопки отправки комментария
var sentCheckInterval = 1000;
var antiSpamTimeout = 10000;
var messageOnce = false; //Показывает выводилось ли уже сообщение о частой отправке


function getExistComments() {
	var params = "getComments=true";
	insertNewData(params, "../add-comment.php", "comment-list", "POST");
}

function errorHandler(errorText) {
	alert("Ошибка AJAX: " + errorText);
}

getExistComments(); //Получаем уже существующие комментарии
//Обрабатываем клик по кнопке
var firstValue;

$("#send_button").click(
	function() {
		var btn = this;
		if (approve == true && blockedSendBtn == false) {
			approve = false; //Кнопка нажата, больше жать нельзя
			messageOnce = false; //Можно снова выводить сообщения
			$(btn).context.children[0].style.visibility = "hidden";
			$(btn).addClass("send_button_loading");
			/*Извлекаем текст комментария из текстового поля*/
			var textOfComment = $('textarea[name=user_comment]')[0].value;
			var params = "currentComment=" + textOfComment; /*Параметры: пара = значение*/
			insertNewData(params, "../add-comment.php", "comment-list", "POST");
			var intervalHandle = setInterval(function() { /*Таймаут на соединение*/
				if (sucessful == true) {
					$(btn).context.children[0].style.visibility = "visible";
					$(btn).removeClass("send_button_loading");
					$(btn).addClass("send_button_blocked");
					clearInterval(intervalHandle);
					setTimeout(function() { //Выставляем таймаут для спамеров, которые не знают js или надеятся на то, что нет проверки на сервере
						$(btn).context.innerHTML = defaultSendBtnText;
						approve = true;
						$(btn).removeClass("send_button_blocked");
					}, antiSpamTimeout);
				}
			}, sentCheckInterval);
		} else {
			//выводим поясняющую надпись
			if (messageOnce != true) {
				if (approve == false) {
					$(btn).context.innerHTML = "<span>Подождите " + antiSpamTimeout / 1000 + " секунд.</span>";
				}
				else{
					if(blockedSendBtn == true){
						$(btn).context.innerHTML = "<span>Сначала Вам необходимо войти.</span>";
					}
				}
				messageOnce = true;
			}
		}
	});

var hSendBtnBlockInterval = setInterval(function() {
	//Функция контролирует состояние и внешний вид кнопки отправки в зависимости от состояния формы
	var auth_btn = document.getElementById("vk_auth");
	var logout_btn = document.getElementById("vk_logout");
	var send_button = document.getElementById("send_button");

	if (approve == true && messageOnce == false) { 
		send_button.innerHTML = defaultSendBtnText;
	}

	if (auth_btn != null && blockedSendBtn == false) {
		blockedSendBtn = true;
		send_button.className += ' send_button_blocked'
	} 
	else {
		if (logout_btn != null && blockedSendBtn == true) {
			send_button.innerHTML = defaultSendBtnText;
			var els = Array.prototype.slice.call( //Удаляем класс скрытности
				document.getElementsByClassName("send_button_blocked")
			);
			for (var i = 0, l = els.length; i < l; i++) {
				var el = els[i];
				el.className = el.className.replace(
					new RegExp('(^|\\s+)' + "send_button_blocked" + '(\\s+|$)', 'g'),
					'' //Заменяем на пустоту
				);
			}
			blockedSendBtn = false;
		}
	}
}, sentCheckInterval);

function vk_script_add() {
	app_id = 4832378;
	var script_added = false;
	setTimeout(function() {
		var el = document.createElement("script");
		el.type = "text/javascript";
		el.src = "//vk.com/js/api/openapi.js";
		el.async = true;
		document.getElementsByTagName("head")[0].appendChild(el);
		script_added = true;
	}, 0);
	return app_id;
}

/*var hAuthBtnInterval = setInterval(function() { 
if(document.getElementById("vk_auth") != null){
clearInterval(hAuthBtnInterval);
document.getElementById("vk_auth").addEventListener("click",*/
function vk_auth() {
		function getCookie(name) {
			var matches = document.cookie.match(new RegExp(
				"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
			));
			return matches ? decodeURIComponent(matches[1]) : undefined;
		}

		var app_id = vk_script_add(); //Динамически добавляем скрипт API

		var hInterval = setInterval(function() {
			if (app_id != undefined) {
				clearInterval(hInterval);
				VK.init({
					apiId: app_id
				});

				function authInfo(response) {
					if (response.session) { //Авторизованный пользователь
						//Отправляем строку с сессионными данными пользователя для проверки авторизации
						var params = "params=" + getCookie("vk_app_" + app_id);
						insertNewData(params, "vk_ajax_auth.php", "user_info", "POST");
					} else { //Не авторизованный
					}
				}
				VK.Auth.login(function() { //Выводим попап
					VK.Auth.getLoginStatus(authInfo);
				});
			}
		}, sentCheckInterval);
	}
	/*);
	}
	});*/

//$('#vk_logout').click(
/*var hInterval = setInterval(function() { //Проверяем - появилась ли кнопка, для которой добавляем листенер
	if (document.getElementsByClassName("vk_logout")[0] != null) {
		clearInterval(hInterval);
		document.getElementsByClassName("vk_logout")[0].addEventListener("click",*/
//Слушаем кнопку, ждем нажатия
function vk_logout() {
		event.preventDefault();
		var app_id = vk_script_add();
		var hInterval = setInterval(function() {
			if (app_id != undefined) {
				clearInterval(hInterval);
				VK.init({
					apiId: app_id
				});
				VK.Auth.getLoginStatus(
					function() {
						VK.Auth.logout(
							function() {
								var params = "logout=1";
								insertNewData(params, "logout.php", "user_info", "POST");
								messageOnce = false;
							}
						);
					}
				);
			}
		}, sentCheckInterval);
	}
	/*		);
		}
	}, sentCheckInterval);*/