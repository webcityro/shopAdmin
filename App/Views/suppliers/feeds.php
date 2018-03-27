<div id="mainContent">
	<h1>Feed-uri</h1>

	<table id="displayFeeds">
		<thead>
			<th>ID</th>
			<th>Nume</th>
			<th>Furnizor</th>
			<th>Metoda Logare</th>
			<th>Tip feed</th>
			<th>Actiuni</th>
		</thead>
		<tbody>
			<?php if ($rows): ?>
				<?php foreach ($rows as $row): ?>
					<tr class="feedRow" data-id="<?php echo $row->id; ?>">
						<td class="feedID"><?php echo $row->id; ?></td>
						<td class="feedName"><?php echo $row->name; ?></td>
						<td class="feedSupplier">
							<?php
							foreach ($suppliers as $suppliersRow) {
								if ($suppliersRow->id == $row->supplierID) {
									echo $suppliersRow->name;
									break;
								}
							}
							?>
						</td>
						<td class="feedLoginType"><?php echo $row->loginType != 'none' ? $row->loginType : 'Fara'; ?></td>
						<td class="feedType"><?php echo $row->type; ?></td>
						<td class="feedActions">
							<a href="#" class="editLink">Editeaza</a> /
							<a href="#" class="deleteLink">Sterge</a>
						</td>
					</tr>
				<?php endforeach ?>
			<?php else: ?>
				<tr class="removeTR">
					<td colspan="4">Nu exista feed-uri!</td>
				</tr>
			<?php endif ?>
		</tbody>
	</table>

	<div class="form hasTitle" id="addFeedForm">
		<h2 id="feedFormTitle">Adauga feed</h2>
		<div class="formRow">
			<input type="checkbox" class="inputData" id="feedActive">
			<label for="feedActive">Activ <span></span></label>
		</div>
		<div class="formRow">
			<label for="feedFormName">Nume*</label>
			<input type="text" id="feedFormName" class="inputData">
		</div>
		<div class="formRow">
			<label for="feedSuppliersID">Furnizori*</label>
			<?php if ($suppliers): ?>
				<select id="feedSuppliersID" class="inputData">
					<option value="0">Alege furnizorul</option>
					<?php foreach ($suppliers as $suppliersRow): ?>
						<option value="<?php echo $suppliersRow->id; ?>"><?php echo $suppliersRow->name; ?></option>
					<?php endforeach ?>
				</select>
			<?php else: ?>
				<p>Nu exista nici un furnizor inca!</p>
			<?php endif ?>
		</div>
		<div id="runAfterIDFormRow" class="formRow">
			<label for="runAfterID">Ruleaqza dupa*</label>
			<select id="runAfterID" class="inputData">
				<option value="0">Acesta va rula primu</option>
			</select>
		</div>
		<div class="formRow">
			<label for="feedLoginType">Tip de logare</label>
			<select id="feedLoginType" class="inputData">
				<option value="none">Fara</option>
				<?php foreach ($loginTypes as $type): ?>
					<option value="<?php echo $type; ?>"><?php echo $type; ?></option>
				<?php endforeach ?>
			</select>
		</div>
		<div id="loginDetalies">
			<div class="formRow url curl soap nod hide">
				<label for="feedLoginURL">URL de logare</label>
				<input type="text" id="feedLoginURL" class="inputData">
			</div>
			<div class="formRow curl url nod hide">
				<label for="feedLoginUsername">Urilizator logare / API public kley</label>
				<input type="text" id="feedLoginUsername" class="inputData">
			</div>
			<div class="formRow curl url nod hide">
				<label for="feedLoginPassword">Parola Logare / API private key</label>
				<input type="text" id="feedLoginPassword" class="inputData">
			</div>
			<div class="formRow soap hide">
				<label for="feedLoginSoapDefineVars">Definitii de variabule</label>
				<textarea type="text" id="feedLoginSoapDefineVars" class="inputData" placeholder="Ex: $this->options = ['opt' => 'value'];"></textarea>
			</div>
			<div class="formRow soap hide">
				<label for="feedLoginSoapClientArgs">Argumente soapClient()</label>
				<input type="text" id="feedLoginSoapClientArgs" class="inputData">
				<em class="soap">Handler: $this->client</em>
			</div>
			<div class="formRow soap hide">
				<label for="feedLoginSoapLoginFunction">Functie logare</label>
				<input type="text" id="feedLoginSoapLoginFunction" class="inputData" placeholder="Ex: login:arg1, arg2, arg3">
				<em class="soap">Handler: $this->login</em>
			</div>
			<div class="formRow soap hide">
				<label for="feedLoginSoapResultsFunction">Functie fetch results</label>
				<input type="text" id="feedLoginSoapResultsFunction" class="inputData" placeholder="Ex: results:arg1, arg2, arg3">
			</div>
			<div class="formRow curl hide">
				<label for="feedLoginCurlUsernameFielt">Key post curl nume de utilizator</label>
				<input type="text" id="feedLoginCurlUsernameFielt" class="inputData" placeholder="Ex: username">
			</div>
			<div class="formRow curl hide">
				<label for="feedLoginCurlPasswordFielt">Key post curl Parola</label>
				<input type="text" id="feedLoginCurlPasswordFielt" class="inputData" placeholder="Ex: password">
			</div>
		</div>
		<div class="formRow">
			<label for="feedMainURL">URL principal</label>
			<input type="text" id="feedMainURL" class="inputData">
		</div>
		<div class="formRow">
			<label for="feedTestConnection">Testeaza conexiunea*</label>
			<button class="button buttonSmall" id="feedTestConnection">Testeaza...</button>
			<span id="feedConnectionStatus"></span>
		</div>
		<div class="formRow">
			<label for="feedType">Tip feed*</label>
			<select id="feedType" class="inputData">
				<option value="none">Alege tipul feed-ului</option>
				<?php foreach ($parserTypes as $type): ?>
					<option value="<?php echo $type; ?>"><?php echo $type; ?></option>
				<?php endforeach ?>
			</select>
		</div>
		<div class="formRow">
			<label for="feedStructure">Structura feed-ului*</label>
		</div>
		<div class="formRow buttonRow">
			<button class="button buttonMediu" id="saveFeedBtn">Adauga!</button>
			<button class="button buttonMediu" id="cancelFeedBtn">Anuleaza!</button>
		</div>
		<div id="feedStructure"></div>
	</div>
</div>

<script>
	var feedsListBySupplyers = <?php echo json_encode($feedsListBySupplyers); ?>;
</script>