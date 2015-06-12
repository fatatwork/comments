	sucessful = false;
	errorDelay = 10000;
	ajaxReturn = null;

	function insertNewData(params, php_script_path, targetHTMLid, method){
		sucessful = false;
		request = new ajaxRequest(); /*Создаем новый обьект запроса (функция снизу)*/
		request.open(method, php_script_path, true); /*Настраиваем обьект на создаение post запроса по адресу файла php сценария. true - указывает на включение асинхронного режима*/
		request.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); 
		/*Отправляем http заголовки, для того чтобы сервер знал о поступлении POST запроса*/
		request.onreadystatechange = function(){ /*Указывает на CALLBACK функцию, которая должна вызываться при каждом изменении свойства readyState*/
			if (this.readyState == 4) {
				if(this.status == 200){
					clearTimeout(timeoutHandle);
					if(this.responseText != null || this.responseText == true){
						if(targetHTMLid != null){ //Если есть необходимость делать вставку в документ
							if(this.responseText !== ""){
								document.getElementById(targetHTMLid).innerHTML = this.responseText;
							}
							else{
								sucessful = false;
								alert("Ошибка AJAX: Нет данных.");
							}
						}
						else{
							ajaxReturn = this.responseText;
						}
						sucessful = true;
					}
					else{
						alert("Ошибка AJAX: Данные не получены");
					}
				}
				else{
					clearTimeout(timeoutHandle);
					alert("Ошибка AJAX: " + this.statusText);
				}
			};
		}
		request.send(params); /*Собственно отправка запроса*/
		var timeoutHandle = setTimeout( function(){ /*Таймаут на соединение*/
	    	request.abort(); errorHandler("Время ожидания вышло. Проверьте соединение и попробуйте ещё раз.") 
	    	}, errorDelay);
	}


	function ajaxRequest(){
		try{
			var request = new XMLHttpRequest()
		}
		catch(e1){
			try{
				request = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch(e2){
				try{
					request = new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch(e3){
					request = false;
				}
			}
		}
		return request;
	}

	function errorHandler(errorText){
		alert("Ошибка AJAX: " + errorText);
	}

	function adminGetExistArticles(){
		insertNewData("", "admin_get_articles.php", "list", "POST");
	}

	function banPressed(comment_id, user_id, param){
		var form = $("#form_" + comment_id);
		var radios = $("#form_" + comment_id + " input[name="+param+"]");
		switch(param){
			case 'ban': for(var i=0; i != radios.lenght; ++i){
									if(radios[i].checked == true){
										insertNewData(param+'='+radios[i].value+'&'+'user_id='+user_id,'admin_get_comments.php', 'list', 'POST');
										break;
									}
								}
				break;
			case 'delete': insertNewData(param+'='+comment_id,'admin_get_comments.php', 'list', 'POST');
				break;
			case 'unban_user': insertNewData(param+'='+user_id,'admin_get_comments.php', 'list', 'POST');
				break;
		}	 
 }