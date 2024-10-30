var indicativeLinkTracker = (function ($) {
	
	function trackLinks()
	{
		$('a').click(function (event) {
			
			event.preventDefault();
			var link = $(this).attr('href');
			var linkText = $(this).html().length > 0 ? $(this).html().substr(0, 20) : '';
			var redirect = !$(this).hasClass('indicative-no-redirect');
			Indicative.buildEvent("Link Click",  {
				link_text: linkText,
				link_href: link,
				link_id: $(this).attr('id')
			}, function () {
	            if (link != undefined && redirect) {
	                window.location = link;
	            }
	        });
			
		});
	}
	
	return {
		trackLinks:trackLinks
	};
	
})(jQuery);

jQuery(document).ready(function () {
	if (typeof Indicative != "undefined"){
		indicativeLinkTracker.trackLinks();
	}
});