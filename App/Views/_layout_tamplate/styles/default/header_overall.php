<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="<?php echo Storemaker\System\Libraries\Config::get('url/style'); ?>css/main.css">
		<link rel="stylesheet" href="<?php echo Storemaker\System\Libraries\Config::get('url/style'); ?>css/popups.css">
		<link rel="stylesheet" href="<?php echo Storemaker\System\Libraries\Config::get('url/style'); ?>css/alerts.css">
		<link rel="stylesheet" href="<?php echo Storemaker\System\Libraries\Config::get('url/style'); ?>css/form.css">
		<link rel="stylesheet" href="<?php echo Storemaker\System\Libraries\Config::get('url/style'); ?>css/buttons.css">
		<link rel="stylesheet" href="<?php echo Storemaker\System\Libraries\Config::get('url/style'); ?>css/pagination.css">
		<link rel="stylesheet" href="<?php echo Storemaker\System\Libraries\Config::get('url/style'); ?>css/categories.css">
		<link rel="stylesheet" href="<?php echo Storemaker\System\Libraries\Config::get('url/style'); ?>css/error_handle.css">
		<link rel="stylesheet" href="<?php echo Storemaker\System\Libraries\Config::get('url/style'); ?>css/table_style.css">
		<?php echo $this->getCSS(); ?>

		<script>
			var languageJSON = <?php echo Storemaker\System\Libraries\language::getJSON(); ?>;
		</script>
		<script src="<?php echo Storemaker\System\Libraries\Config::get('site/domain'); ?>App/Public/js/language.js"></script>
		<script src="<?php echo Storemaker\System\Libraries\Config::get('site/domain'); ?>App/Public/js/jquery.js"></script>
		<script src="<?php echo Storemaker\System\Libraries\Config::get('site/domain'); ?>App/Public/js/jquery-ui.min.js"></script>
		<script src="<?php echo Storemaker\System\Libraries\Config::get('site/domain'); ?>App/Public/js/error_handle.js"></script>
		<script>
			var domain = "<?php echo Storemaker\System\Libraries\Config::get('site/domain'); ?>";
		</script>
		<script src="<?php echo Storemaker\System\Libraries\Config::get('url/style'); ?>js/init.js"></script>
	<?php if ($this->thisUser->isLogIn()): ?>

	<?php else: ?>
		<script src="<?php echo Storemaker\System\Libraries\Config::get('url/style'); ?>js/login_functions.js"></script>
	<?php endif ?>
		<?php
			echo $this->getJS();
			echo $this->getFavicon();
		?>

		<title><?php echo Storemaker\System\Libraries\Config::get('site/name').' - '.$this->getTitleTag(); ?></title>
	</head>
	<body>
		<?php
			require_once 'popups.php';
			require_once 'header.php';


		?>
		<main id="main">
			<?php
				if ($this->thisUser->isLogIn()) {
					include_once 'navbar.php';
				}
			?>