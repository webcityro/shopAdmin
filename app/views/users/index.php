<div id="mainContent">
	<h1><?php echo language::translate('pageTitle'); ?></h1>

	<table id="displayUsers" class="displayTable">
		<thead>
			<tr>
				<th><?php echo language::translate('idCollumn'); ?></th>
				<th><?php echo language::translate('formLabelUserName'); ?></th>
				<th><?php echo language::translate('formLabelFName'); ?></th>
				<th><?php echo language::translate('formLabelLName'); ?></th>
				<th><?php echo language::translate('formLabelEmail'); ?></th>
				<th><?php echo language::translate('formLabelSex'); ?></th>
				<th><?php echo language::translate('singupDate'); ?></th>
				<th><?php echo language::translate('lastLoginDate'); ?></th>
				<th><?php echo language::translate('lastLogoutDate'); ?></th>
				<th><?php echo language::translate('actionCollumn'); ?></th>
				</tr>
			</thead>
		<tbody>
		<?php
			$usersList = (is_array($usersList)) ? $usersList : [$usersList];

			foreach ($usersList as $userRow) {
				$thisUser = new userObj($userRow->id);
				$allowEdit = ($userRow->id != config::get('ownerID') || $this->thisUser->isOwner());
		?>
			<tr class="userHeade" data-id="<?php echo $userRow->id; ?>">
				<td><?php echo $userRow->id; ?></td>
				<td><?php echo $userRow->userName; ?></td>
				<td><?php echo $userRow->fName; ?></td>
				<td><?php echo $userRow->lName; ?></td>
				<td><?php echo $userRow->email; ?></td>
				<td><?php echo language::translate('formLabelSex'.strtoupper($userRow->sex)); ?></td>
				<td><?php echo date('d m Y', strtotime($userRow->singUpDate)); ?></td>
				<td><?php echo date('d m Y - H : i', strtotime($userRow->lastLogInDate)); ?></td>
				<td><?php echo date('d m Y - H : i', strtotime($userRow->lastLogOutDate)); ?></td>
				<td>
					<?php if (!$thisUser->isOwner()): ?>
						<a href="#" class="editUser"><?php echo language::translate('edit'); ?></a> /
						<a href="#" class="deleteUser"><?php echo language::translate('delete'); ?></a>
					<?php else: ?>
						 - <?php echo language::translate('owner'); ?>
					 <?php endif ?>
				</td>
			</tr>
		<?php
			}
		?>
		</tbody>
	</table>
	<input type="hidden" name="updateToken" id="updateToken" value="<?php echo token::generate('update'); ?>">
	<input type="hidden" name="deleteToken" id="deleteToken" value="<?php echo token::generate('delete'); ?>">

	<?php echo $pagination['links']; ?>

	<button id="showAddAdmin" class="button"><?php echo language::translate('addAnAdmin'); ?></button>
	<div id="addAdminForm" class="form">
		<h2><?php echo language::translate('addAdmin'); ?></h2>
		<div class="formBody">
			<div id="addUserErrors" class="hide"></div>
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
				<label><?php echo language::translate('formLabelSex'); ?>:</label>
				<div id="formSexGroup" style="display:table-cell; width: 100%;">
					<label for="sexM" class="checkbox">
						<?php echo language::translate('formLabelSexM'); ?>
						<input type="radio" name="sex" id="sexM" class="sex" value="m">
					</label>

					<label for="sexF" class="checkbox">
						<?php echo language::translate('formLabelSexF'); ?>
						<input type="radio" name="sex" id="sexF" class="sex" value="f">
					</label>
				</div>
			</div>
			<div class="formRow">
				<label for="email"><?php echo language::translate('formLabelEmail'); ?>:</label>
				<input type="text" name="email" id="email">
			</div>
			<div class="formRow">
				<label for="groupID"><?php echo language::translate('userGroups'); ?>:</label>
				<select id="groupID">
					<option value="4">User</option>
					<option value="2">Admin</option>
				</select>
			</div>
			<div class="formRow">
				<label for="status"><?php echo language::translate('status'); ?>:</label>
				<select id="status">
					<option value="1"><?php echo language::translate('enable'); ?></option>
					<option value="0"><?php echo language::translate('disable'); ?></option>
				</select>
			</div>
			<div class="formRow buttonRow">
				<input type="hidden" name="singupToken" id="singupToken" value="<?php echo token::generate('singup'); ?>">
				<button id="singupBtn" class="button buttonMedium"><?php echo language::translate('add'); ?></button>
				<button id="cancelAddAdmin" class="button buttonMedium"><?php echo language::translate('cancel'); ?></button>
			</div>
		</div>
	</div>
</div>