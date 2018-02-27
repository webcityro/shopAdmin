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
			text-align:center;
		}

		a:hover {
			color:#000;
			text-decoration:none;
		}
		body {
			text-align:center;
		}

		#head {
			margin:0 0 0 0;
			width:100%;
			height:154px;
			background-image:url('.$style.'/images/top_header_bg.jpg);
			background-position:0 74px;
			background-repeat:repeat-x;
		}

		#head #logo {
			margin:0 0 0 50px;
			width:317px;
			height:74px;
		}

		#head #weolcomeText {
			padding:20px;
		}

		#head #weolcomeText p {
			font-family:"Arial Black", Gadget, sans-serif;
			font-size:24px;
			font-weight:bolder;
			text-align:right;
			color:#ccc;
		}

		#contents {
			padding:20px;
			margin:20px auto 20px auto;
			width:90%;
			font:Arial, Helvetica, sans-serif 12px normal;
			text-align:left;
			color:#333;
			background:#f1f1f1;
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
			background-color:#f1f1f1;
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
			<a href="'.config::get('site/domain').'">
				<img src="'.config::get('site/domain').'app/public/images/logo.png" alt="'.config::get('site/name').'">
			</a>
		</div>
		<div id="weolcomeText">
			<p>'.language::translate('emailSubject').'</p>
		</div>
	</div>
	<div id="contents">
		<p>'.language::translate('hi').' '.$userName.'!</p>
		<p>'.language::translate('emailPharagraf1').'</p>
		<p>'.language::translate('emailPharagraf2').'</p>

		<ul>
			<li>'.language::translate('formLabelUserName').' <strong>'.$userName.'</strong></li>
			<li>'.language::translate('formLabelPassword').' <strong>'.$password.'</strong></li>
			<li>'.language::translate('formLabelEmail').'Adresa de email: <strong>'.$email.'</strong></li>
			<li>'.language::translate('yourProfile').': <strong><a href="'.config::get('site/domain').'user/'.$userName.'/">'.config::get('site/domain').'user/'.$userName.'/</a></strong></li>
		</ul>

		<p>'.language::translate('emailPharagraf3').' <a href="'.config::get('site/domain').'inscriere/activare/'.$newUserId.'/'.$userCode.'">'.language::translate('clickHire').'</a>!</p>
		<p>'.language::translate('tnx').'<br />'.language::translate('ourTeam').'</p>
	</div>
	<div id="footer">
		<p id="copyright">&copy; Copyright 2014 '.((date('Y') < 2015) ? '- '.date('Y') : "").'<a href="htto://www.web-city.ro/">Web City</a> '.language::translate('copyright').'</p>
	</div>
</body>
</html>';