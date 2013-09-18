(function($) {
    $.fn.extend({
        img_thumb_lightbox_with_tooltip: function(target,options) {
            options = $.extend( {}, $.MyFunc.defaults, options );
            this.each(function() {
                new $.MyFunc(this,target,options);
            });
            return this;
        }
    });

    $.MyFunc = function( ctl, target, options ) {
		if ( $(ctl).attr("src") ) {
			var imgsrc = $(ctl).attr("src");
			$(ctl).wrap("<div style='pointer:cursor'>");
			var that = $(ctl).parent();
			$(that).click(function() {
				$('.modal-body').empty();
				var title = $(this).parents("a").attr("title");
				$('.modal-title').find(".modal-title-text").text(title);
				var copy = $(this).parents('div').html();
				$(copy).attr("class","").appendTo('.modal-body');
				$(target).tooltip('destroy');
				$(target).find(".click-to-expand").css("height", Math.round($(window).height()*0.7)+ "px");
				$(target).find(".caption").hide();
				$(target).find(".tooltip").hide();
				$(target).modal({show:true});
			})
			$(that).tooltip({ placement: options.placement, title : options.title})
			if (options.tooltip_show == "always") { 
				$(that).tooltip('show')
			}
		}
    };

    // defaults
    $.MyFunc.defaults = {
        placement : "top", // top | bottom | right | left
		tooltip_show : "always", // always | hover
		title : "click to enlarge"
    };

})(jQuery);

