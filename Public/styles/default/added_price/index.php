<div id="mainContent">
	<h1>Adaos preturi</h1>

	<table id="displayAddedPrice">
		<thead>
			<tr>
				<th>ID:</th>
				<th>pret minim:</th>
				<th>Pret maxim:</th>
				<th>Procent adaos (%):</th>
				<th>Actiuni:</th>
			</tr>
		</thead>
		<tbody id="addedPriceRows">
		<?php if ($rows): ?>
			<?php foreach ($rows as $row): ?>
				<tr class="apRow" data-id="<?php echo $row->id; ?>">
					<td class="apID"><?php echo $row->id; ?></td>
					<td class="apMin" contenteditable="true"><?php echo $row->min; ?></td>
					<td class="apMax" contenteditable="true"><?php echo $row->max; ?></td>
					<td class="apPrecent" contenteditable="true"><?php echo $row->precent; ?></td>
					<td class="apActions">
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
			<td colspan="5">Nu exista nici un adaos de pret inca!</td>
		</tr>
		<?php endif ?>
		</tbody>
		<tr>
			<td colspan="5"><button class="button buttonMediu" id="addAP">Adauga</button></td>
		</tr>
	</table>
</div>