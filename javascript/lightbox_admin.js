/**
 * Lightbox - admin javascript
 */
+(function($) {
	$.entwine('ss', function($) {
		$('form.htmleditorfield-linkform').entwine({
			getLinkAttributes: function () {
				var results = this._super();

				if (this.find(':input[name=LinkType]:checked').val() == 'lightbox') {
					results.href = '[lightbox,id=' + this.find(':input[name=lightbox]').val() + ']';
				}

				console.log(results);
				return results;
			}
		});
	});
}(jQuery));