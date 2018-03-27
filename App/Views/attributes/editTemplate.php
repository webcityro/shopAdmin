<div id="mainContent">
	<h1>Panou de admin / Sabloane Editare <?php echo $sablonName; ?></h1>

	<ul id="subCats-0" class="displayCats">
		<li id="catLabel">Categorii</li>
	<?php
		categoriesObj::setTamplate([
					'start' => '<ul class="subCats hide" id="subCats-{{category_id}}">',
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


	<div class="form hasTitle" id="editSablonForm">
		<h2 id="sablonTitle" contenteditable="true"><?php echo $sablonName; ?></h2>
		<div id="groups" data-sablonid="<?php echo $sablonID; ?>">
			<?php
			if ($sablonGroups) {
				// echo '<pre>'.print_r($sablonGroups, 1).'</pre>';
				foreach ($sablonGroups as $group) {
					$groupRow = $group['rows'];
			?>
					<fieldset class="group" data-id="<?php echo $groupRow->groupID; ?>" data-sort="<?php echo $groupRow->sort_order; ?>">
						<legend class="groupNameBar">
							<span class="groupID">#<?php echo $groupRow->groupID; ?></span>
							<span class="groupName" contenteditable="true"><?php echo $groupRow->name; ?></span>
						</legend>

						<table>
							<thead>
								<tr>
									<th>ID</th>
									<th>Caracteristica</th>
									<th>UM</th>
									<th>Descriere</th>
									<th>Extra info</th>
									<th>Ascunde label</th>
									<th>Ascunde?</th>
									<th>Ordine</th>
									<th>Actiuni</th>
								</tr>
							</thead>
							<tbody>
							<?php
								if ($group['attributes']) {
									foreach ($group['attributes'] as $attrRow) {
							?>
									<tr data-id="<?php echo $attrRow->attributeID; ?>" data-sort="<?php echo $attrRow->sort_order; ?>">
										<td class="crtID">#<?php echo $attrRow->attributeID; ?></td>
										<td class="crtName text" contenteditable="true"><?php echo $attrRow->name; ?></td>
										<td class="crtUM text" contenteditable="true"><?php echo $attrRow->um; ?></td>
										<td class="crtDesc text" contenteditable="true"><?php echo $attrRow->descriere; ?></td>
										<td class="crtExtraInfo text" contenteditable="true"><?php echo $attrRow->info; ?></td>
										<td class="crtHideLabel">
											<input type="checkbox" id="" class="crtIsHiddenLabel"<?php echo ($attrRow->hideLabel == '1') ? ' checked="checked"' : ''; ?>>
										</td>
										<td class="crtHidden">
											<input type="checkbox" id="" class="crtIsHidden"<?php echo ($attrRow->hide == '1') ? ' checked="checked"' : ''; ?>>
										</td>
										<td class="crtOrder">
											<div class="groupMenu">
												<div class="moveUp" data-type="attributes"></div>
												<div class="moveDown" data-type="attributes"></div>
											</div>
										</td>
										<td class="crtActions">
											<div class="saveOrCancel hide">
												<a class="saveLink" href="#">Salveaza</a> /
												<a class="candelLink" href="#">Anuleaza</a> /
											</div>
											<a href="#" data-id="<?php echo $attrRow->id; ?>" class="deleteCrt">Sterge</a>
										</td>
									</tr>
							<?php
									}
								}
							?>
							</tbody>
						</table>
						<div class="groupMenu">
							<button class="button buttonSmall addCaracteristicsBtn">Adauga o caracteristica</button>
							<div class="moveUp" data-type="attributesGroups"></div>
							<div class="moveDown" data-type="attributesGroups"></div>
							<button class="button buttonSmall deleteGroupBtn">Sterge grupul</button>
						</div>
					</fieldset>
			<?php
				}
			}
			?>
		</div>
		<div class="formRow buttonRow">
			<button id="addGroupBtn" class="button buttonSmall">Adauga un grup</button>
		</div>
	</div>
</div>

<script>
	$(function() {
		categories.preopen('#article-<?php echo $sablonID; ?>');
	});
</script>