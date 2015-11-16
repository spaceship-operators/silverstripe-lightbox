/**
 * Lightbox - frontend website
 *
 * .lightbox-container will have the class .lightbox-loading when Content is being retreived
 */

(function($, document, window) {

	// Common selectors
	var $html = $('html'),
		$scroll = $(window).add('html').add('body');

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

	var ua = window.navigator.userAgent.toLowerCase(),
		isAndroid = ua.indexOf('android') > -1,
		screenPos;

	// Android workarounds
	if (isAndroid) {
		$html.addClass('android'); // Add class to allow Android specific styling
	}


	// Bind click events
	$(document)
		.on('click', 'a.lightbox', function (e) {
			// data url property first, otherwise href as a fallback
			var href = $(this).data('url') || $(this).attr('href'),
				content = modal.find('.lightbox-content').html('');
			modal.show().addClass('lightbox-loading');

			$html.addClass('lightbox');
			modal.show().addClass('lightbox-loading');

			if (isAndroid) {
				screenPos = $(window).scrollTop(); // Get current screen position
				$scroll.scrollTop(modal.scrollTop()); // Scroll view to the top of the page
			}

			// get the content linked and inject it to the content of the lightbox.
			$.ajax({
				url: href,
				complete: function () {
					modal.removeClass('lightbox-loading');
				},
				success: function (html) {
					var $html = content.html(html);
					$(document).trigger('lightbox:displayed', [$html]);
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
				$scroll.scrollTop(screenPos);
			}

			e.preventDefault();
		});


}(jQuery, document, window));