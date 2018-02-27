<div id="mainContent">
	<h1>Alias-uri</h1>

	<table id="displayAliases">
		<thead>
			<tr>
				<th>ID:</th>
				<th>Unde se v-a aplica:</th>
				<th>Furnizor:</th>
				<th>Frabricant:</th>
				<th>Cauta:</th>
				<th>Array:</th>
				<th>Prefix:</th>
				<th>Inlocueste cu:</th>
				<th>Activ:</th>
				<th>Actiuni:</th>
			</tr>
		</thead>
		<tbody id="aliasesRows">
		<?php if ($rows): ?>
			<?php foreach ($rows as $row): ?>
				<tr class="aliasRow" data-id="<?php echo $row->id; ?>">
					<td class="aliasID"><?php echo $row->id; ?></td>
					<td class="aliasType">
						<select class="aliasTypes">
							<?php foreach ($types as $key => $value): ?>
								<option value="<?php echo $key; ?>"<?php echo ($key == $row->type) ? ' selected="selected"' : ''; ?>><?php echo $value; ?></option>
							<?php endforeach ?>
						</select>
					</td>
					<td class="aliasSupplier">
						<select class="aliasSupplierID">
							<option value="0">Toti furnizorii</option>
							<?php foreach ($suppliers as $supplierRow): ?>
								<option value="<?php echo $supplierRow->id; ?>"<?php echo ($supplierRow->id == $row->supplierID) ? ' selected="selected"' : ''; ?>><?php echo $supplierRow->name; ?></option>
							<?php endforeach ?>
						</select>
					</td>
					<td class="aliasManufacturer">
						<select class="aliasManufacturerID">
							<option value="0">Toti frabricatii</option>
							<?php foreach (oc::getManufacturers() as $makerRow): ?>
								<option value="<?php echo $makerRow->manufacturer_id; ?>"<?php echo ($makerRow->manufacturer_id == $row->manufacturerID) ? ' selected="selected"' : ''; ?>><?php echo $makerRow->name; ?></option>
							<?php endforeach ?>
						</select>
					</td>
					<td class="aliasSearch" contenteditable="true"><?php echo $row->search; ?></td>
					<td class="aliasArray"><input type="checkbox" class="aliasIsArray"<?php echo ($row->array == '1') ? ' checked="checked"' : ''; ?>></td>
					<td class="aliasPrefix"><input type="checkbox" class="aliasIsPrefix"<?php echo ($row->prefix == '1') ? ' checked="checked"' : ''; ?>></td>
					<td class="aliasReplaceWith" contenteditable="true" data-itemid="<?php echo $row->itemID; ?>" data-table="<?php echo $typeToTable[$row->type]['table']; ?>" data-column="<?php echo $typeToTable[$row->type]['column']; ?>" data-columnid="<?php echo $typeToTable[$row->type]['columnID']; ?>"><?php echo $row->replaceWith; ?></td>
					<td class="aliasActive"><input type="checkbox" class="aliasIsActive"<?php echo ($row->active == '1') ? ' checked="checked"' : ''; ?>></td>
					<td class="aliasActions">
						<span class="saveAndCancel hide">
							<a href="#" class="saveLink">Salveaza</a> /
							<a href="#" class="cancelLink">Anuleaza</a> /
						</span>
						<a href="#" class="deleteLink">Sterge</a>
					</td>
				</tr>
			<?php endforeach ?>
		<?php else: ?>
		<tr class="removeTR">
			<td colspan="10">Nu exista nici un alias inca!</td>
		</tr>
		<?php endif ?>
		</tbody>
		<tr>
			<td colspan="10"><button class="button buttonMediu" id="addAlias">Adauga</button></td>
		</tr>
	</table>
</div>

<script>
	var types = <?php echo json_encode($types); ?>;
	var typeToTable = <?php echo json_encode($typeToTable); ?>;
	var manufacturers = <?php echo json_encode(oc::getManufacturers()); ?>;
	var suppliers = <?php echo json_encode($suppliers); ?>;
</script>