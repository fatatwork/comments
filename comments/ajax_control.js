var errorTimeout = 4000;
var sendBtnLocked;
var antiSpamTimeout = 10000;
var authorized;

var app_id = vk_api_add(); //Динамически добавляем скрипт API
fb_api_add();

//Module/////////

function getExistComments() {
	var params = "getComments=true";
	insertNewData(params, "../add-comment.php", "comment-list", "POST");
}

getExistComments(); //Получаем уже существующие комментарии
//Обрабатываем клик по кнопке
var firstValue;

//Social Networks Initializations

$(document).ready(function() {
	//Нужно переписать - изменился HTML
	$('#send_button').bind('click', function() {
		setTimeout("$('textarea').val('')", 100);
	});
	setTimeout(function() {
		VK.init({
			apiId: app_id
		});
		loadingInsert();
		VK.Auth.getLoginStatus(contentChangeVK);
	}, 1000);
});

function vk_api_add() {
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

function fb_api_add() {
	window.fbAsyncInit = function() {
		FB.init({
			appId: '403917006466762',
			xfbml: true,
			version: 'v2.3'
		});

	};

	(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) {
			return;
		}
		js = d.createElement(s);
		js.id = id;
		js.src = "//connect.facebook.net/ru_RU/sdk.js";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
}

//Controls/////////
$("#send_button").click(
	function() {
		var btn = this;
		if (authorized == true) {
			if (sendBtnLocked != true) {
				var minCommentLength = 7;

				/*Извлекаем текст комментария из текстового поля*/
				var textArea = document.getElementById("user_comment");
				var placeholder = document.getElementById("commentsPlaceHolder");
				var textOfComment = textArea.innerText;
				if (placeholder != undefined) {
					var holderText = placeholder.innerText;
					//Поиск текста из плейсхолдера - не считается комментарием
					if (textOfComment.indexOf(holderText) != -1) {
						textOfComment = undefined;
					}
				}
				if (textOfComment != undefined) {
					if (textOfComment.length >= minCommentLength) {

						////////////////////
						/*Параметры: пара = значение, отправляем комментарий*/
						btnLock(btn);
						var params = 'currentComment=' + textOfComment + '&pageUrl=' + window.location;
						insertNewData(params, "../add-comment.php", "comment-list", "POST", function(readyflag) {
							if (readyflag) {
								btnUnlock(btn);
							} else {
								textReplace(btn, "<span>Ошибка отправки</span>")
							}
						});
					} else {
						textReplace(btn, "<span>Слишком короткое сообщение.</span>");
					}
				} else {
					textReplace(btn, "<span>Вы ничего не написали</span>")
				}
			} else {
				textReplace(btn, "<span>Подождите " + antiSpamTimeout / 1000 + " секунд.</span>");
			}
		} else {
			textReplace(btn, "<span>Сначала Вам необходимо войти.</span>");
		}
	});

function vk_auth() {
	VK.init({
		apiId: app_id
	});

	function getCookie(name) {
		var matches = document.cookie.match(new RegExp(
			"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
		));
		return matches ? decodeURIComponent(matches[1]) : undefined;
	}
	//Отправляем строку с сессионными данными пользователя для проверки авторизации на стороне сервера
	loadingInsert();
	VK.Auth.login(function() { //Выводим попап
		VK.Auth.getLoginStatus(function(response) {
			if (response.session) {
				var params = "params=" + getCookie("vk_app_" + app_id);
				insertNewData(params, "vk_ajax_auth.php", null, "POST");
				contentChangeVK(response);
			}
		});
	});
}

//Слушаем кнопку, ждем нажатия
//ПЕРЕПИСАТЬ - должна быть вилка на разные логауты сетей
function vk_Logout() {
	event.preventDefault();
	if (app_id != undefined) {
		VK.init({
			apiId: app_id
		});
		loadingInsert();
		VK.Auth.getLoginStatus(
			function() {
				VK.Auth.logout(
					function() {
						var params = "logout=1";
						insertNewData(params, "logout.php", null, "POST");
						VK.Auth.getLoginStatus(contentChangeVK);
					}
				);
			}
		);
	}
}

//View/////////
function contentChangeVK(response) {

	if (response.session) { //Авторизованный пользователь
		var user_id = response.session.mid;
		VK.Api.call('users.get', {
				user_ids: user_id,
				fields: 'photo_50',
				name_case: 'nom'
			},
			function(ret) {
				if (ret.response) {
					var userLink = "http://vk.com/id" + ret.response[0].uid;
					var firstName = ret.response[0].first_name;
					var lastName = ret.response[0].last_name;
					var photo = ret.response[0].photo_50;
					//Вызываем отрисовку данных о пользователе
					contentAuthView(userLink, firstName, lastName, photo);
				}
			})

	} else { //Не авторизованный
		contentNotAuthView();
	}
}

///function contentChangeVK(response) {

function deleteAllChilds(parent) {
	var childLength = parent.childNodes.length;
	for (i = 0; i < childLength; ++i) {
		parent.removeChild(parent.childNodes[0]);
	}
}

function textReplace(object, newText) {
	var defaultObjectText = object.innerHTML;
	object.innerHTML = newText;
	$(object).addClass("send_button_denied");
	setTimeout(function() {
		$(object).removeClass("send_button_denied");
		object.innerHTML = defaultObjectText;
	}, errorTimeout);
}

function contentAuthView(userLink, first_name, last_name, photo) {
	var infoBlock = document.getElementById("user_info");
	//Формируем вывод данных о пользователе
	deleteAllChilds(infoBlock);
	var authText = document.createElement("div");
	infoBlock.appendChild(authText);
	authText.innerHTML = "<a href='" + userLink + "'>" + "<img id='avatar' src='" + photo + "'/></a><p>Вы вошли как: <a href='" +
		userLink + "'>" + first_name +
		" " + last_name +
		"</a></p><p><a id='vk_logout' onClick='vk_Logout()' href='#'>Выйти</a></p>";
	authorized = true;
}

function contentNotAuthView() {
	var infoBlock = document.getElementById("user_info");
	deleteAllChilds(infoBlock);
	var logoutText = document.createElement("div");
	logoutText.id = "Login";
	logoutText.innerHTML = "<p>Вы не авторизированы. Войдите через соц-сеть</p><a id='vk_auth' onClick='vk_auth()'><img src='../design/vk_icon.png'></a>"
	infoBlock.appendChild(logoutText);
	authorized = false;
}

function btnLock(btn) {
	sendBtnLocked = true; //Кнопка нажата, больше жать нельзя
	$(btn).context.children[0].style.visibility = "hidden";
	$(btn).addClass("send_button_loading");
}

function btnUnlock(btn) {
	$(btn).context.children[0].style.visibility = "visible";
	$(btn).removeClass("send_button_loading");
	$(btn).addClass("send_button_blocked");
	setTimeout(function() { //Выставляем таймаут для спамеров
		sendBtnLocked = false;
		$(btn).removeClass("send_button_blocked");
	}, antiSpamTimeout);
}

function delHolder() {
	var ob = document.getElementById("commentsPlaceHolder");
	if (ob != undefined) {
		var parent = ob.parentElement;
		parent.removeChild(ob);
	}
}

function loadingInsert() {
	//Добавляет индикатор загрузки на панель информации о пользователе
	var loadAnim = document.createElement("div");
	loadAnim.className = "send_button_loading loadAnim";
	var blockWithInfo = document.getElementById("user_info");
	blockWithInfo.appendChild(loadAnim);
}