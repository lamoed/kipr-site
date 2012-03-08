<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title><?=$page_title?></title>
	<meta http-equiv="Content-Type" content="text/html;charset=windows-1251" />
	<meta name="keywords" content="Кафедра Кипр" />
        <link rel="shortcut icon" href="/look/pic/favicon.ico" />
        <link rel="icon" type="image/x-icon" href="/look/pic/favicon.ico" />
        <link rel="stylesheet" type="text/css" href="/look/css/styles.css" media="all" />
	<!--[if lt IE 7]>
        <script type="text/javascript" src="/look/js/fixpng.js"></script>
	<![endif]-->
	<script type="text/javascript" src="/look/js/scripts.js"></script>
</head>
<body>
<div class="l-header">
	<div class="b-header">
                <div class="b-headertext">Южно-Уральский Государственный Университет<br />Кафедра «Конструирования и производства радиоаппаратуры»</div><div class="b-logo"><a href="<?=$hostname?>"><img src="/look/pic/logo2.png" alt="Кафедра КиПР" /></a></div>
		<div class="b-logosusu"><img src="/look/pic/uurgu.png" alt="" /></div>
		<ul class="b-menu">
			<li class="top"><a href="<?=$hostname?>student/"><span>Студенту</span></a>
                            <ul class="b-submenu">
                                <li><a href="<?=$hostname?>student/information/">информация</a></li>
                                <li><a href="<?=$hostname?>student/documents/">документы</a></li>
                            </ul>
                        </li>
			<li class="top"><a href="<?=$hostname?>abiturient/"><span>Абитуриенту</span></a>
                            <ul class="b-submenu">
                                <li><a href="<?=$hostname?>abiturient/information/">информация</a></li>
                                <li><a href="<?=$hostname?>abiturient/documents/">документы</a></li>
                                <li><a href="<?=$hostname?>abiturient/speciality/">специальности</a></li>
                                <li><a href="<?=$hostname?>abiturient/lecturers/">преподаватели</a></li>
                            </ul>
                        </li>
			<li class="top"><a class="bigword" href="<?=$hostname?>scientific/"><span>Научная деятельность</span></a>
                            <ul class="b-submenu">
                                <li><a href="<?=$hostname?>scientific/publications/">публикации</a></li>
                                <li><a href="<?=$hostname?>scientific/patents/">патенты</a></li>
                                <li><a href="<?=$hostname?>scientific/exhibition/">выставки</a></li>
                                <li><a href="<?=$hostname?>scientific/awards/">награды</a></li>
                            </ul>
                        </li>
			<li class="top"><a href="<?=$hostname?>guestbook/"><span>Гостевая книга</span></a></li>
			<li class="top"><a href="<?=$hostname?>contacts/"><span>Контакты</span></a></li>
		</ul>
	</div>
</div>
<div class="l-content">
    <div class="b-page">
        <?=$content?>
        <div class="b-popup">
            <a class="b-close">x</a>
            <p class="b-contactarea">
            </p>
        </div>
        <div class="b-background-popup"></div>
    </div>
</div>
<div class="l-separator"></div>
<div class="l-footer">
	<div class="b-footer">
		<img src="/look/pic/wires.jpg" alt="" class="b-wires" />
		<div class="b-email">
			<h2>Рассылка новостей:</h2>
			<img src="/look/pic/mail.png" alt="Почта" />
                        <form action="#" method="post">
				<div class="b-email-input">
					<input type="text" name="newsemail" maxlength="30" value="user@mail.ru" />
				</div>
                            <input type="button" class="b-email-submit" value="Подписаться" onclick="sendbox();" />
			</form>
		</div>
		<div class="b-footer-menu">
			<h2>Меню:</h2>
			<ul>
                            <li><a href="<?=$hostname?>student/">Студенту</a>&raquo;<a href="<?=$hostname?>student/information/">информация</a>&raquo;<a href="<?=$hostname?>student/documents/">документы</a></li>
				<li><a href="<?=$hostname?>abiturient/">Абитуриенту</a>&raquo;<a href="<?=$hostname?>abiturient/information/">информация</a>&raquo;<a href="<?=$hostname?>abiturient/documents/">документы</a></li>
				<li><a href="<?=$hostname?>abiturient/">Абитуриенту</a>&raquo;<a href="<?=$hostname?>abiturient/speciality/">специальности</a>&raquo;<a href="<?=$hostname?>abiturient/lecturers/">преподаватели</a></li>
				<li><a href="<?=$hostname?>scientific/">Научная деятельность</a>&raquo;<a href="<?=$hostname?>scientific/publications/">публикации</a>&raquo;<a href="<?=$hostname?>scientific/patents/">патенты</a></li>
				<li><a href="<?=$hostname?>scientific/">Научная деятельность</a>&raquo;<a href="<?=$hostname?>scientific/exhibition/">выставки</a>&raquo;<a href="<?=$hostname?>scientific/awards/">награды</a></li>
				<li><a href="<?=$hostname?>forum/">Форум</a></li>
				<li><a href="<?=$hostname?>guestbook/">Гостевая книга</a></li>
				<li><a href="<?=$hostname?>contacts/">Контакты</a></li>
			</ul>
		</div>
                <!--LiveInternet counter--><script type="text/javascript"><!--
                    document.write("<a href='http://www.liveinternet.ru/click' "+
                        "target=_blank><img src='//counter.yadro.ru/hit?t45.18;r"+
                        escape(document.referrer)+((typeof(screen)=="undefined")?"":
                        ";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
                        screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
                        ";"+Math.random()+
                        "' alt='' title='LiveInternet' "+
                        "border='0' width='31' height='31' class='b-livinternet'><\/a>")
                    //--></script><!--/LiveInternet-->
	</div>
</div>
</body>
</html>