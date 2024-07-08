Racenet.EventHandler.init();

Racenet.EventHandler.addTrigger(
    Racenet.EventHandler.Events.ready,
	new Racenet.EventHandler.Always({
	    exec: Racenet.Request.init
	})
);

Racenet.EventHandler.addTrigger(
    Racenet.EventHandler.Events.ready,
	new Racenet.EventHandler.Regexp({
        test: /^\/$|\/(news)|^\/maps|^\/player|^\/admin\/maps|^\/ranking/,
	    exec: Racenet.Request.enableHistory
	})
);

var mapTooltip = new Racenet.EventHandler.Regexp({
	test: /^\/map|^\/player/,
	exec: Racenet.Tooltip.init
});
Racenet.EventHandler.addTrigger(Racenet.EventHandler.Events.ready, mapTooltip);
Racenet.EventHandler.addTrigger(Racenet.EventHandler.Events.ajaxSuccess, mapTooltip);

var rating = new Racenet.EventHandler.Regexp({
	test: /^\/map/,
	exec: Racenet.Rating
});
Racenet.EventHandler.addTrigger(Racenet.EventHandler.Events.ready, rating);
Racenet.EventHandler.addTrigger(Racenet.EventHandler.Events.ajaxSuccess, rating);

var checkboxes = new Racenet.EventHandler.Regexp({
    test: /^\/maps|^\/player\/maps/,
    exec: Racenet.GfxCheckboxes.init
});
Racenet.EventHandler.addTrigger(Racenet.EventHandler.Events.ready, checkboxes);
Racenet.EventHandler.addTrigger(Racenet.EventHandler.Events.ajaxSuccess, checkboxes);
