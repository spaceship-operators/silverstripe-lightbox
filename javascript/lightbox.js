/**
 * Lightbox - frontend website
 *
 * .lightbox-container will have the class .lightbox-loading when Content is being retreived
 */

(function($, document, window) {

	// Common selectors
	var $html = $('html');

	// Modal mark-up, may move this to somewhere else laster
	var modal = $([
		'<div class="lightbox-container" style="display:none;">',
			'<div class="lightbox-dialog">',
				'<button class="lightbox-close-btn" title="Close" type="button">Close</button>',
				'<div class="lightbox-content"></div>',
			'</div>',
			'<div class="lightbox-overlay"></div>',
		'</div>'].join('')
	).appendTo('body');


	/**
	 * Device detection
	 * ================
	 *
	 * Mainly to target devices on Android OS, as it does not support `position: fixed;` very well.
	 *
 	 */
	var ua = navigator.userAgent.toLowerCase(),
		isAndroid = ua.indexOf('android'),
		screenPos;

	// Android workarounds
	if (isAndroid) {
		$html.addClass('android'); // Add class to allow Android specific styling
	}


	// Bind click events
	$(document)
		.on('click', 'a.lightbox', function (e) {
			var href = $(this).attr('href'),
				content = modal.find('.lightbox-content').html('');
			modal.show().addClass('lightbox-loading');

			$html.addClass('lightbox');
			modal.show().addClass('lightbox-loading');

			if (isAndroid) {
				screenPos = $(window).scrollTop(); // Get current screen position
				window.location = '#page-top'; // Anchor to top of the `position: absolute;` Android lightbox
			}

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
			$html.removeClass('lightbox');

			if (isAndroid) {
				// Scroll back to screen position before lightbox is opened
				$('html, body').scrollTop(screenPos);
			}

			e.preventDefault();
		});


}(jQuery, document, window));