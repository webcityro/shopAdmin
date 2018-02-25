<div id="userInfo">
<?php
if ($this->thisUser->isLogIn()):
    //echo language::translate('hi').' ';
?>
    <div id="UIinner">
		<a href="<?php echo config::get('site/domain').'user/'.$this->thisUser->getData()->userName; ?>"><?php echo $this->thisUser->getData()->userName; ?></a>
		<a href="<?php echo config::get('site/domain'); ?>logout" class="button buttonSmall">Logout</a>
    </div>
    <a href="<?php echo config::get('site/domain'); ?>login" id="userInfoBtn"><?php echo $this->thisUser->getData()->userName; ?></a>
<?php else: ?>
	<div id="UIinner">
		<div class="error"></div>
		<div id="loginForm">
			<div class="loginFormRow">
				<label for="userName"><?php echo language::translate('formLabelUser'); ?></label>
				<input type="text" name="loginUserName" id="loginUserName">
			</div>
			<div class="loginFormRow">
				<label for="password"><?php echo language::translate('formLabelPassword'); ?></label>
				<input type="password" name="loginPassword" id="loginPassword">
			</div>
			<div class="loginFormRow rememberMe">
				<input type="checkbox" name="rememberMe" id="rememberMe">
				<label for="rememberMe"><?php echo language::translate('formLabelRememberMe'); ?>
					<span></span>
				</label>
			</div>
			<div class="loginFormRow" id="loginButtonRow">
				<input type="hidden" name="loginToken" id="loginToken" value="<?php echo token::generate('login'); ?>">
				<button id="loginBtn"><?php echo language::translate('formLoginBtn'); ?></button>
			</div>
			<br class="clearFix">
		</div>
	</div>
	<a href="<?php echo config::get('site/domain'); ?>login" id="userInfoBtn">Login</a>
<?php endif; ?>
</div>