<div id="mainContent">
	<h1><?php echo language::translate('pageTitle'); ?></h1>

	<table id="displayUserGroups" class="displayTable">
		<thead>
			<tr>
				<th><?php echo language::translate('idCollumn'); ?></th>
				<th class="name"><?php echo language::translate('formLabelFName'); ?></th>
				<th><?php echo language::translate('actionCollumn'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if ($groupRows): ?>
				<?php foreach ($groupRows as $groupRow): ?>
					<tr data-id="<?php echo $groupRow->id; ?>">
						<td><?php echo $groupRow->id; ?></td>
						<td><?php echo $groupRow->name; ?></td>
						<td>
							<a href="#" class="editGroup"><?php echo language::translate('edit'); ?></a> /
							<a href="#" class="deleteGroup"><?php echo language::translate('delete'); ?></a>
						</td>
					</tr>
				<?php endforeach ?>
			<?php else: ?>
				<tr class="noGroups"><td colspan="3"><?php echo language::translate('noGroupsAdded'); ?></td></tr>
			<?php endif ?>
		</tbody>
	</table>
	<input type="hidden" name="deleteToken" id="deleteToken" value="<?php echo token::generate('delete'); ?>">

	<?php echo $pagination['links']; ?>

	<button id="showAddGroupBtn" class="button"><?php echo language::translate('addGroup'); ?></button>
	<div id="addGroupForm" class="form hide">
		<h2 id="addGroupFormTitle"><?php echo language::translate('addGroup'); ?></h2>
		<div class="formBody">
			<div id="addUserGroupErrors" class="hide"></div>
			<div class="formRow">
				<label for="groupName"><?php echo language::translate('formLabelFName'); ?>:</label>
				<input type="text" id="groupName">
			</div>
			<fieldset id="sectionPermitions">
				<legend><?php echo language::translate('groupPermissions'); ?></legend>
				<?php
					$jsonPermissions = [];
					$x = -1;

					foreach ($siteSections as $key => $label):
						$x++;
				?>
					<div class="formRow">
						<label><?php echo $label; ?></label>
						<div class="permissions" data-section="<?php echo $key; ?>">
							<label for="all_<?php echo $x; ?>">
								<?php echo language::translate('all'); ?>
								<input type="checkbox" id="all_<?php echo $x; ?>" class="groupPermission" value="all">
							</label>

							<?php
								foreach ($permissions as $action => $actionLabel):
									$jsonPermissions[$key][$action] = false;
							?>
								<label for="<?php echo $action.'_'.$x; ?>">
									<?php echo $actionLabel; ?>
									<input type="checkbox" id="<?php echo $action.'_'.$x; ?>" class="groupPermission" value="<?php echo $action; ?>">
								</label>
							<?php endforeach ?>
						</div>
					</div>
				<?php endforeach ?>
			</fieldset>
			<div class="formRow buttonRow">
				<input type="hidden" name="addUserGroupToken" id="addUserGroupToken" value="<?php echo token::generate('addGroup'); ?>">
				<button id="addGroupBtn" class="button buttonMedium"><?php echo language::translate('add'); ?></button>
				<button id="cancelAddGroup" class="button buttonMedium"><?php echo language::translate('cancel'); ?></button>
			</div>
		</div>
	</div>
</div>

<script>
	var permissions = <?php echo json_encode($jsonPermissions); ?>;
	var sections = <?php echo json_encode($siteSections); ?>;
</script>