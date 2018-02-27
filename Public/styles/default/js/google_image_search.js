var googleImageSearch = function (param) {
	this.searchForm;
	this.searchBar;
	this.closeImageFormBtn;
	this.imagesSearchQuery;
	this.imagesSearchBtn;
	this.imagesSearchResualts;
	this.aplyImagesBtn;
	this.imgDivClass = 'image';
	this.showSearchBtn = param.button;
	this.multiselect = (typeof param.multiselect != 'undefined') ? param.multiselect : true;
	this.numOfItems  = (typeof param.items != 'undefined') ? param.items : 30; // integer value range between 1 to 10 including
	this.num 		 = (typeof param.num != 'undefined') ? param.num : 10; // integer value range between 1 to 10 including
	this.fileType	 = (typeof param.fileType != 'undefined') ? param.fileType : 'jpg'; // you can leave these if extension does not matters you
	this.imgSize	 = (typeof param.size != 'undefined') ? param.size : 'large'; // for image size
	this.start 		 = (typeof param.start != 'undefined') ? param.start : 1; // integer value range between 1 to 101, it is like the offset
	this.key 		 = 'AIzaSyC6QJujKiPSnz0nyf7g-HQMJqIE35QNbi0'; // API_KEY got from https://console.developers.google.com/
	this.cx 		 = '015812523998999303647:xybs_yiynna'; // cx value is the custom search engine value got from https://cse.google.com/cse(if not created then create it)
	this.imagesSearchFormClass = 'imagesSearchForm';
	this.closeBtnClass = 'closeBtn';
	this.searchBarClass = 'searchBar';
	this.searchQueryClass = 'searchQuery';
	this.searchBtnClass = 'searchBtn';
	this.searchResualtsClass = 'searchResualts';
	this.aplyImagesBtnClass = 'aplyImagesBtn';
	this.callbeck;
	this.initialValue = '';

	this.init = function() {
		var t = this;
		this.searchForm  		  = $('<div />');
		this.searchBar  		  = $('<div />');
		this.closeImageFormBtn    = $('<a />');
 		this.imagesSearchQuery    = $('<input />');
		this.imagesSearchBtn      = $('<button />');
		this.imagesSearchResualts = $('<div />');
		this.aplyImagesBtn        = $('<button />');

		this.searchForm.addClass(this.imagesSearchFormClass+' hide').append(this.closeImageFormBtn).append(this.searchBar).append(this.imagesSearchResualts).append(this.aplyImagesBtn);
		this.closeImageFormBtn.addClass(this.closeBtnClass).attr('href', '#').text('X');
		this.searchBar.addClass(this.searchBarClass).append(this.imagesSearchQuery).append(this.imagesSearchBtn);
		this.imagesSearchQuery.addClass(this.searchQueryClass).attr('type', 'text');
		this.imagesSearchBtn.addClass(this.searchBtnClass).text('Cauta');
		this.imagesSearchResualts.addClass(this.searchResualtsClass);
		this.aplyImagesBtn.addClass('button buttonSmall '+this.aplyImagesBtnClass).text('Aplica imaginile');

		docBody.append(this.searchForm);

		this.setButton(this.showSearchBtn);
		docBody.on('click', '.'+this.closeBtnClass, function(e) {
			e.preventDefault();
			t.closeSearch();
		});
		docBody.on('click', '.'+this.searchBtnClass, function(e) {
			e.preventDefault();
			var limit = Math.ceil(t.numOfItems / t.num);

			for (var x = t.start; x <= limit; x++) {
				t.start = (x * t.num < t.numOfItems) ? x * t.num : t.numOfItems;
				t.search();
			}
		});
		docBody.on('click', '.'+this.aplyImagesBtnClass, function(e) {
			e.preventDefault();
			t.callCallbeck();
		});
		docBody.on('click', '.'+this.searchResualtsClass+' .'+this.imgDivClass, function(e) {
			e.preventDefault();
			t.selectImage(e);
		});

	};
	this.setButton = function(e) {
		var t = this;
		docBody.on('click', e, function(e) {
			e.preventDefault();
			t.showSearch(e);
		});
	};
	this.setCallbeck = function(cb) {
		this.callbeck = cb;
	};
	this.setInitialValue = function(value) {
		this.initialValue = value;
	};
	this.showSearch = function() {
		this.searchForm.removeClass('hide');
		this.imagesSearchQuery.val(this.initialValue);
	};
	this.closeSearch = function(e) {
		this.searchForm.addClass('hide');
	};
	this.search = function() {
		var params = {
			q: this.imagesSearchQuery.val(), // search text
			num: this.num,
			start: this.start,
			imgSize: this.imgSize,
			searchType: 'image', // compulsory
			fileType: this.fileType,
			key: this.key,
			cx: this.cx
		};
		var t = this;

		var strURL = Object.keys(params).map(function(key){
			return encodeURIComponent(key) + '=' + encodeURIComponent(params[key]);
		}).join('&');

		$.get('https://www.googleapis.com/customsearch/v1?'+strURL, function(data) {
			// console.log('search data', data);
			for (var i in data.items) {
				var item = data.items[i];
				var imgDiv = $('<div />');
				var img = $('<img />')

				imgDiv.addClass(t.imgDivClass).append(img);
				img.attr({src: item.image.thumbnailLink, 'data-link': item.link});
				t.imagesSearchResualts.append(imgDiv);
			}
		}, 'json');
	};
	this.callCallbeck = function() {
		var t = this;

		this.imagesSearchResualts.children().each(function(i, e) {
			var imageDiv = $(e);

			if (imageDiv.hasClass('selected')) {
				var image = imageDiv.children('img');
				t.callbeck(image.attr('data-link'), image.attr('src'));
			}
		});
		this.closeSearch();
	};
	this.selectImage = function(e) {
		var self = $(e.target);
		self = (self.prop('tagName').toLowerCase() == 'img') ? self.parent() : self;
		if (!this.multiselect && !self.hasClass('selected')) {
			$('.'+this.searchResualtsClass+' .'+this.imgDivClass+'.selected').removeClass('selected');
		}
		self.toggleClass('selected');
	};
};