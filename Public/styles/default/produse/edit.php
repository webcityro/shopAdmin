<div id="mainContent">
	<h1>Panou de admin / Sabloane Editare <?php echo $sablonName; ?></h1>

	<ul id="subCats-0" class="displayCats">
		<li id="catLalel">Categori</li>
	<?php
		categoriesObj::setTamplate([
					'start' => '<ul class="subCats hide" id="subCats-{{category_id}}">',
					'repeat' =>'<li>
						<div class="catRow" data-id="{{category_id}}">
							<img src="'.config::get('site/domain').'../image/{{image}}" class="catRowImage">
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
					$return .= '<li id="article-'.$productRow->id.'" class="article product">';
					$return .= '<img src="'.config::get('site/domain').'../image/'.$productRow->image.'" class="articleImage">';
					$return .= '<span>'.$productRow->name.'</span> - ID: '.$productRow->id;
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
				foreach ($sablonGroups as $group) {
					$groupRow = $group['rows'];
			?>
					<fieldset class="group" data-id="<?php echo $groupRow->id; ?>" data-sort="<?php echo $groupRow->sort; ?>">
						<legend class="groupNameBar">
							<span class="groupID">#<?php echo $groupRow->id; ?></span>
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
								if ($group['caracteristics']) {
									foreach ($group['caracteristics'] as $crtRow) {
							?>
									<tr data-id="<?php echo $crtRow->id; ?>" data-sort="<?php echo $crtRow->sort; ?>">
										<td class="crtID">#<?php echo $crtRow->id; ?></td>
										<td class="crtName text" contenteditable="true"><?php echo $crtRow->label; ?></td>
										<td class="crtUM text" contenteditable="true"><?php echo $crtRow->um; ?></td>
										<td class="crtDesc text" contenteditable="true"><?php echo $crtRow->descriere; ?></td>
										<td class="crtExtraInfo text" contenteditable="true"><?php echo $crtRow->info; ?></td>
										<td class="crtHideLabel">
											<input type="checkbox" id="" class="crtIsHiddenLabel"<?php echo ($crtRow->hideLabel == '1') ? ' checked="checked"' : ''; ?>>
										</td>
										<td class="crtHidden">
											<input type="checkbox" id="" class="crtIsHidden"<?php echo ($crtRow->hide == '1') ? ' checked="checked"' : ''; ?>>
										</td>
										<td class="crtOrder">
											<div class="groupMenu">
												<div class="moveUp" data-type="caracteristics"></div>
												<div class="moveDown" data-type="caracteristics"></div>
											</div>
										</td>
										<td class="crtActions">
											<div class="saveOrCancel hide">
												<a class="saveLink" href="#">Salveaza</a> /
												<a class="candelLink" href="#">Anuleaza</a> /
											</div>
											<a href="#" data-id="<?php echo $crtRow->id; ?>" class="deleteCrt">Sterge</a>
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
							<div class="moveUp" data-type="groups"></div>
							<div class="moveDown" data-type="groups"></div>
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
		categories.preopen('#subCats-<?php echo $sablonID; ?>');
	});
</script>