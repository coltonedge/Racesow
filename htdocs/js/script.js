var ready = true;
var ico_loader = '/gfx/icons/ico_loader2.gif';

function rankedmaps_box_toggle( str ) {

	if( $("#holder_"+str).is(':hidden') && ready ) {
	
		var player_id = $("#bg_"+str).attr('player_id');
		var num_maps = $("#bg_"+str).attr('num_maps');
		
		$("#holder_"+str+":hidden").parents('.player_item').find("td.loader").append( '<img style="width: 13px; height: 13px;" src="'+ico_loader+'" />' ).end();
		$("#holder_"+str+":hidden").find("div.content").hide();		
		
		var loadURL = '/html/stats/player_'+ player_id +'_rankedmaps.htm';
		if( window.location.href.match( /v=0.32/ ) )
			loadURL = '/html/stats_0.32/player_'+ player_id +'_rankedmaps.htm';
		
		$("#holder_"+str+":hidden").find("div.content").loadIfModified( loadURL, '', function() {
		
			ready = false;
			$(this).show();
			$("#holder_"+str+":hidden").show();
			
			ready = true;
			$("#holder_"+str).parents('.player_item').find("td.loader").empty().end();
			$("#more_"+str).show()
			
			
			//$("#bg_"+str+",#bg_"+str+" td").css("background-color", "#41365C;");
			$("img.toggler_"+str).attr("src", $("#arr_down").val() );
		
		});
						
	} else if( ready ) {
	
		$("#more_"+str).fadeOut(400, 0);
		$("img.toggler_"+str).attr("src", $("#arr_right").val());
		ready = false;
		$("#holder_"+str).slideUp(600, function() {
		
			ready = true;
		});
	}
}

function h1_rankedmaps_box_toggle( str ) {

	if( $("#holder_"+str).is(':hidden') && ready ) {
	
		var player_id = $("#bg_"+str).attr('player_id');
		var num_maps = $("#bg_"+str).attr('num_maps');
		
		$("#holder_"+str+":hidden").parents('.player_item').find("td.loader").append( '<img style="width: 13px; height: 13px;" src="'+ico_loader+'" />' ).end();
		$("#holder_"+str+":hidden").find("div.content").hide();
		$("#holder_"+str+":hidden").find("div.content").loadIfModified( '/html/old_stats/player_'+ player_id +'_rankedmaps.htm', '', function() {
		
			ready = false;
			$(this).show();
			$("#holder_"+str+":hidden").slideDown(850, function() {
			
				ready = true;
				$("#holder_"+str).parents('.player_item').find("td.loader").empty().end();
				$("#more_"+str).fadeIn("slow", 1);
			});
			
			$("img.toggler_"+str).attr("src", $("#arr_down").val() );
		
		});
						
	} else if( ready ) {
	
		$("#more_"+str).fadeOut("slow", 0);
		$("img.toggler_"+str).attr("src", $("#arr_right").val());
		ready = false;
		$("#holder_"+str).slideUp(600, function() {
		
			ready = true;
		});
	}
}

function b_toggle( str ) {

	if( $("#holder_"+str).is(':hidden') && ready ) {
	
			ready = false;
			$("#holder_"+str).parents('.item').find("td.loader").append( '<img style="width: 13px; height: 13px;" src="'+ico_loader+'" />' ).end();
			$("img.toggler_"+str).attr("src", $("#arr").attr("arr_down"));
			$("#holder_"+str+":hidden").slideDown(850, function() {
			
				ready = true;
				$("#more_"+str).fadeIn("slow", 1);
				$("#holder_"+str).parents('.item').find("td.loader").empty().end();
			});
						
	} else if( ready ) {
	
		ready = false;
		$("#more_"+str).fadeOut("slow", 0);
		$("img.toggler_"+str).attr("src", $("#arr").attr("arr_right"));
		$("#holder_"+str).slideUp(600, function() {

			ready = true;
		});
	}
}

/******************************************************************************
	TOOLTIP
******************************************************************************/
//document.onmousemove = updateWMTT;
var wmtt = false;

jQuery(document).ready( function() {
	   
		$('#main_head').click( function() {
		
			window.location.href='http://www.warsow-race.net/';
		});
		
		/*
		$('#pw_state').click( function() {

			if( $(this).is(':checked') ) {
				$('#password').removeAttr('disabled');
			} else {
				$('#password').val('').attr('disabled','disabled');
			}
		});
		*/
		
		
	});

///////////////////////////////////////////////////////////////////////////////
function showWMTT(name) {

	wmtt = true;
	$("#wmttdragger img").attr( "src", "/gfx/levelshots/thumbs/"+ name +".jpg" ).show();
	$("#wmttdragger:hidden").show();
}

///////////////////////////////////////////////////////////////////////////////
function hideWMTT() {

	wmtt = false;
	$("#wmttdragger img").attr( "src", "" );
	$("#wmttdragger:visible").hide();
}

/*
$(document).ready( function() {

	$( 'div.menu div[@class!=holder]' ).mouseover( function() {
	
		$(this).addClass( 'menu_hover' )
	
	}).mouseout( function() {
	
		$(this).removeClass( 'menu_hover' );
	}).click( function() {
	
		window.location.href = $(this).find( 'a' ).attr( 'href' );
	});
});
*/

///////////////////////////////////////////////////////////////////////////////
function log_download( map_id, dl_path ) {

	$.get( "/tools/log_download.php", "id="+map_id, function() {

		window.location.href = dl_path;
	});
}

///////////////////////////////////////////////////////////////////////////////
function refresh_players( port ) {

	$('#server_'+ port +' td.palyer_holder').load( 'http://www.warsow-race.net/tools/liveplayers.php?port='+ port );
}