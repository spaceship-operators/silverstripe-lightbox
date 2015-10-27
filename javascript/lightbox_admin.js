/**
 * Lightbox - ModelAdmin javascript
 */
+(function($) {
	$.entwine('ss', function($) {
		$('textarea.htmleditor').entwine({
			openLinkDialog: function () {
				var self = this,
					dialogs = $('#cms-editor-dialogs'),
					data = 'urlLinkform',
					url = dialogs.data(data),
					newUrl = '',
					enabled = $('.lightbox-disable').length == 0,
					dialog = $('.htmleditorfield-linkdialog');

				if (enabled) {
					newUrl = addQueryParameter(url, 'lightbox', '1');
				} else {
					newUrl = removeQueryParameter(url, 'lightbox');
				}
				dialogs.data(data, newUrl);

				// need to refresh the form since it's a new url
				if (dialog.length && url != newUrl) {
					dialog.addClass('loading');
					dialog.empty().open();
					$.ajax({
						url: newUrl,
						complete: function() {
							dialog.removeClass('loading');
						},
						success: function(html) {
							dialog.html(html);
							dialog.getForm().setElement(self);
							dialog.trigger('ssdialogopen');
						}
					});
				} else {
					this._super();
				}
			}
		});
		$('form.htmleditorfield-linkform').entwine({
			getLinkAttributes: function () {
				var results = this._super(),
					linkType = this.find(':input[name=LinkType]:checked').val();

				if (linkType == 'lightbox') {
					var value = this.find(':input[name=lightbox]').val() || '';
					results.href = '[lightbox,id=' + value + ']';
					results.class = 'lightbox';
				}

				return results;
			},
			getCurrentLink: function () {
				var selectedEl = this.getSelection(),
					href = "", target = "", title = "", action = "insert", style_class = "";

				if(selectedEl.is('a')) {
					href = selectedEl.attr('href');
					target = selectedEl.attr('target');
					title = selectedEl.attr('title');
					href = this.getEditor().cleanLink(href, selectedEl);
				}

				if(href.match(/^\[lightbox(?:\s*|%20|,)?id=([0-9]+)\]?(#.*)?$/i)) {
					return {
						LinkType: 'lightbox',
						lightbox: RegExp.$1,
						Anchor: RegExp.$2 ? RegExp.$2.substr(1) : '',
						Description: title,
						TargetBlank: target ? true : false
					};
				} else {
					return this._super();
				}
			},
			redraw: function () {
				this._super();

				var linkType = this.find(':input[name=LinkType]:checked').val();

				if(linkType === 'lightbox') this.find('.field#TargetBlank').hide();
			}
		});
	});
	// helper functions specific to Lightbox use
	function addQueryParameter(uri, key, value) {
		var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i"),
			separator = uri.indexOf('?') !== -1 ? "&" : "?";

		if (uri.match(re)) {
			return uri.replace(re, '$1' + key + "=" + value + '$2');
		}
		else {
			return uri + separator + key + "=" + value;
		}
	}
	function removeQueryParameter(uri, key) {
		var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");

		uri = uri.replace(re, '$1');

		if (uri.slice(-1) == '?') {
			uri = uri.substring(0, uri.length -1);
		}
		return uri;
	}
}(jQuery));