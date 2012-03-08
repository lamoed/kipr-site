<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <title><?=$page_title?></title>
        <meta http-equiv="Content-Type" content="text/html;charset=windows-1251" />
        <meta name="keywords" content="������� ����" />
        <link rel="shortcut icon" href="/look/pic/favicon.ico" />
        <link rel="icon" type="image/x-icon" href="/look/pic/favicon.ico" />
        <link rel="stylesheet" type="text/css" href="/look/css/styles.css" media="all" />
        <!--[if lt IE 7]>
            <script type="text/javascript" src="/look/js/fixpng.js"></script>
	<![endif]-->
        <script type="text/javascript">
            function sure(value, type) {
                if(confirm('�� �������?')) {
                    window.location = '<?=$hostname?>admin/'+type+'/delete/'+value;
                }
            }
        </script>
        <?=$javascript?>
    </head>
    <body>
        <div class="l-header">
            <div class="b-header">
                <div class="b-headertext">����-��������� ��������������� �����������<br />������� ���������������� � ������������ ����������������</div><div class="b-logo"><a href="<?=$hostname?>admin/"><img src="/look/pic/logo2.png" alt="������� ����" /></a></div>
		<div class="b-logosusu"><img src="/look/pic/uurgu.png" alt="" /></div>
                <ul class="b-menu">
                    <li class="top"><a href="<?=$hostname?>admin/news/"><span>�������</span></a></li>
                    <li class="top"><a href="<?=$hostname?>admin/documents/"><span>���������</span></a></li>
                    <li class="top"><a href="<?=$hostname?>admin/forum/index/"><span>�����</span></a></li>
                    <li class="top"><a href="<?=$hostname?>admin/guestbook/"><span>�������� �����</span></a></li>
                </ul>
            </div>
        </div>
        <div class="l-content l-admin-content">
            <div class="b-page">
                <?=$content?>
            </div>
        </div>
        <div class="l-separator"></div>
        <div class="l-footer l-admin-footer">
            <div class="b-footer b-admin-footer">
                <h3>����������������� ������</h3><a href="<?=$hostname?>">&rarr; �� ����</a><br />
                <div class="b-copy">&copy;���� ����� 2010�.</div>
            </div>
        </div>
    </body>
</html>