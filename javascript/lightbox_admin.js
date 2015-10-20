/**
 * Lightbox - ModelAdmin javascript
 */
+(function($) {
	$.entwine('ss', function($) {
		$('form.htmleditorfield-linkform').entwine({
			getLinkAttributes: function () {
				var results = this._super();

				if (this.find(':input[name=LinkType]:checked').val() == 'lightbox') {
					var value = this.find(':input[name=lightbox]').val() || '';
					results.href = '[lightbox,id=' + value + ']';
				}

				return results;
			}
		});
	});
}(jQuery));