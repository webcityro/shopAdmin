<html>
	<head>
		<link type="text/css" rel="stylesheet" href="<?php echo config::get('site/domain'); ?>app/public/styles/<?php echo config::get('site/style').'/'; ?>/css/main.css">

		<title>Proline admin - <?php echo $this->view->getTitleTag(); ?></title>
	</head>
	<body>
		<div id="main">
			<?php require_once config::get('path/sysViews').'tamplate/header.php'; ?>
