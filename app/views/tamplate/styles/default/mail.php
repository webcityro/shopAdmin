<?php
	$body = '
<html>
<head>
	<style type="text/css">
		* {
			padding:0;
			margin:0;
		}

		a:link, a:active, a:visited {
			font-family:Arial, Helvetica, sans-serif;
			color:#333;
		}

		a:hover {
			color:#000;
			text-decoration:none;
		}
		body {
		}

		#head {
			margin:0 0 0 0;
			width:100%;
			height:50px;
			border-bottom: 1px solid #000;
			background: #333;
			background: -webkit-linear-gradient( #444, #222);
			background: -moz-linear-gradient( #444, #222);
			background: -o-linear-gradient( #444, #222);
			background: -ms-linear-gradient( #444, #222);
			background: linear-gradient( #444, #222);
		}

		#head #logo {
			float:left;
			margin:5px 0 0 40px;
			width:42px;
			height:40px;
		}

		#head #weolcomeText {
			float:left;
			padding:0 20px 0 20px;
			height: 50px;
			font-family:"Arial Black", Gadget, sans-serif;
			font-size:24px;
			font-weight:bolder;
			text-align:right;
			color:#ccc;
			line-height: 50px;
		}

		#contents {
			padding:20px;
			margin:20px auto 20px auto;
			width:90%;
			font:Arial, Helvetica, sans-serif 12px normal;
			text-align:left;
			color:#333;
			border-radius: 20px;
			background:#f5f5f5;
			border:1px solid #333;
		}

		#contents p {
			margin:15px 0 0 0;
			text-indent:20px;
		}

		#contents ul {
			margin:15px 0 0 35px;;
		}

		#footer {
			padding:10px 20px;
			margin:0 auto;
			width:90%;
			text-align:center;
			border-radius:20px;
			background-color:#f5f5f5;
			border:1px solid #333;
		}

		#footer #copyright {
			margin-left:auto;
			margin-right:auto;
			font-family:Arial, Helvetica, sans-serif;
			font-size:11px;
			text-align:center;
			color:#333;
			text-align:center;
		}


	</style>
</head>
<body>
	<div id="head">
		<div id="logo">
			<a href="'.config::get('site/domain').'">Proline - Admin</a>
		</div>
		<div id="weolcomeText">'.$title.'</div>
		<br style="clear: both;">
	</div>
	<div id="contents">
		'.$value.'
	</div>
	<div id="footer">
		<p id="copyright">&copy; Copyright 2017 '.((date('Y') < 2017) ? '- '.date('Y') : "").'<a href="htto://www.web-city.ro/">Web City</a> '.language::translate('copyright').'</p>
	</div>
</body>
</html>';