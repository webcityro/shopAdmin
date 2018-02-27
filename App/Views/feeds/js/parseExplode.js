function parseExplode() {
	this.parse = function(data, callbeck, limit) {
		if (typeof data == 'object') {
			var dataArr = data.structure.split(data.spliter);
			var json = {};

			json['explode'] = {text: {value: 'Explode("'+data.spliter+'")'}, structurePath: 'explode', children: {}};

			for (var i in dataArr) {
				var index = 'item_'+i;

				json.explode.children[index] = {text: {value: dataArr[i]}, structurePath: 'explode/'+i};

				if (i >= limit) {
					callbeck(json);
					break;
				}
			}
		} else {
			callbeck({'explode': {text: {value: data}, structurePath: ''}});
		}
	}
}