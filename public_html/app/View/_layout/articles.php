<!DOCTYPE html>
<html>
<head>
<title><?php echo $title; ?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="keywords" content="" />
<meta name="description" content="" />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" media="all" /> 
 <link rel="stylesheet" type="text/css" href="<?php echo $SERVER; ?>style/bibi.css" />
</head>
<body>

	<div id="content">
		<h1 align=center>Защита сайта от DDOS-атак</h1>
		<ul id="menu">
			<li  align=left><a href="http://webdis-graf.16mb.com/">Главная </a></li>
			<li  align=left><a href="/articles">Статьи </a></li>
			<li  align=left><a href="#">Цены </a></li>
			<li  align=left><a href="#">Купить </a></li>
			<li  align=left><a href="#">Помощь онлайн </a></li>
			<li  align=left><a href="#">Обратная связь </a></li>
		</ul>
	
		<?php 
			$DB = Dbconnect::instance()->getConnect(); 
			$user = $DB->select('SELECT * FROM `article`');
			echo '<div class="post">';
			foreach($user as $shit => $do) 
			{
			
			echo ("<p><b>$do[title]</b></p>");
			echo ("<p>$do[description]</p>");
			echo ("<p style='font-style: italic;'>$do[update]</p>");
			}
			echo '</div>';
		?>
	


		<div class="col last">
		</div>
		
		<div id="footer">
			<p>Copyright &copy;</p>
		</div>	
	</div>
</body>
</html>