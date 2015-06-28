<?php
session_start();
header( 'Content-type: text/html; charset=utf-8' );
require_once '../funcLib.php';

//адрес странички с которой перенаправляемься на авторизацию
$_SESSION['page_url'] = "http://".$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<HTML xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
	<TITLE>Комплексный государственный экзамен</TITLE>
	<META NAME="DESCRIPTION"
	      CONTENT="Белорусский государственный медицинский университет - ведущее высшее медицинское учебное учреждение Республики Беларусь, имеющее заслуженный международный авторитет и признание. На кафедрах университета обучаются 7046 студентов, 68 аспирантов и 286 клинических ординаторов, в том числе 808 иностранных студентов и 74 иностранных клинических ординатора.">
	<META NAME="KEYWORDS"
	      CONTENT="Общеуниверситетские, БГМУ, белорусский государственный медицинский университет, университет, вуз, минск, образование, высшее образование, цт, централизованное тестирование, абитуриент, студент, наука, аспирантура, медицинский, врач">
	<META NAME="ROBOTS" CONTENT="all">
	<META http-equiv="X-UA-Compatible" content="IE=9">

	<META HTTP-EQUIV="Content-Type" CONTENT="text/html; CHARSET=windows-1251">
	<link
		href="http://fonts.googleapis.com/css?family=Open+Sans+Condensed:700&subset=cyrillic-ext"
		rel="stylesheet" type="text/css">

	<script type="text/javascript"
	        src="http://www.bsmu.by/scripts/jquery.min.js"></script>
	<script type="text/javascript"
	        src="http://www.bsmu.by/scripts/upper.js"></script>
	<link rel="stylesheet" type="text/css"
	      href="http://www.bsmu.by/style_main_ru.css">
	<link rel="stylesheet" TYPE="text/css"
	      HREF="http://www.bsmu.by/style_ru.css">
	<link href="/design/comments_style.css" rel="stylesheet" type="text/css">


	<link href="http://www.bsmu.by/rss/rss.xml" rel="alternate"
	      type="application/atom+xml" title="Atom 1.0"/>
	<link REL="SHORTCUT ICON" HREF="/favicon.ico">

	<!--[if lt IE 9]>


	<link rel="stylesheet" type="text/css" href="/style_IE.css"
	      media="all"></link>

	<![endif]-->
	<meta name="viewport"
	      content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

</HEAD>
<BODY>

<div id="naviP">
	<div class="defaultP" id="menuP">
		<ul>
			<li><a href="http://www.bsmu.by/">Главная</a></li>
			<li><a href="http://www.bsmu.by/page/6/44/">Университет</a></li>
			<li><a href="http://www.bsmu.by/page/4/33/">Абитуриент</a></li>
			<li><a href="http://www.bsmu.by/page/3/32/">Студент</a></li>
			<li><a href="http://www.bsmu.by/page/5/40/">Выпускник</a></li>
			<li><a href="http://www.bsmu.by/page/8/64/">Врач</a></li>
			<li><a href="http://www.bsmu.by/qa/">Вопрос/Ответ</a></li>
		</ul>
	</div>
</div>
<div id="Header_cont">
	<div id="Logo"><a title="Главная страница" href="http://www.bsmu.by/"></a>
	</div>
	<div id="Search">
		<div id="l_box1"><a class="op_box1">Languages</a><span
				style="color: #a5a5a5;"> &nbsp;&rarr;&nbsp;Rus</span>

			<div id="underlay1"></div>
			<div id="lightbox1"><br/><a class="cl_box1" href="#">x</a>

				<div id="sl"><span style="color: #c5c5c5;"><strong>Supporting
							languages</strong></span></div>
				<div id="Lang">&nbsp;&nbsp;<a href="http://eng.bsmu.by/">Eng</a>&nbsp;
					<a href="http://spa.bsmu.by/">Spa</a>&nbsp; <a
						href="http://deu.bsmu.by/">Deu</a>&nbsp; <a
						href="http://ara.bsmu.by/">Ara</a>&nbsp; <a
						href="http://fra.bsmu.by/">Fra</a>&nbsp; <a
						href="http://tuk.bsmu.by/">Tuk</a>&nbsp; <a
						href="http://fas.bsmu.by/">Fas</a>&nbsp; <a
						href="http://aze.bsmu.by/">Aze</a>&nbsp; <a
						href="http://chi.bsmu.by/">Chi</a>&nbsp; <a
						href="http://heb.bsmu.by/">Heb</a>&nbsp;</div>
			</div>
		</div>
		<FORM NAME="frmsearch" CLASS="search" METHOD=GET
		      ACTION="http://www.bsmu.by/search/">
			<div><input class=search_input name=words maxlength=120
			            value=''><input type=image class=search_image
			                            title="Найти" alt="Найти"
			                            src="http://www.bsmu.by/design/search_ru.gif">
			</div>
		</FORM>
		<a href="http://www.bsmu.by/map/">Карта сайта</a></div>
