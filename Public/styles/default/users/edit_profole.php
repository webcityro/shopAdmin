<div id="mainContent">
	<div id="topRow">
		<div id="userName">
			<?php echo $userData->userName; ?>
		</div>
	</div>

	<div id="middleRow">
		<div id="leftInfo">
			<div id="profileInfo" data-userid="<?php echo $userData->id; ?>">
				<h3><?php echo language::translate('profileInfo'); ?></h3>
				<p><label for="fName"><strong><?php echo language::translate('formLabelFName'); ?>:</strong></label> <input type="text" id="fName" name="fName" class="updateField" value="<?php echo $userData->fName; ?>"></p>
				<p><label for="lName"><strong><?php echo language::translate('formLabelLName'); ?>:</strong></label> <input type="text" id="lName" name="lName" class="updateField" value="<?php echo $userData->lName; ?>"></p>
				<p><label for="email"><strong><?php echo language::translate('formLabelEmail'); ?>:</strong></label> <input type="text" id="email" name="email" class="updateField" value="<?php echo $userData->email; ?>"></p>
				<p>
					<strong><?php echo language::translate('sex'); ?>:</strong>
					<label for="sexm"><?php echo language::translate('sexm'); ?></label>
					<input type="radio" name="sex" id="sexM" class="sex updateField" value="m"<?php echo ($userData->sex == 'm') ? ' checked="checked"' : ''; ?>> /
					<label for="sexf"><?php echo language::translate('sexf'); ?></label>
					<input type="radio" name="sex" id="sexF" class="sex updateField" value="f"<?php echo ($userData->sex == 'f') ? ' checked="checked"' : ''; ?>>
				</p>
				<p>
					<label><strong>Parola:</strong></label>
					<a href="#" id="showPasswordForm">Schimba-ti parola</a>
					<div id="passwsordForm" class="hide">
						<p><label for="oldPassword"><strong>Parola veche:</strong></label> <input type="text" id="oldPassword"></p>
						<p><label for="newPassword"><strong>Parola noua:</strong></label> <input type="text" id="newPassword"></p>
						<p><label for="newPassword25"><strong>Confirma parola noua:</strong></label> <input type="text" id="newPassword2"></p>
						<p><button class="button buttonSmall" id="changePassword">Salveaza!</button></p>
					</div>
				</p>
			</div>
		</div>
		<input type="hidden" name="updateToken" id="updateToken" value="<?php echo token::generate('update'); ?>">

		<div id="rightInfo"></div>
		<br class="clear">
	</div>
</div>