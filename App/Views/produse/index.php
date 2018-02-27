<div id="mainContent">
	<h1>Panou de admin / Produse</h1>

	<ul id="subCats-0" class="displayCats">
		<li id="catLabel">Categorii</li>
	<?php
		categoriesObj::setTamplate([
					'start' => '<ul class="subCats hide" id="subCats-{{parent_id}}">',
					'repeat' =>'<li>
						<div class="catRow" data-id="{{category_id}}">
							<img src="'.config::get('site/domain').'../image/{{image}}" class="catRowImage">
							<input type="radio" name="category_id" value="{{category_id}}" class="catSelectID">
							<div class="catName">{{name}}</div>
						</div>
						{{subcats}}
					</li>',
				'end' => '</ul>'
					]);
		echo categoriesObj::render(function ($catID) use($productsTree) {
			$return = '';

			if (!empty($productsTree[$catID])) {
				foreach ($productsTree[$catID] as $productRow) {
					$return .= '<li id="article-'.$productRow->id.'" class="article product">';
					$return .= '<img src="'.config::get('site/domain').'../image/'.$productRow->image.'" class="articleImage">';
					$return .= '<span>'.$productRow->name.'</span> - ID: '.$productRow->id;
					$return .= ' - <div class="articleLinks">';
					$return .= '<a href="#" class="editPrd" data-id="'.$productRow->id.'">Editeaza</a> / ';
					$return .= '<a href="#" class="deletePrd" data-id="'.$productRow->id.'">Sterge</a>';
					$return .= '</div>';
					$return .='</li>';
				}
			}
			return $return;
		});
	?>
	</ul>


	<div class="form hasTitle" id="addProductsForm">
		<h2 id="addProductsFormTitla">Adauga un prodrus</h2>
		<div class="formRow buttonRow">
			<button class="button buttonSmall formPart selected" data-part="basic">De baza</button>
			<button class="button buttonSmall formPart" data-part="phizical">Dimensiuni / greutate</button>
			<button class="button buttonSmall formPart" data-part="attributes">Atribute</button>
			<button class="button buttonSmall formPart" data-part="images">Imagini</button>
		</div>
		<div id="formGroupBasic" class="formGroup">
			<div class="formRow">
				<label for="prdName">Nume*</label>
				<input type="text" name="" id="prdName" class="dataInput">
			</div>
			<div class="formRow">
				<label for="prdMakerID">Producator*</label>
				<select id="prdMakerID" class="dataInput">
					<option value="">Alege producatorul</option>
					<?php foreach (oc::getManufacturers() as $makerRow): ?>
						<option value="<?php echo $makerRow->manufacturer_id; ?>"><?php echo $makerRow->name; ?></option>
					<?php endforeach ?>
				</select>
			</div>
			<div class="formRow">
				<label for="prdModel">Model*</label>
				<input type="text" name="" id="prdModel" class="dataInput">
			</div>
			<div class="formRow">
				<label for="prdCode">Cod produs*</label>
				<input type="text" name="" id="prdCode" class="dataInput">
			</div>
			<div class="formRow">
				<label for="prdDesc">Descriere</label>
				<textarea name="" id="prdDesc" class="dataInput"></textarea>
			</div>
			<div class="formRow">
				<label for="prdMetaTitle">Titlu meta tag*</label>
				<input type="text" name="" id="prdMetaTitle" class="dataInput">
			</div>
			<div class="formRow">
				<label for="prdMetaDesc">Descriere meta tag</label>
				<textarea name="" id="prdMetaDesc" class="dataInput"></textarea>
			</div>
			<div class="formRow">
				<label for="prdMetaKeywords">Keywords meta tag</label>
				<input type="text" name="" id="prdMetaKeywords" class="dataInput">
			</div>
			<div class="formRow">
				<label for="prdStoc">Stoc*</label>
				<input type="text" name="" id="prdStoc" class="dataInput">
			</div>
			<div class="formRow">
				<label for="prdPrice">Pret*</label>
				<input type="text" name="" id="prdPrice" class="dataInput">
			</div>
			<div class="formRow">
				<input type="checkbox" name="" id="prdStatus" class="dataInput">
				<label for="prdStatus">Activa
					<span></span>
				</label>
			</div>
		</div>
		<div id="formGroupPhizical" class="formGroup hide">
			<div class="formRow">
				<label for="prdWidth">Latime*</label>
				<input type="text" id="prdWidth" class="dataInput">
			</div>
			<div class="formRow">
				<label for="prdLenght">Lungime*</label>
				<input type="text" id="prdLenght" class="dataInput">
			</div>
			<div class="formRow">
				<label for="prdLengthClassID">clasa lungime*</label>
				<select id="prdLengthClassID" class="dataInput">
					<option value="">Alege clasa de lungime</option>
					<?php foreach (oc::getLengthClass() as $lengthClassRow): ?>
						<option value="<?php echo $lengthClassRow->id; ?>"><?php echo $lengthClassRow->title; ?></option>
					<?php endforeach ?>
				</select>
			</div>
			<div class="formRow">
				<label for="prdHeight">Inaltime*</label>
				<input type="text" id="prdHeight" class="dataInput">
			</div>
			<div class="formRow">
				<label for="prdWeight">Greutate*</label>
				<input type="text" id="prdWeight" class="dataInput">
			</div>
			<div class="formRow">
				<label for="prdWeightClassID">clasa greutate*</label>
				<select id="prdWeightClassID" class="dataInput">
					<option value="">Alege clasa de greutate</option>
					<?php foreach (oc::getWeightClass() as $weightClassRow): ?>
						<option value="<?php echo $weightClassRow->id; ?>"><?php echo $weightClassRow->title; ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
		<div id="formGroupAttributes" class="formGroup hide">
			<div id="sablonsRow" class="formRow">
				<label for="sablonID">Sabloane</label>
				<span id="sablonSelect">Alege o categorie!</span>
			</div>
			<ul id="attributeStates" class="hide">
				<li>
					<div class="stateDot" id="original"></div>
					Nu apartine unui sablon.
				</li>
				<li>
					<div class="stateDot" id="sablon"></div>
					Apartne unui sablon.
				</li>
				<li>
					<div class="stateDot" id="sablonNotUsed"></div>
					Apartne unui sablon dar nu este folosit.
				</li>
			</ul>
			<div id="groups"></div>
			<div class="formRow buttonRow">
				<button id="addGroupsBtn" class="button buttonSmall">Adauga un grup</button>
			</div>
		</div>
		<div id="formGroupImages" class="formGroup hide">
			<div id="images"></div>
			<div class="formRow buttonRow">
				<button id="addImagesBtn" class="button buttonSmall">Adauga imagini</button>
			</div>

			<div id="imagesSearchForm" class="hide">
				<a href="#" id="closeBtn">X</a>
				<div id="searchBar">
					<input type="text" id="searchQuery">
					<button id="searchBtn">Cauta</button>
				</div>
				<div id="searchResualts"></div>
				<button id="aplyImagesBtn" class="button buttonSmall">Aplica imaginile</button>
			</div>
		</div>
		<div class="formRow buttonRow">
			<button class="button buttonMediu" id="cancelPrdBtn">Anuleaza!</button>
			<button class="button buttonMediu" id="savePrdBtn">Adauga!</button>
		</div>
	</div>
</div>

<script>
	$(function() {
		// categories.preopen('#subCats-102');
	});
</script>