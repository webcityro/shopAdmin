<div id="mainContent">
	<h1>Errori</h1>

	<table id="displayErrors">
		<thead>
			<tr>
				<th>ID:</th>
				<th>Tip eroare</th>
				<th>Unde a aparut:</th>
				<th>Momentul in care a aparut</th>
				<th>Actiuni</th>
			</tr>
		</thead>
		<tbody>
			<?php if ($rows): ?>
				<?php foreach ($rows as $row): ?>
					<tr class="header<?php echo ($row->read == '0') ? ' unread' : ''; ?>">
						<td class="errorID"><?php echo $row->id; ?></td>
						<td class="errorType"><?php echo $row->type; ?></td>
						<td class="errorPlace"><?php echo $row->place; ?></td>
						<td class="errorTime"><?php echo date('d.m.Y h:i:s', $row->setTime); ?></td>
						<td class="ErrorActions"><a href="#" class="errorShow">Vezi eroarea</a> / <a href="#" class="errorDelete" data-id="<?php echo $row->id; ?>">Sterge</a></td>
					</tr>
					<tr class="errorBody hide">
						<td colspan="5"><?php echo $row->message; ?></td>
					</tr>
				<?php endforeach ?>
			<?php else: ?>
				<tr><td colspan="5">Nu exista nicio eroare!</td></tr>
			<?php endif ?>
		</tbody>
	</table>
</div>