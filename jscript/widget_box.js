(function(yt, undefined) {
	yt.Box = function(selector, options) {
		var self = this;

		var element = $(selector).addClass('yt_box');

		var content = element.html();
		element.html('');

		var options = options || {};

		var title = $('<div/>')
			.appendTo(element)
			.addClass('title')
			.html(element.attr('title') || options.title);
		element.attr('title', null);

		var body = $('<div/>')
			.appendTo(element)
			.addClass('body')
			.append(content);
		var buttons = $('<div/>').addClass('buttons');

		var buttonNbr = Object.keys(options.buttons).length;
		if (buttonNbr > 0) {
			body.addClass('has_buttons').append(buttons);
		}

		for (var buttonName in options.buttons) {
			var button_data = options.buttons[buttonName];

			var button_container = $('<div/>')
				.appendTo(buttons)
				.addClass('button_container');

			var button = $('<div/>');
			switch (button_data.type) {
				case 'link':
					button = $('<a/>').attr('href', button_data.action);
					break;

				case 'button':
				default:
					button.addClass('button')
						.click(button_data.action);
					break;
			}

			button.addClass(button_data.class)
				.html(buttonName)
				.appendTo(button_container);
		}

	};
})(yt)