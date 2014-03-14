function YT() {
	this.datePattern = /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/;
	this.hourPattern = /^([01]\d|2[0-3]):([0-5]\d)(:[0-5]\d)?$/;

	this.redirectTo = function(url) {
		window.location.href = url;
	};

	this.getTimestamp = function(str, format) {
		format = format || '%Y-%d-%m %H:%M:%s';
		format = format.match(/%\w/g);

		var d = str.match(/\d+/g);

		var year = 1970;
		var month = 0;
		var day = 1;
		var hour = 0;
		var minute = 0;
		var second = 0;
		for (var i = 0; i < format.length; i++) {
			if (i >= d.length) {
				break;
			}
			var value = d[i];
			switch (format[i]) {
				case '%Y':
					year = value;
					break;
				case '%d':
					day = value;
					break;
				case '%m':
					month = value - 1;
					break;
				case '%H':
					hour = value;
					break;
				case '%M':
					minute = value;
					break;
				case '%s':
					second = value;
					break;
			}
		}

		return new Date(year, month, day, hour, minute, second) / 1000;
	};

	this.getDate = function(timestamp, format) {
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