</div>
<div class="niled">&nbsp;</div>
<div class="Collage">
	<div class="Collage_In"><img
			src="http://www.bsmu.by/design/kollazh_empty.gif" alt=""/></div>
</div>
<div class="Content_cont">
	<script
		src="http://www.bsmu.by/scripts/jquery-ui-1.8.18.custom.min.js"></script>
	<script
		src="http://www.bsmu.by/scripts/jquery.smooth-scroll.min.js"></script>
	<script src="http://www.bsmu.by/scripts/photo.js"></script>
	<div class="MenuMainIn">
		<h1>Новости</h1>

		<div class="news_headings"><a
				href="http://www.bsmu.by/allarticles/rubric1/">Общеуниверситетские</a>
			<a href="http://www.bsmu.by/allarticles/rubric2/">Международные</a>
			<a href="http://www.bsmu.by/allarticles/rubric3/">Учебные</a> <a
				href="http://www.bsmu.by/allarticles/rubric4/">Воспитательные</a>
			<a class="LentaRSS" href="http://www.bsmu.by/rss/rss.xml">RSS</a>
		</div>
		<div class="niled">&nbsp;</div>
	</div>
	<div class="OtherPages OtherPagesQNP">
		<p>

		<DIV class=path><font size="1" color="#a50000">&rarr;</font> <a
				href="http://www.bsmu.by">Главная</a> <font size="1"
		                                                    color="#a50000">&rarr;</font>
			<a href="http://www.bsmu.by/allarticles/">Новости</a> <font size="1"
			                                                            color="#a50000">&rarr;</font>
			<a href="http://www.bsmu.by/allarticles/rubric1/">Общеуниверситетские</a>
		</DIV>
		</p>
		<div class="NewsContent"><span
				style="font-family: helvetica; color: #747474; font-size: 30px;">11</span><span
				style="font-family: helvetica; font-size: 11px; color: #747474;"> июня 2015 г.</span>

			<h1>Комплексный государственный экзамен для студентов
				стоматологического факультета и медицинского факультета
				иностранных учащихся.</h1>

			<p><img src="http://www.bsmu.by/ImgForArticles/201506111629231.jpg"
			        alt="Комплексный государственный экзамен"></p>

			<p>

			<p>09.06-10.06.2015 для студентов пятого курса стоматологического
				факультета и выпускников-стоматологов медицинского факультета
				иностранных учащихся прошел комплексный государственный экзамен
				по терапевтической стоматологии. Экзамен принимали 5
				экзаменационных комиссий, которые состояли из ППС кафедр
				стоматологического факультета и представителей практического
				здравоохранения. Экзамен проходил в рабочей дружественной
				обстановке.</p>

			<p>
				<img
					src="http://www.bsmu.by/images/news/2015/6_2015/2_110615.jpg"
					alt="" width="600" height="400"/></p>

			<p>Экзамен посетил ректор университета А.В. Сикорский, который с
				интересом слушал ответы выпускников, а также принял участие в
				обсуждении некоторых спорных вопросов.</p>

			<p>
				<img
					src="http://www.bsmu.by/images/news/2015/6_2015/1_110615.jpg"
					alt="" width="600" height="400"/></p>

			<p>Все выпускники показали хорошие знания и получили положительные
				оценки.</p></p>
			<br/>
			<script charset="utf-8" src="http://yandex.st/share/share.js"
			        type="text/javascript"></script>
			<h3>&nbsp;Поделитесь</h3>

			<div data-yasharel10n="ru" data-yasharetype="none"
			     data-yasharequickservices="facebook,twitter,vkontakte,odnoklassniki,moimir,lj,gplus,yaru,friendfeed,moikrug"
			     class="yashare-auto-init"></div>
			<!-- Модуль комментариев -->
			<div id="comments_module">
				<div id="user_info"></div>
					<form class="comments" action="#">
						<div class="comment-send-area">
							<div id="user_comment" onClick="delHolder()" role="textbox" contenteditable="true" data-role="editable" aria-multiline="true">
							<p><span id="commentsPlaceHolder">Поделитесь своим мнением...</span></p></div>
						</div>
					</form>		
				<a id="send_button"><span>Оставить сообщение</span></a>
				
				<div id="comment-list"></div>
			</div>
			<script charset="utf-8" src="http://yandex.st/share/share.js"
			        type="text/javascript"></script>
		</div>
	</div>
	<div class="AnonsOther">
		<p>

		<div class="AnonsArtRubric">
			<div class="In"><a title="Прочитать новость"
			                   href="http://www.bsmu.by/allarticles/rubric1/article1165/"><img
						src="http://www.bsmu.by/ImgForArticles/s_201506121640201.jpg"
						alt="Экскурсия в анатомический музей БГМУ"></a></div>
			<div class="InText">
				<div><span style="font-size: 18px;">12</span> июня</div>
				<a title="Прочитать новость"
				   href="http://www.bsmu.by/allarticles/rubric1/article1165/">Экскурсия
					в анатомический музей БГМУ</a><br/>для учащихся города
				Минска.
			</div>
		</div>
		<div class="niled">&nbsp;</div>

		<div class="AnonsArtRubric">
			<div class="In"><a title="Прочитать новость"
			                   href="http://www.bsmu.by/allarticles/rubric1/article1164/"><img
						src="http://www.bsmu.by/ImgForArticles/s_201506121627001.jpg"
						alt="Подведение итогов спартакиад"></a></div>
			<div class="InText">
				<div><span style="font-size: 18px;">12</span> июня</div>
				<a title="Прочитать новость"
				   href="http://www.bsmu.by/allarticles/rubric1/article1164/">Подведение
					итогов спартакиад</a><br/>среди сборных команд факультетов и
				общежитий.
			</div>
		</div>
		<div class="niled">&nbsp;</div>

		<div class="AnonsArtRubric">
			<div class="In"><a title="Прочитать новость"
			                   href="http://www.bsmu.by/allarticles/rubric1/article1160/"><img
						src="http://www.bsmu.by/ImgForArticles/s_201506101411151.jpg"
						alt="Итоговая аттестация на лечебном факультете."></a>
			</div>
			<div class="InText">
				<div><span style="font-size: 18px;">10</span> июня</div>
				<a title="Прочитать новость"
				   href="http://www.bsmu.by/allarticles/rubric1/article1160/">Итоговая
					аттестация на лечебном факультете.</a><br/>Государственные
				экзамены у студентов.
			</div>
		</div>
		<div class="niled">&nbsp;</div>

		<div class="AnonsArtRubric">
			<div class="In"><a title="Прочитать новость"
			                   href="http://www.bsmu.by/allarticles/rubric1/article1163/"><img
						src="http://www.bsmu.by/ImgForArticles/s_201506121603131.jpg"
						alt="День рождения военно-медицинского факультета."></a>
			</div>
			<div class="InText">
				<div><span style="font-size: 18px;">9</span> июня</div>
				<a title="Прочитать новость"
				   href="http://www.bsmu.by/allarticles/rubric1/article1163/">День
					рождения военно-медицинского факультета.</a><br/>Двадцатилетие
				факультета.
			</div>
		</div>
		<div class="niled">&nbsp;</div>

		<div class="AnonsArtRubric">
			<div class="In"><a title="Прочитать новость"
			                   href="http://www.bsmu.by/allarticles/rubric1/article1159/"><img
						src="http://www.bsmu.by/ImgForArticles/s_201506091512551.jpg"
						alt="Соревнования по пляжному волейболу"></a></div>
			<div class="InText">
				<div><span style="font-size: 18px;">9</span> июня</div>
				<a title="Прочитать новость"
				   href="http://www.bsmu.by/allarticles/rubric1/article1159/">Соревнования
					по пляжному волейболу</a><br/>в программе Республиканской
				универсиады – 2015.
			</div>
		</div>
		<div class="niled">&nbsp;</div>

		<div class="AnonsArtRubric">
			<div class="In"><a title="Прочитать новость"
			                   href="http://www.bsmu.by/allarticles/rubric1/article1158/"><img
						src="http://www.bsmu.by/ImgForArticles/s_201506081638461.jpg"
						alt="Ежегодные практические занятия по медицинской подготовке"></a>
			</div>
			<div class="InText">
				<div><span style="font-size: 18px;">8</span> июня</div>
				<a title="Прочитать новость"
				   href="http://www.bsmu.by/allarticles/rubric1/article1158/">Ежегодные
					практические занятия по медицинской подготовке</a><br/>среди
				девушек 10-х классов.
			</div>
		</div>
		<div class="niled">&nbsp;</div>

		<div class="AnonsArtRubric">
			<div class="In"><a title="Прочитать новость"
			                   href="http://www.bsmu.by/allarticles/rubric1/article1157/"><img
						src="http://www.bsmu.by/ImgForArticles/s_201506041516151.jpg"
						alt="Наши лауреаты."></a></div>
			<div class="InText">
				<div><span style="font-size: 18px;">4</span> июня</div>
				<a title="Прочитать новость"
				   href="http://www.bsmu.by/allarticles/rubric1/article1157/">Наши
					лауреаты.</a><br/>Торжественная церемония награждения
				Лауреатов XXI Республиканского конкурса научных работ студентов.
			</div>
		</div>
		<div class="niled">&nbsp;</div>

		<div class="AnonsArtRubric">
			<div class="In"><a title="Прочитать новость"
			                   href="http://www.bsmu.by/allarticles/rubric1/article1156/"><img
						src="http://www.bsmu.by/ImgForArticles/s_201506041141261.jpg"
						alt="Спартакиада  БГМУ по футболу"></a></div>
			<div class="InText">
				<div><span style="font-size: 18px;">4</span> июня</div>
				<a title="Прочитать новость"
				   href="http://www.bsmu.by/allarticles/rubric1/article1156/">Спартакиада
					БГМУ по футболу</a><br/>среди сборных команд факультетов.
			</div>
		</div>
		<div class="niled">&nbsp;</div>

		</p>
	</div>
