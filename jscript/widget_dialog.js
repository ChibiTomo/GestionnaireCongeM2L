(function(yt, undefined) {
	yt.Dialog = function(selector, options) {
		var self = this;

		var overlay = $('#yt_overlay');
		if (overlay.length == 0) {
			overlay = $('<div/>')
				.attr('id', 'yt_overlay')
				.appendTo('body')
				.hide();
		}
		var box = new yt.Box(selector, options);
		var dialog = $('<div/>')
			.addClass('yt_dialog')
			.append($(selector))
			.appendTo('body')
			.hide();

		var oldBodyOverflow = $('body').css('overflow');
		this.open = function() {
			oldBodyOverflow = $('body').css('overflow');
			$('body').css('overflow', 'hidden');
			overlay.show();
			dialog.show();
		};

		this.close = function() {
			$('body').css('overflow', oldBodyOverflow);
			overlay.hide();
			dialog.hide();
		};

		this.getElement = function() {
			return dialog;
		};
		
		this.setTitle = function(title) {
			box.setTitle(title);
		};
	};
})(yt)