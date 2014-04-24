(function(yt, undefined) {
	yt.Tab = function(selector, options) {
		var self = this;

		var element = $(selector).addClass('yt_tab');

		var bodies = element.children();
		element.html('');

		var sidebar = $('<div/>').appendTo(element)
			.addClass('sidebar');

		var body = $('<div/>').appendTo(element)
			.addClass('body');

		var options = options || {};

		var top = 0;
		for (var i = 0; i < bodies.length; i++) {
			var content = $(bodies[i])
				.addClass('content')
				.attr('data-target', i);
			var button = $('<div/>').addClass('button')
				.attr('data-target', i)
				.attr('data-scroll', top)
				.html(content.attr('title'));
			content.removeAttr('title');
			sidebar.append(button);
			body.append(content);

			top += body.height();
		}
		
		function redraw() {
			sidebar.find('.button').each(function() {
				var id = $(this).attr('data-target');
				$(this).attr('data-scroll', id * body.height())
			});
			sidebar.find('.selected').removeClass('selected').click();
		}

		$(window).resize(function() {
			redraw();
		});

		sidebar.find('.button').click(tab_click);

		function tab_click() {
			if ($(this).hasClass('selected')) {
				return;
			}

			body.find('.content').css('overflow', 'hidden');
			var target = body.find('[data-target=' + $(this).attr('data-target') + ']');

			sidebar.find('.button').removeClass('selected');
			$(this).addClass('selected');

			var dest = $(this).attr('data-scroll');
			target.scrollTop(0);
			body.animate({
				scrollTop: dest
			}, 300);

			setTimeout(function() {
				target.css('overflow', 'auto');
			}, 300);
		}
		sidebar.find('.button').eq(0).addClass('selected');
		$(document).ready(function () {
			redraw();
		});
	};
})(yt)