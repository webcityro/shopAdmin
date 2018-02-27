function parseJSON() {
	this.parse = function(data, callbeck, limit) {
		var json = (typeof data == 'object' || typeof data == 'array') ? data : JSON.parse(data);

		callbeck(this.walk(json, false, limit));
	};

	this.walk = function (json, path, limit) {
		var formatedJSON = {};
		var x = 0;

		for (var e in json) {
			var index = 'item_'+x;
			var thisPath = (!path) ? e : path+'/'+e;

			formatedJSON[index] = {text: {value: e}, structurePath: thisPath};

			if ((typeof json[e] == 'object' || typeof json[e] == 'array') && json[e] != null) {
				/*if (e == 'children' || e == 'childrens') {
					formatedJSON[index].children = this.walk(json[e], thisPath);
				} else if (e == 'attribute' || e == 'attributes' || e == 'attr' || e == 'attrs') {
					formatedJSON[index].attr = {value: json[e]};
				} else if (e == 'image' || e == 'images' || e == 'photo' || e == 'photos' || e == 'img' || e == 'imgs') {
					formatedJSON[index].images = json[e];
				} else {
					formatedJSON[index].children = this.walk(json[e], thisPath);
				}*/
				var childrenCount = (typeof json[e] == 'array') ? json[e].length : Object.keys(json[e]).length;
				formatedJSON[index].children = this.walk(json[e], thisPath, ((childrenCount > 50) ? limit : 50));
			} else {
				formatedJSON[index].value = {value: json[e]};
			}

			if (typeof limit !== 'undefined' && x == limit) {
				break;
			}
			x++;
		}
		return formatedJSON;
	};
}