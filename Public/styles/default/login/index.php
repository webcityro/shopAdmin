<?php
    $errors['userName'] = (!empty($errors['userName'])) ? '<img src="'.config::get('icon/error').'" alt="Eroare"><div class="error">'.$errors['userName'].'</div>' : '';
    $errors['password'] = (!empty($errors['password'])) ? '<img src="'.config::get('icon/error').'" alt="Eroare"><div class="error">'.$errors['password'].'</div>' : '';
    $errors['top'] = (!empty($errors['top'])) ? '<img src="'.config::get('icon/error').'" alt="Eroare"><div class="error">'.$errors['top'].'</div>' : '';
?>
<div id="mainContent">
	<h1><?php echo language::translate('pageTitle'); ?></h1>
    <form action="<?php echo config::get('site/domain'); ?>login/run" method="post">
        <table class="form" id="login">
            <tr id="top">
                <?php echo (!empty($errors['top'])) ? '<td colspan="2" class="feedBeck" style="text-align: center; color: #f00;">'.$errors['top'].'</td>' : ''; ?>
            </tr>
            <tr>
                <td class="tdLabels">
                    <label for="userName"><?php echo language::translate('formLabelUserName'); ?>:</label>
                </td>
                <td class="tdInput" id="userNameRow">
                    <input type="text" name="userName" id="userName" value="<?php echo input::post('userName') ?>">
                    <div class="feedBeck" id="userNameFeedBeck">
                        <?php echo $errors['userName']; ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="tdLabels">
                    <label for="password"><?php echo language::translate('formLabelPassword'); ?>:</label>
                </td>
                <td class="tdInput" id="passwordRou">
                    <input type="password" name="password" id="password">
                    <div class="feedBeck" id="passwordFeedBeck">
                        <?php echo $errors['password']; ?>
                    </div>
                    <div id="rememberMeDiv">
                        <label for="rememberMe"><?php echo language::translate('formLabelRememberMe'); ?>:</label>
                        <input type="checkbox" name="rememberMe" id="rememberMe" value="on"<?php echo (input::post('rememberMe') == 'on') ? ' checked="checked"' : ''; ?>>
                    </div>
                </td>
            </tr>
            <tr id="logInBtnRow">
                <td class="tdButtonm" colspan="2" id="btnRow">
                    <input type="hidden" name="token" id="token" value="<?php echo token::generate(); ?>">
                    <input type="submit" id="logInBtn" class="button buttonBig" value="<?php echo language::translate('formLoginBtn'); ?>">
                </td>
            </tr>
        </table>
    </form>
</div>