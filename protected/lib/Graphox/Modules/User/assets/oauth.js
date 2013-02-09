
(function($)
{
	$.fn.OAuthWidget = function(options)
	{
		var authWindow = null;
		
		options = $.extend({
			title : "Authenticate",
			width : 450,
			height: 380
		},options);
		
		
		$('.auth-link', this).click(function(event)
		{
			event.preventDefault();
			
			if(authWindow != null)
				authWindow.close();
			
			var $this = $(this);
			
			var left	= $(window).width()/2	- options.width/2;
			var top		= $(window).height()/2	- options.height/2;
			
			authWindow = window.open(
				$this.attr('href'),
				options.title,
				"width=" + options.width
					+ ",height="+ options.height
					+ ",left="	+ left
					+ ",top="	+ top
					+ ",resizable=yes"
					+ ",scrollbars=no"
					+ ",toolbar=no,"
					+ "menubar=no,"
					+ "location=no,"
					+ "directories=no"
					+ ",status=yes"
			);
			
			authWindow.focus();
		});
	}
	
	$(function()
	{
		$(".oauth.services").OAuthWidget();
	});
})(jQuery);