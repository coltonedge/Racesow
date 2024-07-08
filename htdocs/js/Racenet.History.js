/**
 * Racenet History
 */
Racenet.History = function() {

    var _initialHistoryHash = window.location.href.replace(/#.*$/,'');

	var _historyCallback = function() {

	    // if we come back to the latest page which was not loaded with ajax
	    if ((typeof hash == 'undefined' || !hash)) {
	
	        hash = _initialHistoryHash;
	        isInitialRequest = false;
			
	    } else {    
		
		    // default procedure   
	        if (hash.match(/domDest\/([^\/]+)/)) {

	            ajaxDomDest = '#' + RegExp.$1;
				
	        } else {

	            ajaxDomDest = '#inner_tube';
	        }
	    }
	
	    // we had a page reload without a history hash. nothing to do
	    if (!hash || !$(ajaxDomDest).length) {

	        return;
	    }
	
	    // FIXME: IE7 quickhack for pagination/history
	    if (window.location.href.match(/.*#$/)) {

	        window.location.href = 'http://'+ window.location.host + window.location.pathname;
	    }

        $.ajax({
            url: hash,
            type: 'GET',
			dataType: 'html',
            success: function(data) {

                $(ajaxDomDest).html(data);
            }
        });
	
	    return false;
	}
	
	$.history.init(_historyCallback, 200);
}