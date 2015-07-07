var errorTimeout = 4000;
var sendBtnLocked;
var antiSpamTimeout = 10000;
var authorized;
//айдишники проложений для социалок, эти айдишники будут фигурировать в названии кук
fbAppId = 403917006466762;
vkAppId = 4832378;
var photoFb; //глобальная переменная для фотки из фесбука
$(document).ready(function() {
    getExistComments(); //Получаем уже существующие комментарии
    //!!!!!!ЗОНА ОТЛАДКИ!!!!!!!!!!!!!!!

    //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    getLoginStatusForAll();
    //Social Networks Initializations
    //Нужно переписать - изменился HTML
    /*$('#send_button').bind('click', function() {
        setTimeout("$('user_comment').val('')", 100);
    });*/
    loadingInsert();
    initiateSocApi(socNetName);
});

function initiateSocApi(socID) {
    //Инициализация движков
    VK.init({
        apiId: vkAppId
    });
    //VK.Auth.getLoginStatus(contentChangeVK);
    FB.init({
        appId: fbAppId,
        xfbml: false,
        cookie: true,
        version: 'v2.3'
    });
    /*FB.getLoginStatus(function (response) {
        contentChangeFB(response);
    });*/
}

function getExistComments() {
    var params = 'pageUrl=' + window.location;
    insertNewData(params, "../outComments.php", "comment-list", "POST");
}

//Функция получает данные пользователя на основе универсальной куки и выводит их на страницу
function getLoginStatusForAll() {
    var userInfo;
    insertNewData("", "../getLoginStatusForAll.php", null, "POST", function(ret) {
        if (ret != undefined) {
            if (ret == "null")
                contentNotAuthView();
            else {
                userInfo = JSON.parse(ret);
                contentAuthView(userInfo["user_link"], userInfo["first_name"], userInfo["last_name"], userInfo["image"]);
            }
        }
    });
}

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
                        var params = 'currentComment=' + textOfComment + '&pageUrl=' + window.location + '&image=' + photoFb;
                        insertNewData(params, "../addComment.php", "comment-list", "POST", function(readyflag) {
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
    initiateSocApi("vk");
    VK.Auth.login(function() { //Выводим попап
        VK.Auth.getLoginStatus(function(response) {
            if (response.session) {
                insertNewData(null, "../setVkCookie.php", null, "POST", function() {
                    getLoginStatusForAll();
                });
            }
        });
    });
}

function fb_auth() {
    //Отправляем строку с сессионными данными пользователя для проверки авторизации на стороне сервера
    loadingInsert();
    initiateSocApi("fb");
    FB.login(function(response) { //Popup
        FB.getLoginStatus(function(response) { //Проверяем статус логина
            if (response.status === 'connected') { //Если авторизовался
                insertNewData(null, "../setFbCookie.php", null, "POST", function() {
                    getLoginStatusForAll();
                });
            }
        });
    });
}

//Слушаем кнопку, ждем нажатия
function logoutFunc() {
    event.preventDefault();
    loadingInsert();
    insertNewData(null, "../logout.php", null, "POST", function(ret) {
        FB.logout();
            if (ret != undefined)
                if (ret == "logout")
                    contentNotAuthView();
    });
}

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
        "</a></p><p><a id='vk_logout' onClick='logoutFunc()' href='#'>Выйти</a></p>";
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