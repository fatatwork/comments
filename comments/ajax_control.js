var errorTimeout = 4000;
var sendBtnLocked;
var antiSpamTimeout = 10000;
var authorized;
//айдишники проложений для социалок, эти айдишники будут фигурировать в названии кук
var fbAppId= 403917006466762;
var vkAppId = 4832378;
var photoFb;//глобальная переменная для фотки из фесбука



//Module/////////

function getExistComments() {
	var params='pageUrl='+window.location;
	insertNewData(params, "../outComments.php", "comment-list", "POST");
}
function getCookie(name) {
	var matches = document.cookie.match(new RegExp(
		"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
	));
	return matches ? decodeURIComponent(matches[1]) : undefined;
}

function getLoginStatusForAll(){
	insertNewData("", "../getLoginStatusForAll.php", "user_info", "POST");
}
getExistComments(); //Получаем уже существующие комментарии
getLoginStatusForAll();


//Обрабатываем клик по кнопке
var firstValue;

//Social Networks Initializations

$(document).ready(function() {
	//Нужно переписать - изменился HTML
	$('#send_button').bind('click', function() {
		setTimeout("$('user_comment').val('')", 100);
	});
	//Загрузчик скриптов
	yepnope({
		test: vk_api_add.init() && fb_api_add.init(),
		complete: function(url, result, key){
			VK.init({
				apiId: vkAppId
			});
			FB.init({
				appId: fbAppId,
				xfbml: false,
				cookie: true,
				version: 'v2.3'
			});
			loadingInsert();
			VK.Auth.getLoginStatus(contentChangeVK);
			FB.getLoginStatus(function(response) {
				contentChangeFB(response);
			});
		}
	})
});

var vk_api_add = {
	init: function(){
		setTimeout(function() {
			var el = document.createElement("script");
			el.type = "text/javascript";
			el.src = "//vk.com/js/api/openapi.js";
			el.async = true;
			document.getElementsByTagName("head")[0].appendChild(el);
			script_added = true;
			return el.src;
		}, 0);
	}
}

var fb_api_add = {
	init: function(){
		(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) {
				return;
			}
			js = d.createElement(s);
			js.id = id;
			js.src = "//connect.facebook.net/ru_RU/sdk.js";
			fjs.parentNode.insertBefore(js, fjs);
			return js.src;
		}(document, 'script', 'facebook-jssdk'));
	}
}

//Controls/////////
//надо сделать переменную для проверки того в какой социальной сети я залогинен
$("#send_button").click(
	function() {
		var btn = this;
		if (authorized == true) {
			if (sendBtnLocked != true) {
				var minCommentLength = 4;

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
						/*Параметры: пара = значение, отправляем комментарий, так же в яваскриппте проперяем какие куки уже есть на комп
						и в зависмости от того для какой соцсетки - выбираем скрипт для отправки комментария*/
						btnLock(btn);
						var addCommentsScript=undefined;
                        var vkCookie=getCookie("vk_app_"+vkAppId);
                        var fbCookie=getCookie("fbsr_"+fbAppId);
                        var ourVkCookie=getCookie("up_key_vk");
                        var ourFbCookie=getCookie("up_key_fb");

                        if(vkCookie!=undefined || ourVkCookie) addCommentsScript="../addVkComments.php";
                        if(fbCookie!=undefined || ourFbCookie) addCommentsScript="../addFbComments.php";

						var params = 'currentComment=' + textOfComment + '&pageUrl=' + window.location+'&image='+photoFb;
						insertNewData(params, addCommentsScript, "comment-list", "POST", function(readyflag) {
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
	//Отправляем строку с сессионными данными пользователя для проверки авторизации на стороне сервера
	loadingInsert();
	VK.Auth.login(function() { //Выводим попап
		VK.Auth.getLoginStatus(function(response) {
			if (response.session) {
				contentChangeVK(response);
			}
		});
	});
}

function fb_auth() {
	//Отправляем строку с сессионными данными пользователя для проверки авторизации на стороне сервера
	loadingInsert();
	FB.login(function(response) { //Popup
		FB.getLoginStatus(function(response) { //Проверяем статус логина
			if (response.status === 'connected') { //Если авторизовался
                contentChangeFB(response); //Меняем внешний вид
			}
		});
	}, {
		scope: 'user_about_me'
	});
}

//Слушаем кнопку, ждем нажатия
function vk_Logout() {
	event.preventDefault();
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

function fb_Logout() {
	event.preventDefault();
	loadingInsert();
	FB.logout(function(response) {
		FB.getLoginStatus(function(response) { //Проверяем статус логина
			if (response.status !== 'connected') {
				contentChangeFB()
				var params = "logout=1";
				insertNewData(params, "logout.php", null, "POST");
			}
		});
	});
}

//View/////////
function contentChangeVK(response) {

	if (response.session) { //Авторизованный пользователь
		var user_id = response.session.mid;
		VK.Api.call('users.get', { //Запрашиваем данные пользователя
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
					photoFb=photo;
					//Вызываем отрисовку данных о пользователе
					contentAuthView(userLink, firstName, lastName, photo, "vk");
				}
			})

	} else { //Не авторизованный
		contentNotAuthView();
	}
}

function contentChangeFB(response) {
	if (response) {
		if (response.status === 'connected') {
			// Logged into your app and Facebook.
			FB.api('/me', function(ret) { //Запрашиваем данные пользователя
				var userLink = ret["link"];
				var firstName = ret["first_name"];
				var lastName = ret["last_name"];
				//Отдельный запрос для получения фотографии
				FB.api('/me/picture', photoGetWrapper(userLink, firstName, lastName));
				function photoGetWrapper(userLink, firstName, lastName) {
					//Замыкание
					return function(ret) {
						var photo = ret.data.url;
						contentAuthView(userLink, firstName, lastName, photo, "fb");
					}
				}
				///
				
			});
		}
	} else {
		// The person is not logged into Facebook, so we're not sure if
		// they are logged into this app or not.
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

function contentAuthView(userLink, first_name, last_name, photo, socNetID) {
	var infoBlock = document.getElementById("user_info");
	//Формируем вывод данных о пользователе
	deleteAllChilds(infoBlock);
	var authText = document.createElement("div");
	infoBlock.appendChild(authText);
	authText.innerHTML = "<a href='" + userLink + "'>" + "<img id='avatar' src='" + photo + "'/></a><p>Вы вошли как: <a href='" +
		userLink + "'>" + first_name +
		" " + last_name +
		"</a></p><p><a id='vk_logout' onClick='" + socNetID + "_Logout()' href='#'>Выйти</a></p>";
	authorized = true;
}

function contentNotAuthView() {
	var infoBlock = document.getElementById("user_info");
	deleteAllChilds(infoBlock);
	var logoutText = document.createElement("div");
	logoutText.id = "Login";
	logoutText.innerHTML = "<p>Вы не авторизированы. Войдите через соц-сеть</p><a id='vk_auth' onClick='vk_auth()'><img src='../design/vk_icon.png'></a><a id='fb_auth' onClick='fb_auth()'><img src='../design/fb_icon.png'></a>"
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



function testAPI() {
	console.log('Welcome!  Fetching your information.... ');
	FB.api('/me', function(response) {
		console.log('Successful login for: ' + response.name);
		document.getElementById('status').innerHTML =
			'Thanks for logging in, ' + response.name + '!';
	});
}