Racenet.GfxCheckboxes = {

    init: function() {

	    $('input.gfxCheckbox').each(function() {
	
	        var id = $(this).attr('id');
	        if (!$(this).is(':checked')) {
	
	            Racenet.GfxCheckboxes.changeStatus(id);
	        }
	        
	        $('label[for='+ id +']').click(function() {
	
	            Racenet.GfxCheckboxes.changeStatus(id);
	        });
	    });
	},

	changeStatus : function(id) {

	    var $img = $('img', $('label[for='+ id +']'));
	    var rel = $img.attr('rel');
	    var src = $img.attr('src');
	    
	    $img.attr('rel', src);
	    $img.attr('src', rel);
	}
	
};