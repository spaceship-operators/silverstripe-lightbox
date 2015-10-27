/**
 * Lightbox - frontend website
 *
 * .lightbox-container will have the class .lightbox-loading when Content is being retreived
 */

(function($, document, window) {
	// Html for the lightbox, may move this out to somewhere else later.
	var modal = $([
		'<div class="lightbox-container" style="display:none;">',
			'<div class="lightbox-dialog">',
				'<button class="lightbox-close-btn" title="Close" type="button">Close</button>',
				'<div class="lightbox-content"></div>',
			'</div>',
			'<div class="lightbox-overlay"></div>',
		'</div>'].join('')
	).appendTo('body');

	$(document)
		.on('click', 'a.lightbox', function (e) {
			var href = $(this).attr('href'),
				content = modal.find('.lightbox-content').html('');
			modal.show().addClass('lightbox-loading');

			// get the content linked and inject it to the content of the lightbox.
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
			// stop the link from following the link
			e.preventDefault();
		})
		.on('click', '.lightbox-close-btn, .lightbox-overlay', function (e) {
			// hide the modal
			modal.hide();
		});


}(jQuery, document, window));