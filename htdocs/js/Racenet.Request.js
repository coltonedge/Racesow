Racenet.Request = {
    
	useHistory: true,
	initialHash: null,
	isInitialRequest: true,
	
	init: function() {
	
	    Racenet.Request.target = $('#inner_tube');
	    
		Racenet.Request.initialHash = window.location.pathname;
		
	    var _ajaxStart = function() {
	
	        $('#main_loader').fadeIn();
	    }
	    
	    var _ajaxStop = function() {
	
	        $('#main_loader').fadeOut();
			init_ajax_contents();
	    }
		
	    $().ajaxStart(_ajaxStart);
	    $().ajaxStop(_ajaxStop);   
	},
	
	enableHistory: function() {

        Racenet.Request.useHistory = true;
        $.historyInit(Racenet.Request.callback);
    },
	
	serializeForm: function(form) {

		var params = {};

        $('input', form).each(function() {

            if (!$(this).is('.noserialize')) {

	            var name = $(this).attr('name');
                var value = $(this).val();
				
				if ($(this).is(':checkbox') && !$(this).is(':checked')) {

                    value = null;
                }
				
	            if(name && value) {
	
	                if ($('input[name='+ name +']', form).length > 1) {
	                    
	                    name = name.replace(/\[\]$/, '');
	                    
	                    if (!params[name]) {
	                        
	                        params[name] = new Array();
	                    }
	                    
	                    if ($(this).is(':checked, :selected')) {
	                    
	                        params[name].push(value);
	                    }
	                    
	                } else {
	    
	                    
	                    params[name] = value;
	                }
				}
            }
		});
		
		var url = form.attr('action').replace(/\/$/, '');
		
		for (param in params) {

            if (typeof params[param] == 'object') {

                params[param] = params[param].join(',');
            }
			
			if (params[param]) {
			
                url += '/'+ param +'/'+ params[param];
			}
        }
		
		return url;
    },
	
	callback: function(hash) {
		
		if(!hash || hash == '' || typeof hash == 'undefined') {

            /*
            if (window.location.hash == Racenet.Request.initialHash || window.location.hash == window.location.pathname) {

    			return false;
		    }
			*/
			
			if (Racenet.Request.isInitialRequest) {

                return false;
            }
			
			hash = Racenet.Request.initialHash;
        }
		
		Racenet.Request.isInitialRequest = false;
		
		$.ajax({
            url: hash + '?rnd=' + Math.random(),
            dataType: 'html',
            success: function(html) {

                var _gaq = _gaq || [];
                _gaq.push(['_setAccount', 'UA-2034947-1']);
                _gaq.push(['_setDomainName', '.warsow-race.net']);
                _gaq.push(['_trackPageview', hash]);
                
                if(typeof Racenet.Request.forceTarget != 'undefined') {
                
                    var $target = $(Racenet.Request.forceTarget);
                    if ($target.length) {
                    
                        Racenet.Request.target = $target;
                    }
                }
                
                Racenet.Request.target.html(html);
            },
			
            error: function(e) {

               console.log(e);
            }
        });
    },
	
	/**
	 * Perform a request and load the result into the target container
	 * 
	 * @param {Object} obj
	 * @param {Object} domDest
	 * @param {Object} override
	 */
	load: function(obj, useHistory, target) {
		
		var requestUrl;
		var requestType = 'GET';
		var requestReturnType = 'html';
		var requestData = '';
/*		
		// Try to handle obj as an original event object 
		try {

            switch (obj.type) {

                case 'submit':
				    if (obj.target.method == 'zend') {

                        requestType = 'GET';
                    }
					
				    requestType = obj.target.method;
					requestUrl = obj.target.action || window.location.href;
					
					for (n in obj.target.elements) {

                        if (typeof obj.target.elements[n] != 'object') {

                            continue;
                        }

						var type = obj.target.elements[n].type;
						
						if ((type != 'submit' && type != 'button') || obj.target.elements[n] == obj.originalEvent.explicitOriginalTarget) {
							
							if (obj.target.method == 'zend') {

    							if (obj.target.elements[n].name && obj.target.elements[n].value) {

    								requestUrl += '/' + obj.target.elements[n].name + '/' + obj.target.elements[n].value;
							    }
						    
							} else {

                                requestData += '&'+ obj.target.elements[n].name + '=' + obj.target.elements[n].value;
                            }
						} 
                    }
					
				    break;
					
			    default:
				    throw 'event not implemented for Racenet.Request.load';
				    break;

            }
			
       
		} catch(e) {
*/
	        if (typeof useHistory == 'undefined') {
	
	            useHistory = Racenet.Request.useHistory;
	        }
	           
	        if (typeof target == 'undefined') {
	            
	            target = Racenet.Request.target;
	        }
	        
	        if (typeof obj == 'string') {
	
	           requestUrl = obj;
	           
	        } else if($(obj).is('form')) {
	
	            requestUrl = Racenet.Request.serializeForm($(obj));
	            
	        } else if($(obj).is('a')) {
	
	            requestUrl = $(obj).attr('href');
	            
	        } else if($(obj).is('select')) {
	
	            requestUrl = $(obj).attr('value');
	            
	        } else if($(obj).is('input[type=checkbox]')) {
	
	            requestUrl = $(obj).attr('rel');
	        }
	    
	        requestUrl = requestUrl.replace(/^.*?:\/\/.*?\//, '/');
//        }
		
	
	    if (useHistory) {
	
	        $.historyLoad(requestUrl);
	        
	    } else {
	
			$.ajax({
                url: requestUrl,
				type: requestType,
				data: requestData,
				dataType: requestReturnType,
				success: function(html) {

                    var _gaq = _gaq || [];
                    _gaq.push(['_setAccount', 'UA-2034947-1']);
                    _gaq.push(['_setDomainName', '.warsow-race.net']);
                    _gaq.push(['_trackPageview', requestUrl]);

                    Racenet.Request.target.html(html);
                },
				error: function(e) {

                   console.log(e);
                }
			});
	    }
		
		return false;
	},
	
	/**
	 * Perform a search request using the load method
	 * 
	 * @param {Object} trigger
	 * @param {String} url
	 * @param {String} domDest
	 */
	search: function(trigger, url, domDest) {
	
	    if (typeof domDest == 'undefined') {
	        domDest = '#inner_tube';
	    }
		
	    var $form = $(trigger).parents("form");
	    var $search = $("input.search", $form);
	
	    if ($search.val() == $search.attr('rel')) {
	
	       $search.val('').focus();
	       return false;
	    }
	
	    $search.addClass("loading");
	
	    $("input", $form).attr("disabled", "true");
	
	    Racenet.Request.load(url.replace(/(?:\:|%3A)value/, escape($search.val())), domDest, true);
	    return false;
	}
};