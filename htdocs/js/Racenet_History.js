/**
 * Racenet History
 */
Racenet.History = function() {

	this.initialHistoryHash = window.location.href.replace(/#.*/,'');
	$.history.init(this.historyCallback, 200);
}

Racenet.History.prototype.historyCallback = function() {

}