<?php
    load::sysHelpper('form');

    if (!empty($errors)) {
        foreach ($errors as $key => $value) {
            $errors[$key] = '<img src="'.config::get('icon/error').'" alt="Eroare"><div class="error">'.$value.'</div>';
        }
    }
?>

<div id="mainContents">
	<h1><?php echo language::translate('pageTitle'); ?></h1>

    <form action="<?php echo config::get('site/domain').'inscriere/run'; ?>" method="post" name="singUpForm">
        <table id="singUpForm" class="form">
            <tr>
                <td class="tdLabels">
                    <label for="UserName"><?php echo language::translate('formLabelUserName'); ?>:</label>
                </td><td class="tdInput">
                    <input type="text" name="userName" id="userName" value="<?php echo input::post('userName'); ?>" />
                    <div class="feedBeck" id="userNameFeedBeck">
                        <?php echo (isset($errors['userName'])) ? $errors['userName'] : ''; ?>
                    </div>
                </td>
            </tr><tr>
                <td class="tdLabels">
                    <label for="password"><?php echo language::translate('formLabelPassword'); ?>:</label>
                </td><td class="tdInput">
                    <input type="password" name="password" id="password" />
                    <div class="feedBeck" id="passwordFeedBeck">
                        <?php echo (isset($errors['password'])) ? $errors['password'] : ''; ?>
                    </div>
                </td>
            </tr><tr>
                <td class="tdLabels">
                    <label for="password2"><?php echo language::translate('formLabelPassword2'); ?>:</label>
                </td><td class="tdInput">
                    <input type="password" name="password2" id="password2" />
                    <div class="feedBeck" id="password2FeedBeck">
                        <?php echo (isset($errors['password2'])) ? $errors['password2'] : ''; ?>
                    </div>
                </td>
            </tr><tr>
                <td class="tdLabels">
                    <label for="email"><?php echo language::translate('formLabelEmail'); ?>:</label>
                </td><td class="tdInput">
                    <input type="text" name="email" id="email" value="<?php echo input::post('email'); ?>" />
                    <div class="feedBeck" id="emailFeedBeck">
                        <?php echo (isset($errors['email'])) ? $errors['email'] : ''; ?>
                    </div>
                </td>
            </tr><tr>
                <td class="tdLabels">
                    <label for="sex"><?php echo language::translate('formLabelSex'); ?>:</label>
                </td><td class="tdInput">
                    <label for="m"><?php echo language::translate('formLabelSexM'); ?>:</label>
                    <input type="radio" name="sex" class="sex" id="m" value="m" <?php echo (input::post('sex') == 'm') ? 'checked="checked"' : ''; ?> />
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="f"><?php echo language::translate('formLabelSexF'); ?>:</label>
                    <input type="radio" name="sex" class="sex" id="f" value="f"<?php echo (input::post('sex') == 'f') ? 'checked="checked"' : ''; ?> />
                    <div class="feedBeck" id="sexFeedBeck">
                        <?php echo (isset($errors['sex'])) ? $errors['sex'] : ''; ?>
                    </div>
                </td>
            </tr><tr>
                <td class="tdLabels">
                    <label><?php echo language::translate('formLabelDob'); ?>:</label>
                </td><td class="tdInput">
                    <select id="dobDay" name="dobDay">
                        <option value=""><?php echo language::translate('formLabelDobDay'); ?></option>
                        <?php
                            for ($x=1;$x<=31;$x++) {
                                echo '<option '.(($x == input::post('dobDay')) ? ' selected="selected"' : ''). ' value="',$x, '">', $x, '</option>';
                            }
                        ?>
                    </select>
                    <div class="feedBeck" id="dobDayFeedBeck">
                        <?php echo (isset($errors['dobDay'])) ? $errors['dobDay'] : ''; ?>
                    </div>
                    <select name="dobMonth" id="dobMonth">
                        <option value=""><?php echo language::translate('formLabelDobMonth'); ?></option>
                        <?php
                            for ($x=1;$x<=12;$x++) {
                                echo '<option'.(($x == input::post('dobMonth')) ? ' selected="selected"' : '').' value="',($x < 10) ? '0'.$x : $x, '">', $x, '</option>';
                            }
                        ?>
                    </select>
                    <div class="feedBeck" id="dobMonthFeedBeck">
                        <?php echo (isset($errors['dobMonth'])) ? $errors['dobMonth'] : ''; ?>
                    </div>
                    <select name="dobYear" id="dobYear">
                        <option value=""><?php echo language::translate('formLabelDobYear'); ?></option>
                        <?php
                            for ($x=date('Y');$x>=(date('Y')-100);$x--) {
                                echo '<option '.(($x == input::post('dobYear')) ? ' selected="selected"' : '').' value="',$x, '">', $x, '</option>';
                            }
                        ?>
                    </select>
                    <div class="feedBeck" id="dobYearFeedBeck">
                        <?php echo (isset($errors['dobYear'])) ? $errors['dobYear'] : ''; ?>
                    </div>
                </td>
            </tr><tr>
                <td class="tdLabels">
                    <label for="country"><?php echo language::translate('formLabelCountry'); ?>:</label>
                </td><td class="tdInput">
                <?php echo form_countrySelectList(array('name' => 'country', 'id' => 'country'), language::translate('formLabelCountry'), NULL, input::post('country')) ?>
                    <div class="feedBeck" id="taraFeedBeck">
                        <?php echo (isset($errors['country'])) ? $errors['country'] : ''; ?>
                    </div></td>
            </tr><tr>
              <td class="tdLabels">&nbsp;</td>
              <td colspan="2" class="tdButtonm">
                  <input type="hidden" name="token" id="token" value="<?php echo token::generate(); ?>">
                  <input type="submit" id="singUpBtn" class="button buttonBig" value="Inscrie-te!">
              </td>
            </tr>
        </table>
    </form>
</div>