<!DOCTYPE html>
<html>
	<head>
		<link type="text/css" rel="stylesheet" href="<?php echo config::get('site/domain'); ?>app/public/styles/<?php echo config::get('site/style'); ?>/css/main.css">
		<link type="text/css" rel="stylesheet" href="<?php echo config::get('site/domain'); ?>app/public/styles/<?php echo config::get('site/style'); ?>/css/popups.css">
		<link type="text/css" rel="stylesheet" href="<?php echo config::get('site/domain'); ?>app/public/styles/<?php echo config::get('site/style'); ?>/css/alerts.css">
		<link type="text/css" rel="stylesheet" href="<?php echo config::get('site/domain'); ?>app/public/styles/<?php echo config::get('site/style'); ?>/css/form.css">
		<link type="text/css" rel="stylesheet" href="<?php echo config::get('site/domain'); ?>app/public/styles/<?php echo config::get('site/style'); ?>/css/buttons.css">
		<link type="text/css" rel="stylesheet" href="<?php echo config::get('site/domain'); ?>app/public/styles/<?php echo config::get('site/style'); ?>/css/pagination.css">
		<link type="text/css" rel="stylesheet" href="<?php echo config::get('site/domain'); ?>app/public/styles/<?php echo config::get('site/style'); ?>/css/categories.css">
		<link type="text/css" rel="stylesheet" href="<?php echo config::get('site/domain'); ?>app/public/styles/<?php echo config::get('site/style'); ?>/css/error_handle.css">
		<link type="text/css" rel="stylesheet" href="<?php echo config::get('site/domain'); ?>app/public/styles/<?php echo config::get('site/style'); ?>/css/table_style.css">
		<?php echo $this->getCSS(); ?>

		<script>
			var languageJSON = <?php echo language::getJSON(); ?>;
		</script>
		<script type="text/javascript" src="<?php echo config::get('site/domain'); ?>app/public/js/language.js"></script>
		<script type="text/javascript" src="<?php echo config::get('site/domain'); ?>app/public/js/jquery.js"></script>
		<script type="text/javascript" src="<?php echo config::get('site/domain'); ?>app/public/js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="<?php echo config::get('site/domain'); ?>app/public/js/error_handle.js"></script>
		<script>
			var domain = "<?php echo config::get('site/domain'); ?>";
		</script>
		<script type="text/javascript" src="<?php echo config::get('site/domain'); ?>app/public/js/init.js"></script>
	<?php if ($this->thisUser->isLogIn()): ?>

	<?php else: ?>
		<script type="text/javascript" src="<?php echo config::get('site/domain'); ?>app/public/js/login_functions.js"></script>
	<?php endif ?>
		<?php
			echo $this->getJS();
			echo $this->getFavicon();
		?>

		<title><?php echo config::get('site/name').' - '.$this->getTitleTag(); ?></title>
	</head>
	<body>
		<?php
			require_once config::get('path/appViews').'tamplate/styles/'.config::get('site/style').'/popups.php';
			require_once config::get('path/appViews').'tamplate/styles/'.config::get('site/style').'/header.php';


		?>
		<main id="main">
			<?php
				if ($this->thisUser->isLogIn()) {
					include_once config::get('path/appViews').'tamplate/styles/'.config::get('site/style').'/navbar.php';
				}
			?>