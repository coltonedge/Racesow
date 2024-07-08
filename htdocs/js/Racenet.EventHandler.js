/**
 * Racenet.EventHandler
 * 
 */
Racenet.EventHandler = {

	Events: [],
	
    Triggers: [],
	
	init: function() {
        Racenet.EventHandler.addEvent('ready');
        Racenet.EventHandler.addEvent('ajaxSuccess');

		for (type in Racenet.EventHandler.Events) {

    		$(document).bind(type, Racenet.EventHandler.Events[type], Racenet.EventHandler.trigger);
        }
		
		return Racenet.EventHandler;
    },
    
	kill: function() {

        for (type in Racenet.EventHandler.Events) {

            $(document).unbind(type, Racenet.EventHandler.Events[type], Racenet.EventHandler.trigger);
        }
		
		return Racenet.EventHandler;
    },
	
	addEvent: function(type) {

        Racenet.EventHandler.Events[type] = type;
    },
	
    /**
     * Add a trigger to the onAjax routine
     * 
     * @param {Racenet_UrlTrigger_Abstract} trigger
     * @return Racenet_UrlTrigger
     */
    addTrigger: function (type, trigger) {

        if (Racenet.EventHandler.Events[type] != type) {

            throw new Error('Unknown type given to Racenet.EventHandler.addTrigger');
        }
		
		if (!trigger instanceof Racenet.EventHandler.Abstract) {

            throw new Error('only objects based on Racenet.EventHandler.Abstract can be addded to Racenet.EventHandler');
        }
        
		if (typeof exclusive == 'undefined') {

            exclusive = false;
        }
		
        var triggerTypeId;
		if (typeof Racenet.EventHandler.Triggers[type] == 'undefined') {

            triggerTypeId = 0;
			Racenet.EventHandler.Triggers[type] = [];
        
		} else {

            triggerTypeId = Racenet.EventHandler.Triggers[type].length + 1;
        }
		
	    Racenet.EventHandler.Triggers[type][triggerTypeId] = trigger;
		
        return Racenet.EventHandler;
    },
    
    /**
     * Execute all added onLoad triggers
     * 
     * @return Racenet_UrlTrigger
     */
    trigger: function (e) {
		
		if (typeof e == 'object') {

            e = e.data;
        }
		
		if (Racenet.EventHandler.Events[e] != e) {

            throw new Error('Unknown type given to Racenet.EventHandler.trigger');
        }
		
		for (current in Racenet.EventHandler.Triggers[e]) {

           Racenet.EventHandler.Triggers[e][current].exec();
        }
		
        return Racenet.EventHandler;
    }
}

/**
 * Racenet_UrlTrigger_Abstract
 * 
 * Abstract base class for URL triggers
 * 
 * @param {function} fn
 * @param {mixed} condition
 * @param {string} part
 */
Racenet.EventHandler.Abstract = function (options) {
    
    this.options = options;
	
	this.noFunction = function() {
        throw new Error('unimplemented Racenet.EventHandler called');
    }
	
    if (typeof this.options != 'object') {
        this.options = {};
    }
	
	if(typeof this.options.fn != 'function') {
        this.options.fn = this.noFunction;
    }
	
    if (typeof this.options.options != 'object') {
        this.options.options = {};
    }
	
    if (typeof this.options.source == 'undefined') {
        this.options.source = window.location.pathname;
    }
}

/**
 * Racenet.EventHandler.Always
 * 
 * @extends Racenet.EventHandler.Abstract
 * @param {Object} options
 */
Racenet.EventHandler.Always = function (options) {

    Racenet.EventHandler.Abstract.call(this, options);
    this.exec = function() {

        this.options.exec(this.options.options);
    }
}

/**
 * Racenet.EventHandler.Equals
 * 
 * URL trigger class using a simple comparison
 * 
 * @extends Racenet.EventHandler.Abstract
 * @param {Object} options
 */
Racenet.EventHandler.Equals = function (options) {

    Racenet.EventHandler.Abstract.call(this, options);
    
    this.exec = function() {

        this.options.source == this.options.source && this.options.exec(this.options.options);
    }
}

/**
 * Racenet.EventHandler.Regexp
 * 
 * URL trigger class using regual expressions
 * 
 * @extends Racenet.EventHandler.Abstract
 * @param {Object} options
 */
Racenet.EventHandler.Regexp = function (options) {

    Racenet.EventHandler.Abstract.call(this, options);
    
    this.exec = function() {

        this.options.source.match(this.options.test) && this.options.exec(this.options.options);
    }
}

/**
 * Racenet.EventHandler.Function
 * 
 * URL trigger class using regual expressions
 * 
 * @extends Racenet.EventHandler.Abstract
 * @param {Object} options
 */
Racenet.EventHandler.Function = function (options) {

    Racenet.EventHandler.Abstract.call(this, options);
    
    this.exec = function() {

        this.options.test(this.options.source) && this.options.exec(this.options.options);
    }
}

