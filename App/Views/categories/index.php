<div id="mainContent">
	<h1>Panou de admin / Categorii</h1>

	<ul id="subCats-0" class="displayCats">
	<?php
		categoriesObj::setTamplate([
					'start' => '<ul class="subCats hide" id="subCats-{{parent_id}}">',
					'repeat' =>'<li data-sort="{{sort_order}}">
						<div class="catRow">
							<img src="'.config::get('site/domain').'../image/{{image}}" class="catRowImage">
							<div class="catName" data-id="{{category_id}}">{{name}}</div>
							<div class="catLinks">
								ID: {{category_id}}
								<a href="#" class="editCatLink" data-id="{{category_id}}">Editeaza</a> /
								<a href="#" class="moveCatLink" data-id="{{category_id}}">Muta</a> /
								<a href="#" data-id="{{category_id}}" class="deleteCatLink">Sterge</a>
								<ul class="deleteMenu hide">
									<li><a href="#" class="deleteCat" data-what="onleThis">Doar asta</a></li>
									<li><a href="#" class="deleteCat" data-what="articles">Doar artocolele</a></li>
									<li><a href="#" class="deleteCat" data-what="firstCatLevel">Doar primul nivel de categorii</a></li>
									<li><a href="#" class="deleteCat" data-what="allCats">Doar categoriile</a></li>
									<li><a href="#" class="deleteCat" data-what="all">Toate</a></li>
								</ul>
							</div>
						</div>
						{{subcats}}
					</li>',
				'end' => '</ul>'
					]);
		echo categoriesObj::render();
	?>
		<li class="removeSortable" id="principalCatLevel">
			<div class="catRow selected">
				<div class="catName" data-id="0">Categorie principala</div>
			</div>
		</li>
		<li class="moveCathere removeSortable hide" data-id="0">Muta aici</li>
	</ul>


	<div class="form hasTitle" id="addCatsForm">
		<h2 id="addCatsFormTitla">Adauga categorii</h2>
		<div class="formRow">
			<label for="catImage">Imagine</label>
			<img src="<?php echo config::get('site/domain'); ?>../image/no_image.png" alt="Alege o imagine!" id="catImage" class="showImageSearch" style="cursor: pointer; max-width: 100px; max-height: 100px;">
		</div>
		<div class="formRow">
			<label for="catName">Nume*</label>
			<input type="text" name="" id="catName">
		</div>
		<div class="formRow">
			<label for="catDesc">Descriere</label>
			<textarea name="" id="catDesc"></textarea>
		</div>
		<div class="formRow">
			<label for="catMetaTitle">Titlu meta tag*</label>
			<input type="text" name="" id="catMetaTitle">
		</div>
		<div class="formRow">
			<label for="catMetaDesc">Descriere meta tag</label>
			<textarea name="" id="catMetaDesc"></textarea>
		</div>
		<div class="formRow">
			<label for="catMetaKeywords">Keywords meta tag</label>
			<input type="text" name="" id="catMetaKeywords">
		</div>
		<div class="formRow">
			<input type="checkbox" name="" id="catStatus">
			<label for="catStatus">Activa
				<span></span>
			</label>
		</div>
		<div class="formRow">
			<input type="checkbox" name="" id="catTop">
			<label for="catTop">Sa aparaa in bara de sus
				<span></span>
			</label>
		</div>
		<fieldset>
			<legend style="text-align: center;">Presetari (Optionale)</legend>
			<div class="formRow">
				<label for="catWidth">Latime</label>
				<input type="text" id="catWidth">
			</div>
			<div class="formRow">
				<label for="catLenght">Lungime</label>
				<input type="text" id="catLenght">
			</div>
			<div class="formRow">
				<label for="catLengthClassID">clasa lungime</label>
				<select id="catLengthClassID">
					<option value="">Alege clasa de lungime</option>
					<?php foreach (oc::getLengthClass() as $lengthClassRow): ?>
						<option value="<?php echo $lengthClassRow->id; ?>"><?php echo $lengthClassRow->title; ?></option>
					<?php endforeach ?>
				</select>
			</div>
			<div class="formRow">
				<label for="catHeight">Inaltime</label>
				<input type="text" id="catHeight">
			</div>
			<div class="formRow">
				<label for="catWeight">Greutate</label>
				<input type="text" id="catWeight">
			</div>
			<div class="formRow">
				<label for="catWeightClassID">clasa greutate</label>
				<select id="catWeightClassID">
					<option value="">Alege clasa de greutate</option>
					<?php foreach (oc::getWeightClass() as $weightClassRow): ?>
						<option value="<?php echo $weightClassRow->id; ?>"><?php echo $weightClassRow->title; ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</fieldset>
		<div class="formRow buttonRow">
			<button class="button buttonMediu" id="addCatBtn">Adauga!</button>
		</div>
	</div>
</div>