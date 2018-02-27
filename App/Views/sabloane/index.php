<div id="mainContent">
	<h1>Panou de admin / Sabloane</h1>

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
		echo categoriesObj::render(function ($catID) use($sabloansTree) {
			$return = '';

			if (!empty($sabloansTree[$catID])) {
				foreach ($sabloansTree[$catID] as $sablonRow) {
					$return .= '<li id="article-'.$sablonRow->id.'" class="article sablon"><span>'.$sablonRow->name.'</span> - ID: '.$sablonRow->id;
					$return .= ' - <div class="articleLinks">';
					$return .= '<a href="'.config::get('site/domain').'sabloane/edit/'.$sablonRow->id.'" class="editSablon">Editeaza</a> / ';
					$return .= '<a href="#" class="deleteSablon" data-id="'.$sablonRow->id.'">Sterge</a>';
					$return .= '</div>';
					$return .='</li>';
				}
			}
			return $return;
		});
	?>
	</ul>


	<div class="form hasTitle" id="addSablonForm">
		<h2>Adauga sabloane</h2>
		<div class="formRow">
			<label for="sablonName">Numele sablonului</label>
			<input type="text" name="" id="sablonName">
		</div>
		<div class="formRow buttonRow">
			<button class="button buttonMediu" id="addSablonBtn">Adauga!</button>
		</div>
	</div>
</div>