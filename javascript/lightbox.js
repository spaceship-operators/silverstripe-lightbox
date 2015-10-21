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
			modal.show();
		});


}(jQuery, document, window));