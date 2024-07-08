var wmtt = false;
var viewer_url = '/cgi-bin/chat_client.cgi';
var rsay_url = '/cgi-bin/chat_rsay.cgi';
var raction_url = '/cgi-bin/chat_action.cgi';
var chat_pings = new Array();

var useHistory = true;
var isInitialRequest = true;
var initialHistoryHash;
var lastDomDest = null;

var mapTypes = new Object();
mapTypes.freestyle = false;
mapTypes.race = true;

$(document).ready( racenet_init );  

///////////////////////////////////////////////////////////////////////////////
function racenet_init() {

    enable_navi();
    enable_chat();
    enable_faqanswers();
    init_ajax_contents();

    $('#logo').click( function() {
        window.location.href="/";
    });
}

///////////////////////////////////////////////////////////////////////////////
function init_ajax_contents()
{
	enable_mapTypes();
    enable_mapAdmin();
	enable_paginator();
    enable_playerMapRaces();
	enable_shortinfo();
}

///////////////////////////////////////////////////////////////////////////////
function enable_paginator()
{
  if( !useHistory )
        return false;
    
    $("a.history").click(function(){
        
        var url = $(this).attr('href').replace(/^.*#/, '');
        
        // IE7 remove the hostname from url (only local requests!)
        url = url.replace(/http:\/\/.*?\//, '\/'); // IE
		
        $.history.load(url);
        return false;
    });
    
    $("input.history[type=button]").click(function(){
        
        var url = $(this).attr('rel').replace(/^.*#/, '');
        
        // IE7 remove the hostname from url (only local requests!)
        url = url.replace(/http:\/\/.*?\//, '\/'); // IE
        
        $.history.load(url);
        return false;
    });
}

///////////////////////////////////////////////////////////////////////////////
function enable_shortinfo() {

    $("a.shortinfo_toggle").click( function() {
    
        var $box = $('#shortinfo_'+ $(this).attr('rel'));
        
        if( $box.is(':hidden') ) {
            var $link = $(this);
            $link.html( $('<img src="/gfx/icons/loadani.gif" bordeR="0" />') );
            $.ajax({
                url: '/ranking/shortinfo/player/'+ $(this).attr('rel'),
                success: function( content ) {
                    $('div.inner', $box).html( content );
                    $box.slideDown();
                    $link.text('-');
                }
            });
        } else {
            $box.slideUp();
            // TODO: labels
            $(this).text('+');
        }
    });
}

///////////////////////////////////////////////////////////////////////////////
function enable_playerMapRaces() {
    $("a.playermapraces_toggle").each(function(){
	$(this).unbind();
    });
    $("a.playermapraces_toggle").click( function(event) {
        if( !$(this).attr('rel').match(/(\d+),(\d+)/) )
            return;

        var player_id = RegExp.$1;
        var map_id = RegExp.$2;
        var $box = $('#playermapraces_'+ player_id);

        if( $box.is(':hidden') ) {
            var $link = $(this);
            $link.html( $('<img src="/gfx/icons/loadani.gif" bordeR="0" />') );
            
            $.ajax({
                url: '/player/mapraces/id/'+ player_id +'/map/'+ map_id + '/',
                success: function( content ) {
					$box.toggle();
                    $('div.inner', $box).html( content );
					$link.text('-');
                }
            });
		} else {
			$box.toggle();
			$(this).text('+');
		}
    });
}

///////////////////////////////////////////////////////////////////////////////
function racenet_request( obj, domDest, override ) {

	if( typeof override == 'undefined' )
       override = false;
       
    if( typeof domDest == 'undefined' )
        domDest = '#inner_tube';
    
    if( useHistory && !override )
        return false;
    
    var url;
    
    if( typeof obj == 'string' )
    {
       url = obj;
    }
    else if( $(obj).is('a') )
    {
        url = $(obj).attr('href');
    }
    else if( $(obj).is('select') )
    {
        // uhm? shoulnd't this search for the selected item's value?
		url = $(obj).attr('value');
    }
    else if( $(obj).is('input[type=checkbox]') )
    {
		url = $(obj).attr('rel');
    }

    if( useHistory && override )
    {
		$.history.load(url);
    }
    else
    {
		$(domDest).load(url, init_ajax_contents);
    }
	
    return false;
}


///////////////////////////////////////////////////////////////////////////////
function url_disassemble(url) {

    var urlParts = url.split("/");
    
    var urlObj = new Object();  
    
    for (var n = 1; n < urlParts.length; n += 2) {

        if (n == 1) {
            
            urlObj.controller = urlParts[n];
            urlObj.action = urlParts[n+1];
        
        } else {
        
            urlObj[urlParts[n]] = urlParts[n+1];
        }
    }
	
	return urlObj;
}

///////////////////////////////////////////////////////////////////////////////
function url_assemble(urlObj) {
	
	var url = "/"+ urlObj.controller +"/"+ urlObj.action;
	urlObj.controller = null;
	urlObj.action = null;
	
	for (prop in urlObj) {

        if (!urlObj[prop])
		  continue;
		  
		url += "/"+ prop + "/" + urlObj[prop];
    }
	
	return url;
}

///////////////////////////////////////////////////////////////////////////////
function maptype_filter_url(obj, domDest) {

    var url;
	
	if (typeof obj == "string") {

        url = obj;
		
    } else if ($(obj).is("a")) {

        url = $(obj).attr("href");        
    }
	
	var urlObj = url_disassemble(url);
	var show_race = $("#type_race").is(":checked");
	var show_fs = $("#type_fs").is(":checked");
	
	if (show_race && show_fs) {
    
	   urlObj.mapType = 'all';
    
	} else if(show_race) {

        urlObj.mapType = 'race';
    
	} else if(show_fs) {

        urlObj.mapType = 'fs';
    }
	
	var url = url_assemble(urlObj);
	
	return url;
}

///////////////////////////////////////////////////////////////////////////////
function search_request(trigger, url, domDest) {

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

    console.log($search.val());
    console.log(escape($search.val()));

    racenet_request(url.replace(/(?:\:|%3A)value/, escape($search.val())), domDest, true);
    return false;
}

///////////////////////////////////////////////////////////////////////////////
function enable_navi() {

    $("#button_menu img").hover( function() {
        $(this).attr("src", $(this).attr("hover") );
    }, function() {
        if( $(this).attr('active') == 1 )
            return;
        $(this).attr("src", $(this).attr("normal") );
    });
}

///////////////////////////////////////////////////////////////////////////////
function enable_chat() {

    $('a.chat_enable').click( function() {
    
        var port = $(this).attr('rel');
        $(this).parents('tr').remove();     
        $('#chat_hidden'+port).show();
        
        // chat_pings[port] = window.setInterval("ping_chat("+ port +")", 1000);
        /* clear timeout when closing the chat manually
        window.clearInterval(chat_pings[port]);
        */
        
        $('#text'+port).keypress( function( e ) {
            if( e.which == 13) {
                send_rsay( port );
            }
        });

        $('#send'+port).click( function() {
            send_rsay( port );
        });
        
        $('#iframe'+port).html('<iframe name="chatwindow'+ port +'" id="chatwindow'+ port +'" src="'+ viewer_url +'?port='+ port +'1" width="100%" height="350" border="0" frameborder="0" style="border: 1px solid #000;"/>');
        scroll_chat( port );
        
        $.post(
            raction_url,
            {
                action: 'join',
                nick: $('#nick'+port).val(),
                port: port
            },
            function() {
                $('#text'+port).focus();
            }
        );
    });
}

///////////////////////////////////////////////////////////////////////////////
function ping_chat( port ) {
    
    $.post(
        raction_url,
        {
            action: 'ping',
            id: $('#nick'+port).val(),
            port: port
        }
    );
}

///////////////////////////////////////////////////////////////////////////////
function scroll_chat( port ) {

    var cwindow = $('#chatwindow'+port);
    var autoscroll = $('#autoscroll'+port);
    var cwin = 'chatwindow'+port;
    if( autoscroll.is(':checked') ) {
        frames[cwin].document.body.scrollTop = frames[cwin].document.body.scrollHeight;
    }
    window.setTimeout( 'scroll_chat( '+ port +' )', 250 );
}   

///////////////////////////////////////////////////////////////////////////////
function send_rsay( port ) {

    var nick = $('#nick'+port).val();
    var text = $('#text'+port).val();
    if( text == '' || typeof( text ) == 'undefined' ) return;
    
    $('#text'+port).val('');
    $.post(
        rsay_url,
        { nick: nick, text: text, port: port },
        function() {
            $('#text'+port).focus();
        }
    );
}

///////////////////////////////////////////////////////////////////////////////
function refresh_server( id ) {

    $('#server_'+ id).load( '/server/'+ id +'/' );
}

///////////////////////////////////////////////////////////////////////////////
function download_map( map_id, dl_path ) {

    $.get( "/maps/logdownload/id/" + map_id, function() {
        window.location.href = dl_path;
    });
}

///////////////////////////////////////////////////////////////////////////////
function enable_mapTypes() {

/*
    $("#type_race, #type_fs").click(function() {

        var params = {

            controller: 'maps',
			action: 'index'
        };

        var $form = $(this).parents('form');

		$('input', $(this).parents('form')).each(function() {

            var name = $(this).attr('name');
			var value = $(this).val();
			
			if(name && value) {

			    console.log(name);
			    console.log(value);
				
			
				if ($('input[name='+ name +']', $form).length > 1) {
	                
					name = name.replace(/\[\]$/, '');
					
					if (!params[name]) {
	                    
						params[name] = new Array();
	                }
					
					if ($(this).is(':checked, :selected')) {
					
					    params[name].push(value);
					}
					
	            } else {
	
	                if (!$(this).is('.infotext') || $(this).attr('rel') != value) {
	
	                    params[name] = value;
	                }
	            }
			}
        });
		
		console.log(params);
    });
	
    $("#status_new, #status_enabled, #status_disabled").click(function() {

    });
    */
}

///////////////////////////////////////////////////////////////////////////////
function enable_mapAdmin() {

    $("select.map_admin").change(Racenet_MapAdmin);
}

///////////////////////////////////////////////////////////////////////////////
function Racenet_MapAdmin() {

     var originalValue = $(this).attr("alt");
    
     $("input.map_admin[rel="+ $(this).attr("rel") +"]").removeAttr("disabled").click(Racenet_MapAdmin.save);

     /*
	 $(window).unload(function (){
	 
	     if ($("input.map_admin:enabled").length) {

            if (!confirm("There are unsaved changes. Do you want to discard them?")) {

                return false;
            }
         }
	 });
	 */
}

///////////////////////////////////////////////////////////////////////////////
Racenet_MapAdmin.save = function() {

    var mapId = $(this).attr("rel");
    
	var $fsBox = $(".map_admin.type[rel="+ mapId +"]");
    var $statusBox = $(".map_admin.status[rel="+ mapId +"]");

    var status = $statusBox.val();
    var oldStatus = $statusBox.attr("alt");
    var freestyle = $fsBox.val();
    
    if (!confirm("Do you really want to mark '"+ $(".mapname[rel="+ mapId +"]").text() +"' as "+ status +" for "+ (freestyle == 'true' ? 'freestyle' : 'race') +"-mode?")) {
        $(this).attr("disabled", "disabled");
        $fsBox.val($fsBox.attr("alt"));
        $statusBox.val($statusBox.attr("alt"));
        return;
    }
    
    var $button = $(this).clone();
    $(this).replaceWith($('<img class="loader" rel="'+ mapId +'" style="margin-left: 13px;" src="/gfx/icons/loadani.gif" border="0" />'));
    
    var $disabled = $("input:enabled, select:enabled, textarea:enabled").attr("disabled", "disabled");
    
    $.ajax({
        type: 'POST',
        url: '/admin/maps/save/',
        data: {
            id: mapId,
            status: status,
			oldStatus: oldStatus,
            freestyle: freestyle == 'true' ? 1 : 0
        },
        success: function() {
            $("img.loader[rel="+ mapId +"]").replaceWith($button.attr("disabled", "disabled"));
            $disabled.removeAttr("disabled");
            if (!$("#status_"+ status).is(":checked")) {

                $button.parents("tr:visible").fadeOut();
                
            } else if (!$("#type_"+ (freestyle == 'true' ? 'fs' : 'race')).is(":checked")) {

                $button.parents("tr:visible").fadeOut();
            }
        }
    });
 }

///////////////////////////////////////////////////////////////////////////////
function enable_faqanswers() {

    $(".faq_item").click(faq_toggle);
	
	$('.faq.admin').sortable({
        update: function() {
            $('#faq_saveorder').removeAttr('disabled');
        }
    });
	
	$('.faq.admin .faq_view').click(faq_view);
	$('.faq.admin .faq_cancel').click(faq_cancel);
	$('.faq.admin .faq_save').click(faq_save);
	$('#faq_add').click(faq_add);
	$('#faq_saveorder').click(faq_saveOrder);
	
}

function faq_toggle()
{
    $('.faq_answer', $(this)).slideToggle();
    sign = $(".icon", $(this));
    if ( sign.text() == '+') {
        sign.text('-');
    } else {
        sign.text('+');
    }
}

function faq_saveOrder()
{
    var order = $('.faq.admin').sortable('serialize');
    
    $(this).attr('disabled', 'disabled');
    
    $.ajax({
            url: "/admin/faq/saveorder/",
            type: "POST",
            dataType: "text",
            data: order
    });
}

function faq_save()
{
    var id = $(this).parents('div').find('input').attr('rel');
    var question = $(this).parents('div').find('input').val();
    var answer = $(this).parents('div').find('textarea').val();
    $(this).parents('li').find('.faq_view').text(question);
    $(this).parents('div').find('.faq_cancel').click();
    
    $.ajax({
            url: "/admin/faq/save/",
            type: "POST",
            dataType: "text",
            data: "&id="+ id + "&question="+ question + "&answer="+ answer
    });
}

function faq_add()
{
    $('.faq.admin').append($('.faq.admin li').eq(0).clone());
	enable_faqanswers();
}

function faq_view()
{
    $(this).slideUp().parents('li').find('.faq_edit').slideDown();
}

function faq_cancel()
{
    $(this).parents('li').find('.faq_view').slideDown().end().find('.faq_edit').slideUp();
}

var uploadId;
var progressBar;
var progressUrl;

///////////////////////////////////////////////////////////////////////////////
function uploadProgress(identifier, url) {
    
    progressUrl = url;
    uploadId = $("#"+ identifier).val();
    progressBar = $(".progressbar[rel="+ uploadId +"]");
    
    $("#submitMap").hide();
    progressBar.show()
    
    $(".bar", progressBar).css("width", "1px");
    window.setInterval(fetchUploadProgress, 500);
}

///////////////////////////////////////////////////////////////////////////////
function fetchUploadProgress() {

    $.ajax({
        url: progressUrl,
        type: "POST",
        data: "uploadId="+ uploadId,
        dataType: "json",
        success: updateUploadProgress
    });
}

///////////////////////////////////////////////////////////////////////////////
function updateUploadProgress(info) {
    
    if (!info) return;
    
    var percent = Math.round(info.bytes_uploaded / info.bytes_total * 100);
    var kb = Math.round(info.speed_last / 1000);

    var time = '';
    if (info.est_sec / 3600 > 1) {
        var h = Math.floor(info.est_sec / 3600);
        time += " "+ h +" hour" + (h == 1 ? "" : "s");
        info.est_sec -= h * 3600;
    }
    if (info.est_sec / 60 > 1) {
        var m = Math.floor(info.est_sec / 60);
        time += " "+ m + " minute" + (m == 1 ? "" : "s");
        info.est_sec -= m * 60;
    }
    var s = info.est_sec;
    time += " "+ s + " second" + (s == 1 ? "" : "s") + " remaining";
    
    
    $(".progress", progressBar).text(percent +"%");
    $(".bar", progressBar).animate({width: percent +"%"}, 200);
    $(".speed").text(kb +" kb/s");
    $(".time", progressBar).text(time);
}