function parseXML() {
	this.json = {};

	this.parse = function(data, callbeck, limit) {
		var xml = (typeof data == 'object') ? data : $($.parseXML(data));
		// console.log('xml', xml);
		this.json = this.walk(xml, limit);

		callbeck(this.json);
	};

	this.walk = function(xml, limit, path) {
		var t = this;
		var json = {};
		var x = 0;
		// console.log('xml', xml);

		xml.children().each(function(i, e) {
			var self = $(e);
 			var index = 'item_'+i;
 			var attr = {};
 			var children = self.children();
 			var noOfChildren = children.length;
 			var thisPath = (!path) ? self.prop('tagName') : path+'/'+self.prop('tagName');

 			json[index] = {text: {value: self.prop('tagName')}, value: {value: (noOfChildren == 0) ? self.text() : ''}, structurePath: thisPath};

 			$(this).each(function() {
				$.each(this.attributes, function(i, e) {
					if(this.specified) {
						attr['attr_'+i] = {};
				    	attr['attr_'+i].name = {value: this.name};
				    	attr['attr_'+i].value = {value: this.value};
					}
				});
			});

			if (!$.isEmptyObject(attr)) {
				json[index].attr = attr;
			}

 			if (noOfChildren > 0) {
 				json[index].children = t.walk(self, ((noOfChildren > 50) ? limit : noOfChildren), thisPath);
 			}

			if (typeof limit != 'undefined' && limit <= i) {
 				return false;
 			}
		});

		return json;
	};
}