</div>
<script src="http://www.bsmu.by/scripts/title.js"
        type="text/javascript"></script>
</div>
<div class="niled">&nbsp;</div>
<div id="Footer_cont">
	<div id="goverment">
		<div id="blazon"><img src="http://www.bsmu.by/design/gerb_rgb.jpg"
		                      alt=""/></div>
		<div class="govermentbox">
			<div>Президент Республики Беларусь</div>
			<a href="http://president.gov.by">president.gov.by</a></div>
		<div class="govermentbox">
			<div>Совет Республики Беларусь</div>
			<a href="http://sovrep.gov.by">sovrep.gov.by</a></div>
		<div class="govermentbox">
			<div>Министерство здравоохранения</div>
			<a href="http://minzdrav.gov.by">minzdrav.gov.by</a></div>
		<div class="govermentbox">
			<div>Министерство образования</div>
			<a href="http://edu.gov.by">edu.gov.by</a></div>
	</div>
	<div class="niled">&nbsp;</div>
	<div id="copyright1">&copy; 1921&mdash;2015 Учреждение образования &laquo;Белорусский
		государственный медицинский университет&raquo;.
	</div>
	<div id="copyright2">При перепечатке текстовой информации и фотографий&nbsp;гиперссылка
		на сайт обязательна. Все права на графические и текстовые материалы
		принадлежат их авторам.
	</div>
	<div class="niled">&nbsp;</div>
	<div id="footerinfo"><a href="http://www.bsmu.by/page/18/1481/">Контактная
			информация</a>

		<p>220116, г. Минск, пр. Дзержинского, 83<br/>Тел: +375 17 272-61-96.
			Факс: +375 17 272-61-97<br/>Эл. почта: <a
				href="mailto:bsmu@bsmu.by">bsmu@bsmu.by</a><br/><br/></p>
	</div>
	<div class="banner_Partn">
		<p><img src="http://www.bsmu.by/images/MainPage/part_m.png" alt=""
		        width="22" height="22"/><br/><a href="http://belodent.org/">Стоматологический
				информационно-образовательный портал belodent.org</a><br/><a
				title="Витебский государственный ордена Дружбы народов медицинский университе"
				href="http://www.vsmu.by/ru/">ВГМУ</a> <a
				title="Витебский государственный университет им. П. М. Машерова"
				href="http://www.vsu.by/index.php/ru/">ВГУ</a> <a
				title="Гродненский государственный медицинский университет"
				href="http://grsmu.by/">ГрГМУ</a> <a
				title="Белорусский государственный университет информатики и радиоэлектроники"
				href="http://www.bsuir.by/">БГУИР</a>&nbsp;<a
				title="Гродненский государственный университет имени Янки Купалы"
				href="http://www.grsu.by/">ГрГУ</a> <a
				title="Минский инновационный университет"
				href="http://www.miu.by/">МИУ</a> <a
				title="Брестский государственный университет имени А.С. Пушкина"
				href="http://brsu.by/">БрГУ</a> <a
				title="Гомельский государственный технический университет имени П.О. Сухого"
				href="http://gstu.by/">ГГТУ</a> <a
				title="Академия управления при Президенте Республики Беларусь"
				href="http://pac.by/">АУпПРБ</a> <a
				title="Могилевский государственный университет имени А.А. Кулешова"
				href="http://msu.mogilev.by/">МГУ</a> <a
				title="Барановичский государственный университет"
				href="http://barsu.by/">БарГУ</a></p>

		<p><a href="http://www.alexa.com/"><img
					src="http://www.bsmu.by/images/MainPage/alexa.jpg" alt=""
					width="60" height="56"/></a></p>
	</div>
</div>

<!--наши скрипт-->
<script type="text/javascript" src="/comments/ajax_funcLib.js"></script>
<script type="text/javascript" src="/comments/ajax_control.js"></script>

<script src="http://www.bsmu.by/scripts/menu.js"
        type="text/javascript"></script>
<script src="http://www.bsmu.by/scripts/lang_box.js"
        type="text/javascript"></script>
</BODY>
</HTML>