var imageViewer = function() {
	this.images = {};
	this.imagesCount = 0;

	this.displayImageDivClass = 'displayImage';
	this.closeImageLinkClass = 'closeImageLink';
	this.imageDivClass = 'imageFrame';
	this.prevImageButtnClass = 'prevImageBtn';
	this.nextImageButtnClass = 'nextImageBtn';
	this.imageClass = 'bigImg';
	this.imageLinksDivClass = 'imageLinks';

	this.displayImageDiv;
	this.closeImageLink;
	this.imageDiv;
	this.prevImageButtn;
	this.nextImageButtn;
	this.image;
	this.imageLinksDiv;
	this.docBody;

	this.init = function() {
		this.docBody = (typeof docBody == 'undefined') ? $(document.body) : docBody;
		this.displayImageDiv = $('<div />');
		this.closeImageLink = $('<a />');
		this.imageDiv = $('<div />');
		this.prevImageButtn = $('<a />');
		this.nextImageButtn = $('<a />');
		// this.image = $('<img />');
		this.imageLinksDiv = $('<div />');

		this.displayImageDiv.addClass(this.displayImageDivClass).append(this.closeImageLink).append(this.imageDiv).append(this.imageLinksDiv).hide();
		this.closeImageLink.addClass(this.closeImageLinkClass).attr('href', '#').text('X');
		// this.image.attr({id: this.imageTagID, src: link});
		this.imageLinksDiv.addClass(this.imageLinksDivClass);
		this.imageDiv.addClass(this.imageDivClass).append(this.prevImageButtn).append(this.nextImageButtn);
		this.prevImageButtn.addClass(this.prevImageButtnClass+' hide').attr('href', '#').html('&laquo;');
		this.nextImageButtn.addClass(this.nextImageButtnClass+' hide').attr('href', '#').html('&raquo;');

		this.docBody.append(this.displayImageDiv);

		this.addEventListeners();
	};

	this.addEventListeners = function() {
		var t = this;

		this.closeImageLink.on('click', function(e) {
			e.preventDefault();
			t.close(e);
		});
		this.prevImageButtn.on('click', function(e) {
			e.preventDefault();
			t.show($(this).attr('data-index'));
		});
		this.nextImageButtn.on('click', function(e) {
			e.preventDefault();
			t.show($(this).attr('data-index'));
		});
	};

	this.add = function(img) {
		var newImg = $('<img />');
		var id;
		var index;
		var t = this;

		newImg.addClass(this.imageClass);

		if (typeof img.attr.data != 'undefined') {
			id = ((typeof img.attr.data.id == 'undefined') ? 'noid_'+this.imagesCount : img.attr.data.id);
			this.imagesCount = (typeof img.attr.data.id == 'undefined') ? this.imagesCount+1 : this.imagesCount;
			img.attr.data.id = (typeof img.attr.data.id == 'undefined') ? id : img.attr.data.id;

			for (var i in img.data) {
				newImg.attr('data-'+i, img.attr.data[i])
			}
			delete img.attr.data;
		} else {
			id = 'noid_'+this.imagesCount;
			this.imagesCount++;
			newImg.attr('data-id', id);
		}

		index = 'image_'+id;

		img.target.on('click', function(e) {
			t.show(index);
		});

		for (var i in img.attr) {
			newImg.attr(i, img.attr[i])
		}
		this.images[index] = {img: newImg, target: img.target};

		if (typeof img.links != 'undefined') {
			this.images[index].links = img.links
		}
	};

	this.show = function(index) {
		if (this.imageDiv.children('.'+this.imageClass).length > 0) {
			this.image.img.remove();
		}
		this.image = this.images[index];

		if (Object.keys(this.images).length > 1) {
			if (Object.keys(this.images)[0] == index) {
				this.prevImageButtn.addClass('hide');
			} else {
				this.prevImageButtn.removeClass('hide').attr('data-index', this.getPrevIndex(index));
			}
			if (Object.keys(this.images)[Object.keys(this.images).length - 1] == index) {
				this.nextImageButtn.addClass('hide');
			} else {
				this.nextImageButtn.removeClass('hide').attr('data-index', this.getNextIndex(index));
			}
		}
		this.prevImageButtn.after(this.image.img);
		if (typeof this.image.links != 'undefined') {
			this.parseLinks(this.image.links);
		} else {
			this.imageLinksDiv.addClass('hide');
		}
		var left = ((this.docBody.width() / 2) - (this.displayImageDiv.width() / 2))+'px';
		var top = (($(window).height() / 2) - (this.displayImageDiv.height() / 2))+'px';
		this.displayImageDiv.css({top: top, left: left}).show();
	};

	this.getPrevIndex = function(index) {
		var last;

		for (var i in this.images) {
			if (i == index) {
				return last;
			}
			last = i;
		}
	};

	this.getNextIndex = function(index) {
		var finded = false;

		for (var i in this.images) {
			if (i == index) {
				finded = true
			} else if (finded) {
				return i;
			}
		}
	};

	this.parseLinks = function(links) {
		var link;
		var a;

		this.imageLinksDiv.children().remove();

		for (var i in links) {
			link = links[i];
			a = $('<a />')

			for (var e in link.attr) {
				a.attr(e, link.attr[e]);
			}
			a.html(link.text);
			this.imageLinksDiv.append(a);
		}
		this.imageLinksDiv.removeClass('hide');
	};

	this.remove = function(id) {
		this.images['image_'+id].target.off('click');
		delete this.images['image_'+id];
		this.close();
	};

	this.close = function(e) {
		this.displayImageDiv.hide();
	}
};