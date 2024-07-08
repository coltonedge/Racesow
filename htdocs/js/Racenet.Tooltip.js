Racenet.Tooltip = {

    gfxPath: '/gfx/levelshots/thumbs/',
	gfxExt: '.jpg',
		
	init: function() {
        
        var trigger = $('a.wmtt'); 
		
		Racenet.Tooltip.target = $('<div style="display: none; position: absolute;"><img id="levelshot" src="" alt="" style="border: 1px solid #302843; margin-bottom: 2px;" /></div>');
		Racenet.Tooltip.target.appendTo('body');
		
		trigger.hover(Racenet.Tooltip.mouseOver, Racenet.Tooltip.mouseOut);
    },
	
	mouseOver: function() {
	
	    $().bind('mousemove', Racenet.Tooltip.mouseMove);
		
		var src = $(this).attr('rel');
		var add = $('div.wmtt_addition[rel='+ $(this).attr('addition') +']');
		if (add.length) {

            add.clone().show().appendTo(Racenet.Tooltip.target);
        }
		
		$('#levelshot', Racenet.Tooltip.target.show()).attr("src", src);

	},
	
	mouseOut: function() {
	
	   $().unbind('mousemove', Racenet.Tooltip.mouseMove);
	   $('#levelshot', Racenet.Tooltip.target.hide()).removeAttr('src');
	   $('div.wmtt_addition', Racenet.Tooltip.target).remove();
	},
	
	mouseMove: function(e) {
            
		Racenet.Tooltip.target.css("top", e.pageY + 20).css("left", e.pageX + 20);
    }
}