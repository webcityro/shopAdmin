<div id="mainContent">
	<h1>Furnizori</h1>

	<ul id="displaySuppliers">
	<?php if ($rows): ?>
		<?php foreach ($rows as $row): ?>
			<li class="splRow" data-id="<?php echo $row->id; ?>">
				<span class="splID"><?php echo $row->id; ?></span>
				<span class="splName"><?php echo $row->name; ?></span>
				<span class="splSite"><a target="_blank" href="<?php echo $row->site; ?>"><?php echo $row->site; ?></a></span>
				<?php if (!empty($row->email)): ?>
					<span class="splEmail"><?php echo $row->email; ?></span>
				<?php endif ?>
				<span class="splActions">
					<a href="#" class="splEditLink">Editeaza</a> /
					<a href="#" class="splDeleteLink">Sterge</a>
				</span>
			</li>
		<?php endforeach ?>
	<?php else: ?>
		<li class="removeLI">Nu exista nici un furnizor inca!</li>
	<?php endif ?>
	</ul>

	<div class="form hasTitle" id="addSplForm">
		<h2 id="splFormTitle">Adauga furnizori</h2>
		<div class="formRow">
			<label for="splFormName">Nume*</label>
			<input type="text" id="splFormName">
		</div>
		<div class="formRow">
			<label for="splFormSite">Site*</label>
			<input type="text" id="splFormSite">
		</div>
		<div class="formRow">
			<label for="splFormCotactName">Persoana de contact</label>
			<input type="text" id="splFormCotactName">
		</div>
		<div class="formRow">
			<label for="splFormPhone1">Telefon 1</label>
			<input type="text" id="splFormPhone1">
		</div>
		<div class="formRow">
			<label for="splFormPhone2">Telefon 2</label>
			<input type="text" id="splFormPhone2">
		</div>
		<div class="formRow">
			<label for="splFormPhone3">Telefon 3</label>
			<input type="text" id="splFormPhone3">
		</div>
		<div class="formRow">
			<label for="splFormFax">Fax</label>
			<input type="text" id="splFormFax">
		</div>
		<div class="formRow">
			<label for="splFormEmail">Email</label>
			<input type="text" id="splFormEmail">
		</div>
		<div class="formRow buttonRow">
			<button class="button buttonMediu" id="saveSplBtn">Adauga!</button>
			<button class="button buttonMediu hide" id="cancelSplBtn">Anuleaza!</button>
		</div>
	</div>
</div>