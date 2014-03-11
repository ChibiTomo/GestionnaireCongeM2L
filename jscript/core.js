function YT() {
	this.redirectTo = function(url) {
		window.location.href = url;
	};

	this.getDate = function(format, timestamp) {
		if (typeof(format) == 'number') {
			timestamp = format;
			format = undefined;
		}
		format = format || '%D %d %m %Y - %H:%M:%s';

		var date = new Date();
		if (timestamp) {
			date = new Date(timestamp * 1000);
		}

		format = format.replace('%D', yt.int2Day(date.getDay()));
		format = format.replace('%d', yt.padLeft(date.getDate().toString(), '0', 2));
		format = format.replace('%m', yt.int2Month(date.getMonth()));
		format = format.replace('%Y', date.getFullYear());
		format = format.replace('%H', yt.padLeft(date.getHours().toString(), '0', 2));
		format = format.replace('%M', yt.padLeft(date.getMinutes().toString(), '0', 2));
		format = format.replace('%s', yt.padLeft(date.getSeconds().toString(), '0', 2));
		return format;
	};

	this.int2Day = function(i) {
		var days = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
		return days[i%days.length];
	};

	this.int2Month = function(i) {
		var months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
		return months[i%months.length];
	};

	this.padLeft = function(str, padding, charQty) {
		for (var i = str.length; i < charQty; i++) {
			str = padding + str;
		}
		return str;
	};
}

var yt = new YT();
