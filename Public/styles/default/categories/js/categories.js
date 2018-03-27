$(function(e) {
	var addCatBtnID    		= '#addCatBtn';
	var cancelEditCatID		= '#cancelEdit';
	var subCats0ID			= '#subCats-0';
	var changeImageLinkID   = 'changeImage';
	var deleteImageLinkID   = 'deleteImage';
	var catImageID			= '#catImage';
	// var subCatsClass		= '.subCats';
	var showImageSearchClass 		= '.showImageSearch';
	var addNewCatClass 		= '.addNewCat';
	var deleteCatMenuClass	= '.deleteMenu';
	var deleteCatClass 		= '.deleteCat';
	// var catRowClass   		= '.catRow';
	var catNameClass   		= '.catName';
	var catLinksClass  		= '.catLinks';
	var deleteCatLinkClass  = '.deleteCatLink';
	var editCatLinkClass	= '.editCatLink';
	var moveCatLinkClass	= '.moveCatLink';
	var moveCathereClass	= '.moveCathere';
	var ulSortabeHandleClass	= '.ui-sortable-handle';
	var removeSortableClass	= '.removeSortable';
	var deleteCatLink  		= $(deleteCatLink);
	var addCatsFormTitla	= $('#addCatsFormTitla');
	var catImage       		= $(catImageID);
	var catName        		= $('#catName');
	var catDesc        		= $('#catDesc');
	var catMetaTitle   		= $('#catMetaTitle');
	var catMetaDesc   		= $('#catMetaDesc');
	var catMetaKeywords		= $('#catMetaKeywords');
	var catStatus			= $('#catStatus');
	var catTop				= $('#catTop');
	var catWidth			= $('#catWidth');
	var catLenght			= $('#catLenght');
	var catLengthClassID	= $('#catLengthClassID');
	var catHeight			= $('#catHeight');
	var catWeight			= $('#catWeight');
	var catWeightClassID	= $('#catWeightClassID');
	var addCatBtn      		= $(addCatBtnID);
	var displayCats    		= $('.displayCats');
	var deleteCat 	   		= $(deleteCatClass);
	var deleteCatMenu     	= $('.deleteMenu');
	// var subCats				= $(subCatsClass);
	var moveCatLink			= $('a'+moveCatLinkClass);
	var moveCathere			= $('li'+moveCathereClass);
	var principalCatLevel	= $('#principalCatLevel');
	// var docBody   	   		= $(document.body);
	var tempCatValue   		= '';
	var moveCatID   		= '';
	var catID 		  		= 0;
	var editCatID	  		= 0;
	var mover;
	var oldSort;
	var newSort;
	var run;
	var imgLink = '';
	var imgThumb = '';
	var imgURL = domain+'../image/';
	var imgThumbURL = imgURL+'cache/';
	var noImage = imgURL+'no_image.png';
	var defaultCatImage = catImage.attr('src');

	var imageSearch = new googleImageSearch({
		button: showImageSearchClass,
		multiselect: false
	});
	var imgViewer = new imageViewer();

	imgViewer.init();

	imageSearch.setCallbeck(function(link, src) {
		addImage(link, src);
		if (!imgViewer.displayImageDiv.is(':hidden')) {
			imgViewer.image.img.attr('src', link);
		}
	});
	imageSearch.init();

	catName.on('change', function(e) {
		var value = $(this).val();
		imageSearch.setInitialValue(value);

		if (catMetaTitle.val().length == 0) {
			catMetaTitle.val(value);
		}
	});

	docBody.on('click', '#'+deleteImageLinkID, function(e) {
		var self = $(this);
		var id = self.data('id');

		catImage.addClass(showImageSearchClass.replace('.', '')).attr('src', defaultCatImage);
		imgViewer.remove(id);
		imgThumb = '';
		imgLink = '';
	});

	docBody.on('click', addCatBtnID, function(e) {
		var catNameValue = $.trim(catName.val());
		var catDescValue = $.trim(catDesc.val());
		var catMetaTitleValue = $.trim(catMetaTitle.val());
		var catMetaDescValue = $.trim(catMetaDesc.val());
		var catMetaKeywordsValue = $.trim(catMetaKeywords.val());
		var catStatusValue = (catStatus.is(':checked')) ? 1 : 0;
		var catTopValue = (catTop.is(':checked')) ? 1 : 0;
		var catWidthValue = $.trim(catWidth.val());
		var catLenghtValue = $.trim(catLenght.val());
		var catLenghtClassIDValue = catLengthClassID.val();
		var catHeightValue = $.trim(catHeight.val());
		var catweightValue = $.trim(catWeight.val());
		var catweightClassIDValue = catWeightClassID.val();
		var catTable = $('#subCats-'+categories.id);
		var sort = (editCatID == 0) ? ((categories.id == 0) ? catTable.children().length -3 : catTable.children().length) : '';

		if (catNameValue.length > 0 && catMetaTitleValue.length > 0) {
			$.post(config.domain+'categories/'+((editCatID == 0) ? 'add' : 'edit/'+editCatID), {
				catParentID: categories.id,
				catName: catNameValue,
				catDesc: catDescValue,
				catMetaTitle: catMetaTitleValue,
				catMetaDesc: catMetaDescValue,
				catMetaKeywords: catMetaKeywordsValue,
				catStatus: catStatusValue,
				catTop: catTopValue,
				catWidth: catWidthValue,
				catLenght: catLenghtValue,
				catLengthClassID: catLenghtClassIDValue,
				catHeight: catHeightValue,
				catWeight: catweightValue,
				catWeightClassID: catweightClassIDValue,
				sort: sort,
				imageLink: imgLink,
				imageThumb: imgThumb
			}, function(output) {
				if (output.status == 'ok') {
					if (editCatID != 0) {
						var thisCatName = $(categories.nameClass+'[data-id="'+editCatID+'"');
						thisCatName.text(catNameValue);

						var img = (output.image.length > 0) ? imgThumbURL+output.image : noImage;
						thisCatName.prev().attr('src', img);
						$(cancelEditCatID).click();
					} else if (categories.id == 0) {
						catTable.children().eq(catTable.children().length - 3).after(addHtmlCat(output, catNameValue));
					} else {
						catTable.append(addHtmlCat(output, catNameValue, sort));
					}
					clearForm();
				} else if (output.status == 'error') {
					alert(output.msg);
				}
			}, 'json');
		} else {
			alert('Campurile marcate cu (*) sunt obigatorii!');
		}
	});

	docBody.on('click', editCatLinkClass, function(e) {
		e.preventDefault();

		if (editCatID != 0) {
			clearForm();
		}
		var self = $(this);
		editCatID = self.data('id');

		$.get(config.domain+'categories/getForEdit/'+editCatID, function(data) {
			if (data.status == 'ok') {
				addCatsFormTitla.text('Editeaza categoria '+data.row.name);
				catName.val(data.row.name);
				catDesc.val(data.row.description);
				catMetaTitle.val(data.row.meta_title);
				catMetaDesc.val(data.row.meta_description);
				catMetaKeywords.val(data.row.meta_keyword);
				catStatus.prop('checked', ((data.row.status == 1) ? true : false));
				catTop.prop('checked', ((data.row.top == 1) ? true : false));
				imageSearch.setInitialValue(data.row.name);

				if (data.row.presets != false) {
					catWidth.val(data.row.presets.width);
					catLenght.val(data.row.presets.lenght);
					catHeight.val(data.row.presets.height);
					catWeight.val(data.row.presets.weight);
					catLengthClassID.val(data.row.presets.lenghtClassID);
					catWeightClassID.val(data.row.presets.weightClassID);
				}

				if (data.row.image != '') {
					addImage(data.row.image, data.row.image, editCatID);
				}

				var cancelLink = $('<button />');

				cancelLink.addClass('button buttonMediu').attr({id: cancelEditCatID.replace('#', '')}).css({marginRight: '15px'}).text('Anuleaza');
				$(addCatBtnID).before(cancelLink).before('<br id="removeMe">').text('Salveaza');
			} else if (data.status == 'error') {
				alert('Nu S-a gasit categoria!')
			}
		}, 'json');
	});

	docBody.on('click', cancelEditCatID, function(e) {
		e.preventDefault();
		addCatsFormTitla.text('Adauga categorii');
		clearForm();
		$(addCatBtnID).text('Adauga!');
		$('#removeMe').remove();
		$(this).remove();
		editCatID = 0;
	});

	docBody.on('click', categories.nameClass, function(e) {
		var self = $(e.target);
		categories.id = self.data('id');
		var subCats = $('#subCats-'+categories.id);
		subCats.toggleClass('hide');

		if (self.parent().hasClass('selected')) {
			$('.selected').removeClass('selected');
			var thisCatRow = self.parent().parent().parent().prev();

			if (categories.id == 0) {
				var parentUL = e.parent().parent();
				parentUL.children().eq(parentUL.children().length - 2).children().first().addClass('selected');
			} else {
				categories.id = thisCatRow.children().first().data('id');
				thisCatRow.addClass('selected');
			}
		} else {
			$('.selected').removeClass('selected');
			self.parent().addClass('selected');
		}
	});

	docBody.on('click', deleteCatLinkClass, function(e) {
		e.preventDefault();
		$(this).next(deleteCatMenu).toggleClass('show hide');
	});

	docBody.on('click', function(e) {
		var thisClass = $(e.target).attr('class');
		if (thisClass != 'deleteMenu' && thisClass != 'deleteCat' && thisClass != 'deleteCatLink') {
			$('.deleteMenu.show').removeClass('show').addClass('hide');
		}
	});

	docBody.on('click', deleteCatClass, function(e) {
		e.preventDefault();
		var self 		 = $(this);
		var id 		 = self.parent().parent().prev('a').data('id');
		var url = config.domain+'categories/delete/'+id;
		var whatToDelete = self.data('what');

		if (confirm('Vrei sa stergi '+self.text()+'?')) {
			$.get(url+'/'+whatToDelete, function(output) {
				console.log(output);
				if (output.status == 'ok') {
					var id = url.split('/').pop();
					var thisUL = $('#subCats-'+output.parentID);
					var thisSubCats = $('#subCats-'+id);
					var thisLI = thisSubCats.parent();

					switch (whatToDelete) {
						case 'onleThis':
							var sort = resortAfterDelete(thisLI);
							console.log('sort', sort);
							if (thisSubCats.children().length > 0) {
								thisSubCats.children().each(function(e) {
									var e = $(this);
									e.attr('data-sort', sort);
									thisUL.append(e);
									sort++;
								});
							}
							thisLI.remove();
						break;
						case 'articles':

						break;
						case 'firstCatLevel':
							thisSubCats.children().each(function(e) {
								var e = $(this);
								var tsc = e.children(categories.subCatsClass).children();
								console.log('tsc', tsc);
								thisSubCats.append(tsc);
								e.remove();
							});
							var sort = -1;

							thisSubCats.children().each(function(e) {
								var e = $(this);
								sort++;
								e.attr('data-sort', sort);
							});
						break;
						case 'allCats':
						case 'all':
							resortAfterDelete(thisLI);
							thisLI.remove();
						break;
					}
				} else if (output.status == 'error') {
					alert(output.msg);
				}
			}, 'json');
		}
		self.parent().parent().hide();
	});

	docBody.on('click', moveCatLinkClass, function(e) {
		moveCatID = $(this).data('id');
		mover = $(this).parent().parent().parent();
		$(moveCatLinkClass).addClass('moveCathere').removeClass('moveCatLink').text('Muta aici');
		moveCathere.removeClass('hide');
		$(this).addClass('cancelMove').removeClass('moveCatLink').text('Anuleaza mutarea');
	});

	docBody.on('click', '.cancelMove', function() {
		cancelMove(this);
	});

	docBody.on('click', '.moveCathere', function(e) {
		var self = $(this);
		var moveToID = self.data('id');

		$.get(config.domain+'categories/moveCat/'+moveCatID+'/'+moveToID, function(output) {
			if (output.status == 'ok') {
				var moveToUL = $('#subCats-'+moveToID);
				resortAfterDelete(mover);
				mover.remove();

				if (moveToID != 0) {
					moveToUL.append(mover);
				} else {
					principalCatLevel.before(mover);
				}

				newSort = mover.index();
				mover.attr('data-sort', newSort);
				cancelMove('.cancelMove');
			} else if (output.status == 'error') {
				alert(output.msg);
			}
		}, 'json');
	});

	$(subCats0ID+', '+categories.subCatsClass).sortable({
		containment: 'parent',
		tolerance: 'pointer',
		revert: true,
		opacity: 0.65,
		cancel: removeSortableClass
	});

	docBody.on('sortstart', subCats0ID+', '+categories.subCatsClass , function(event, ui) {
		oldSort = ui.item.index();
		run = true;
	});

	docBody.on('sortupdate', subCats0ID+', '+categories.subCatsClass , function(event, ui) {
		// console.log('this', $(this));
		var thisLI = ui.item;
		var parentUL = thisLI.parent();
		var parentID = parentUL.attr('id').split('-')[1];
		var id = thisLI.children(categories.rowClass).children(categories.nameClass).data('id');
		newSort = thisLI.index();
		thisLI.attr('data-sort', newSort);
		// console.log('item', thisLI);
		// console.log('id', id);
		// console.log('oldSort', oldSort);
		// console.log('new Sort', newSort);
		// console.log('parentID', parentID);
		// console.log('event', event);
		// console.log('ui', ui);

		if (run) {
			run = false;

			$.get(config.domain+'categories/sort/'+oldSort+'/'+newSort+'/'+id+'/'+parentID,
				function(data) {
					if (data.status == 'ok') {
						var allLIs = parentUL.children();
						var liCount = allLIs.length;

						if (newSort < oldSort) {
							for (var x = newSort; x <= oldSort; x++) {
								allLIs.eq(x).attr('data-sort', x);
							}
						} else {
							for (var x = oldSort; x < newSort; x++) {
								allLIs.eq(x).attr('data-sort', x);
							}
						}
					} else if (data.status == 'error') {
						alert(data.msg);
					}

					thisLI.removeAttr('style');
				}, 'json');
		}
	});

	function clearForm () {
		catName.val('');
		catDesc.val('');
		catMetaTitle.val('');
		catMetaDesc.val('');
		catMetaKeywords.val('');
		catStatus.prop('checked', false);
		catTop.prop('checked', false);
		catWidth.val('');
		catLenght.val('');
		catHeight.val('');
		catWeight.val('');
		catLengthClassID.val('');
		catWeightClassID.val('');
		if (imgLink != '') {
			catImage.addClass(showImageSearchClass.replace('.', '')).attr('src', defaultCatImage);
			imgViewer.remove(editCatID);
		}
		imageSearch.setInitialValue('');
		imgThumb = '';
		imgLink = '';
	}

	function cancelMove (e) {
		moveCatID = '';
		mover = '';
		$('a'+moveCathereClass).removeClass('moveCathere').addClass('moveCatLink').text('Muta');
		$(e).removeClass('cancelMove').addClass('moveCatLink').text('Muta');
		moveCathere.addClass('hide');
	}

	function addHtmlCat (output, catName, sort) {
		var newCatRow 		 				  = $('<div />');
		var newCatName 		 				  = $('<div />');
		var newCatLinks 	 				  = $('<div />');

		var newCatImage						  = $('<img />')

		var newCatDeleteMenu 				  = $('<ul />');
		var newSubCatUL		 				  = $('<ul />');

		var newCatLI 		 				  = $('<li />');

		var newCatDeleteMenuLIOnleThis 		  = $('<li />');
		var newCatDeleteMenuLIArticles 		  = $('<li />');
		var newCatDeleteMenuLIFirstCatLevel   = $('<li />');
		var newCatDeleteMenuLIAllCats 		  = $('<li />');
		var newCatDeleteMenuLIAll 			  = $('<li />');

		var newCatEditLink 	 				  = $('<a />');
		var newCatMoveLink 	 				  = $('<a />');
		var newCatDeleteLink 				  = $('<a />');

		var newCatDeleteMenuLinkOnleThis 	  = $('<a />');
		var newCatDeleteMenuLinkArticles	  = $('<a />');
		var newCatDeleteMenuLinkFirstCatLevel = $('<a />');
		var newCatDeleteMenuLinkAllCats 	  = $('<a />');
		var newCatDeleteMenuLinkAll 		  = $('<a />');

		newCatDeleteMenuLinkOnleThis.addClass(deleteCatClass.replace('.', '')).attr({'href': '#', 'data-what': 'onleThis'}).text('Doar asta');
		newCatDeleteMenuLinkArticles.addClass(deleteCatClass.replace('.', '')).attr({'href': '#', 'data-what': 'articles'}).text('Doar articolele');
		newCatDeleteMenuLinkFirstCatLevel.addClass(deleteCatClass.replace('.', '')).attr({'href': '#', 'data-what': 'firstCatLevel'}).text('Doar primul nivel de categorii');
		newCatDeleteMenuLinkAllCats.addClass(deleteCatClass.replace('.', '')).attr({'href': '#', 'data-what': 'allCats'}).text('Doar categoriile');
		newCatDeleteMenuLinkAll.addClass(deleteCatClass.replace('.', '')).attr({'href': '#', 'data-what': 'all'}).text('Toate');

		newCatDeleteMenuLIOnleThis.append(newCatDeleteMenuLinkOnleThis);
		newCatDeleteMenuLIArticles.append(newCatDeleteMenuLinkArticles);
		newCatDeleteMenuLIFirstCatLevel.append(newCatDeleteMenuLinkFirstCatLevel);
		newCatDeleteMenuLIAllCats.append(newCatDeleteMenuLinkAllCats);
		newCatDeleteMenuLIAll.append(newCatDeleteMenuLinkAll);

		newCatDeleteMenu.addClass(deleteCatMenuClass.replace('.', '')+' hide').append(newCatDeleteMenuLIOnleThis)
													.append(newCatDeleteMenuLIArticles)
													.append(newCatDeleteMenuLIFirstCatLevel)
													.append(newCatDeleteMenuLIAllCats)
													.append(newCatDeleteMenuLIAll);

		newCatRow.addClass(categories.rowClass.replace('.', '')).append(newCatImage).append(newCatName).append(newCatLinks);
		newCatImage.addClass(categories.catImageClass.replace('.', '')).attr('src', ((output.image.length > 0) ? imgThumbURL+output.image : noImage));
		newCatName.addClass(categories.nameClass.replace('.', '')).attr('data-id', output.id).text(catName);
		newCatLinks.addClass(catLinksClass.replace('.', '')).append(' ID: '+output.id+' ').append(newCatEditLink).append(' / ').append(newCatMoveLink).append(' / ').append(newCatDeleteLink).append(newCatDeleteMenu);
		newCatEditLink.addClass(editCatLinkClass.replace('.', '')).attr({'href': '#', 'data-id': output.id}).text('Editeaza');
		newCatMoveLink.addClass(moveCatLinkClass.replace('.', '')).attr({'href': '#', 'data-id': output.id}).text('Muta');
		newCatDeleteLink.addClass(deleteCatLinkClass.replace('.', '')).attr('href', config.domain+'admin/categories/delete/'+output.id).text('Sterge');

		newSubCatUL.addClass(categories.subCatsClass.replace('.', '')+' hide').attr('id', 'subCats-'+output.id);
		newCatLI.addClass(ulSortabeHandleClass.replace('.', '')).attr('data-sort', sort).append(newCatRow).append(newSubCatUL);

		return newCatLI;
	}

	function moveArticle (currentID, parentID) {

	}

	function resortAfterDelete (thisLI) {
		var thisUL = thisLI.parent();
		var thisULChildren = thisUL.children();
		var lastLI = (thisUL.attr('id') == subCats0ID.replace('#', '')) ? thisULChildren.eq(thisULChildren.length -3) : thisULChildren.last();
		var thisSort = thisLI.data('sort');
		var lastLISort = lastLI.data('sort');
		console.log('lastLI', lastLI);
		if (lastLI != thisLI) {
			console.log('no match');
			for (var x = thisSort; x <= lastLI.data('sort'); x++) {
				thisUL.children('li[data-sort="'+x+'"]').attr('data-sort', x-1);
			}
			return x-1;
		}
		return thisSort;
	}

	function addImage (link, src, imageID) {
		imgLink = link;
		imgThumb = src;
		var imageURL = (link.substr(0, 7) == 'http://' || link.substr(0, 8) == 'https://') ? link : imgURL+link;
		var thumbURL = (src.substr(0, 7) == 'http://' || src.substr(0, 8) == 'https://') ? src : imgThumbURL+src;
		var imageID = (typeof imageID == 'undefined') ? '0' : imageID;

		imgViewer.add({
				target: catImage,
				attr: {
					src: imageURL,
					data: {
						id: imageID
					}
				},
				links: {
					changeImage: {
						attr: {
							href: '#',
							'data-id': imageID,
							id: changeImageLinkID,
							class: showImageSearchClass.replace('.', '')
						},
						text: 'Schimba imaginea'
					},
					deleteImage: {
						attr: {
							href: '#',
							'data-id': imageID,
							id: deleteImageLinkID
						},
						text: 'Fara imagine'
					}
				}
			});

		catImage.removeClass(showImageSearchClass.replace('.', '')).attr('src', thumbURL);
		// imageSearch.setButton('#'+changeImageLinkID);
	}
});

