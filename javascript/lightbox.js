/**
 * Lightbox
 */

(function($, document, window) {
	var modal = $(['<div class="lightbox-container" style="display:none;">',
		'<div class="lightbox-overlay"></div>',
		'<div class="lightbox-modal">',
		'<a class="lightbox-close-btn" href="#">Close</a>',
		'<div class="lightbox-content"></div>',
		'</div>',
		'</div>'].join('')
	).appendTo('body');

	$(document)
		.on('click', 'a.lightbox', function (e) {
			var href = $(this).attr('href'),
				content = modal.find('.lightbox-content');
			modal.show().addClass('lightbox-loading');

			$.ajax({
				url: href,
				complete: function () {
					modal.removeClass('lightbox-loading');
				},
				success: function (html) {
					content.html(html);
				},
				error: function (jqXHR, textStatus) {
					content.html(textStatus);
				}
			});
			e.preventDefault();
		})
		.on('click', '.lightbox-close-btn, .lightbox-overlay', function (e) {
			modal.hide();
		});


}(jQuery, document, window));