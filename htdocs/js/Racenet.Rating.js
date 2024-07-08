/**
 * Racenet.Rating
 * 
 * Add events for the jQquery rating plugin
 */
Racenet.Rating = function () {

	// Show "disabled" rating for displaying purpose only
    $('.rating:disabled').rating({        split : 4    });
    
    // Show "enabled" rating for user interface
    $('.rating:enabled').rating({
        required: true,
        focus: Racenet.Rating.onFocus,
        blur: Racenet.Rating.onBlur,
        callback: Racenet.Rating.callback
    });
}

/**
 * Show descriptions for each value on focus
 * 
 * @param {string} value
 * @param {string} link
 */
Racenet.Rating.onFocus = function(value, link) {

    Racenet.Rating.container = $(this).parents('table');
    
    var info = $('tr .info', Racenet.Rating.container).eq(1);
    if(info[0]) {
        info[0].data = info[0].data || info.html();
        info.html(link.title || 'value: '+ value);
    }
}

/**
 * Show default state on blur
 * 
 * @param {string} value
 * @param {string} link
 */
Racenet.Rating.onBlur = function(value, link){

    var yourStars = $( "#yourrating div.star", Racenet.Rating.container);
    var info = $('#yourrating .info', Racenet.Rating.container);
    if(yourStars.hasClass("disabled"))
         yourStars.removeClass("star_on");
     
    if(info[0]) {
        info.html( info[0].data || '' );
    }
}

/**
 * Submit ajax and try to save the rating 
 */
Racenet.Rating.callback = function() {

    var value = $('#yourrating div.star_on', Racenet.Rating.container).length;
    var mapId = $('input[name=id]', Racenet.Rating.container).attr("value");
    
    $.ajax({
        url: "/maps/rate/id/"+ mapId + "/value/"+ value + "/",
        method: "GET",
        dataType: "json",
        success: Racenet.Rating.onSuccess
    });
}

/**
 * Show status on success
 * 
 * @param {json} res
 */
Racenet.Rating.onSuccess = function(res) {
    
    htmlDest = $("#communityrating .rating", Racenet.Rating.container);
	
    messageDest = $("#message td", Racenet.Rating.container);
    yourStars = $("#yourrating .rating div.star", Racenet.Rating.container);
    
    if (res.status) {
        htmlDest.html(res.html);
        messageDest.html('<span style="color: green;">'+ res.message + '</span>');
    } else {
        messageDest.html('<span style="color: red;">'+ res.message + '</span>');
        messageDest.get(0).data = messageDest.html();
        yourStars.removeClass("star_on").addClass("disabled").removeClass("star_hover");
    }
	
	$('#message').show();
}
