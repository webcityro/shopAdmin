<?php
if (session::flashExists('home')):
	$flash = session::flash('home');
?>
	<div class="alert <?php echo $flash['type']; ?>">
		<span class="alertBody"><?php echo $flash['msg']; ?></span>
		<a href="#" class="alertClose">&times;</a>
	</div>
<?php endif; ?>

<?php
if ($this->thisUser->isLogIn()):
?>

<?php else: ?>
	<div class="PUbg">
		<div id="singUp" class="popup">
			<h2 class="PUtitle"><?php echo language::translate('formLoginNewAccount'); ?></h2>
			<div class="PUclose">X</div>
			<div class="PUbody">
				<div class="form">
					<div class="formRow">
						<label for="fName"><?php echo language::translate('formLabelFName'); ?>:</label>
						<input type="text" name="fName" id="fName">
					</div>
					<div class="formRow">
						<label for="lName"><?php echo language::translate('formLabelLName'); ?>:</label>
						<input type="text" name="lName" id="lName">
					</div>
					<div class="formRow">
						<label for="userName"><?php echo language::translate('formLabelUserName'); ?>:</label>
						<input type="text" name="userName" id="userName">
					</div>
					<div class="formRow">
						<label for="password"><?php echo language::translate('formLabelPassword'); ?>:</label>
						<input type="password" name="password" id="password">
					</div>
					<div class="formRow">
						<label for="password2"><?php echo language::translate('formLabelPassword2'); ?>:</label>
						<input type="password" name="password2" id="password2">
					</div>
					<div class="formRow">
						<label><?php echo language::translate('formLabelSex'); ?>:</label>
						<div style="display:table-cell; width: 100%;">
							<input type="radio" name="sex" id="sexM" class="sex" value="m">
							<label for="sexM" class="checkbox">
								<?php echo language::translate('sexm'); ?>:
								<span></span>
							</label>
							<br>
							<input type="radio" name="sex" id="sexF" class="sex" value="f">
							<label for="sexF" class="checkbox">
								<?php echo language::translate('sexf'); ?>:
								<span></span>
							</label>
						</div>
					</div>
					<div class="formRow">
						<label for="email"><?php echo language::translate('formLabelEmail'); ?>:</label>
						<input type="text" name="email" id="email">
					</div>
					<div class="formRow">
						<input type="checkbox" name="agree" id="agree">
						<label for="agree" class="checkbox">
							<?php echo language::translate('formLabelAgree', '<a href="#">'.language::translate('t&cp').'</a>'); ?>:
							<span></span>
						</label>
					</div>
					<div class="formRow buttonRow">
						<input type="hidden" name="singupToken" id="singupToken" value="<?php echo token::generate('singup'); ?>">
						<button id="singupBtn" class="button buttonBig"><?php echo language::translate('singUpBtn'); ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="PUbg">
		<div id="forgetPassword" class="popup">
			<h2 class="PUtitle"><?php echo language::translate('forgetMyPassword'); ?></h2>
			<div class="PUclose">X</div>
			<div class="PUbody">
				<div class="form">
					<div class="formRow error hide" id="forgetPasswordError"></div>
					<div class="formRow">
						<label for="forgetPasswordEmail"><?php echo language::translate('formLabelEmail'); ?>:</label>
						<input type="text" name="forgetPasswordEmail" id="forgetPasswordEmail">
					</div>
					<div class="formRow buttonRow">
						<input type="hidden" name="forgetPasswordToken" id="forgetPasswordToken" value="<?php echo token::generate('forgetPasswordToken'); ?>">
						<button id="forgetPasswordBtn" class="button buttonBig"><?php echo language::translate('forgetPasswordBtn'); ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif ?>

