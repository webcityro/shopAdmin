<div id="mainContent">
	<h1><?php echo language::translate('retesPasswordPageTitle'); ?></h1>
	<div id="resetPasswordForm" class="form">
		<h2><?php echo language::translate('retesPasswordFormTitle'); ?></h2>
		<div id="resetPasswordError" class="formRow error hide"></div>
		<div class="formRow">
			<label for="resetNewPassword"><?php echo language::translate('formLabelNewPassword'); ?></label>
			<input type="password" name="resetNewPassword" id="resetNewPassword">
		</div>
		<div class="formRow">
			<label for="resetConfirmNewPassword"><?php echo language::translate('formLabelConfirmNewPassword'); ?></label>
			<input type="password" name="resetConfirmNewPassword" id="resetConfirmNewPassword">
		</div>
		<div class="formRow buttom">
			<input type="hidden" name="resetPasswordToken" id="resetPasswordToken" value="<?php echo token::generate('resetPasswordToken'); ?>">
			<input type="hidden" name="resetPasswordUderID" id="resetPasswordUserID" value="<?php echo $userID; ?>">
			<button id="resetPasswordBtn" class="button buttonBig"><?php echo language::translate('retesPasswordBtn'); ?></button>
		</div>
	</div>
</div>