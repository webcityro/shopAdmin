<div id="mainContent">
	<div id="topRow">
		<div id="userName">
			<?php echo $userData->userName; ?>
		</div>
	</div>

	<div id="middleRow">
		<div id="leftInfo">
			<?php if ($isThisUser): ?>
				<div id="editProfile">
					<a href="<?php echo config::get('site/domain').'user/'.$userData->userName; ?>/edit"><?php echo language::translate('editProfilInfo'); ?></a>
				</div>
			<?php endif ?>

			<div id="profileInfo">
				<h3><?php echo language::translate('profileInfo'); ?></h3>
				<p><strong><?php echo language::translate('formLabelFName'); ?>:</strong> <?php echo $userData->fName; ?></p>
				<?php if (!empty($userData->lName)): ?>
					<p><strong><?php echo language::translate('formLabelLName'); ?>:</strong> <?php echo $userData->lName; ?></p>
				<?php endif ?>
				<p><strong><?php echo language::translate('formLabelEmail'); ?>:</strong> <?php echo $userData->email; ?></p>
				<p><strong><?php echo language::translate('sex'); ?>:</strong> <?php echo language::translate('sex'.$userData->sex); ?></p>
				<p><strong><?php echo language::translate('lastLoginDate'); ?>:</strong> <?php echo date::normalFormat($userData->lastLogInDate); ?></p>
			</div>
		</div>

		<div id="rightInfo"></div>


		<br class="clear">
	</div>
</div>