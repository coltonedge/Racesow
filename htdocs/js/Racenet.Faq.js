/**
 * Racenet FAQ
 */
Racenet.Faq = function (options) {

	this.options = options;
	
	if (typeof this.options != 'object') {
        this.options = {};
    }
	
	this.constructor = function() {

		if (typeof this.options.admin == 'undefined') {

            this.options.admin = false;
        }
		
		if (this.options.admin) {

            $('.faq').sortable({
	            update: function() {
	                $('#faq_saveorder').removeAttr('disabled');
	            }
	        });
	        
	        $('.faq.admin .faq_view').click(_faqAdminEdit);
	        $('.faq.admin .faq_cancel').click(_faqAdminCancel);
	        $('.faq.admin .faq_del').click(_faqAdminDelete);
	        $('.faq.admin .faq_save').click(_faqAdminSave);
	        $('#faq_add').click(_faqAdminAdd);
	        $('#faq_saveorder').click(_faqAdminOrder);
			
        } else {

		    $('.faq_item').click(_faqToggle);
		}
    }
    
	/**
	 * Close/open the questions's answers
	 * 
	 * @access private
	 */
	var _faqToggle = function() {

	    var $box = $('.faq_answer', $(this))
		var $icon = $('.icon', $(this));
		
		$box.slideToggle();
		
	    if ($icon.text() == '+') {
	        $icon.text('-');
	    } else {
	        $icon.text('+');
	    }
	}
	
	/**
	 * faqAdmin
	 * 
	 * @access private
	 */
	var _faqView = function () {

        $(this).slideUp().parents('li').find('.faq_edit').slideDown();
    }
	
	/**
	 * Save the current order of all FAQ items
	 * 
	 * @access private
	 */
	var _faqAdminOrder = function() {

		var order = $('.faq.admin').sortable('serialize');
	    
	    $(this).attr('disabled', 'disabled');
	    $("#main_loader").fadeIn();
	    
	    $.ajax({
	            url: "/admin/faq/saveorder/",
	            type: "POST",
	            dataType: "text",
	            data: order,
	            success: function(res) {
	                $("#main_loader").fadeOut();
	            }
	    });
	}

    /**
     * Save the question/answer form's content
     * 
     * @access private
     */
	var _faqAdminSave = function() {

        var $item = $(this).parents('#faq_new');
		
		if (!$item.length) {
            $item = $(this).parents('li');
        }

	    var $input = $('input', $item);
	    var $deleteButton = $('.faq_del', $item);
		var id = $input.attr('rel');
	    var question = $('input', $item).val();
	    var answer = $('textarea', $item).val();
        
				
		if (!question || !answer) {

            return alert("You need to enter question and answer!");
        }
		
		
		
		$item.find('.faq_view').text(question).end().find('.faq_cancel').click();
	    
	    $("#main_loader").fadeIn();
	    
	    $.ajax({
	            url: "/admin/faq/save/",
	            type: "POST",
	            dataType: "text",
	            data: "&id="+ id + "&question="+ question + "&answer="+ answer,
	            success: function(faqId) {
	                $("#main_loader").fadeOut();
					$input.attr('rel', faqId);
					$item.attr('id', 'faq_'+ faqId);
					$deleteButton.click(_faqAdminDelete);
	            }
	    });
	}
	
	/**
	 * Add a new FAQ item to the list
	 * 
	 * @access private
	 */
	var _faqAdminAdd = function() {

	    $item = $('.faq.admin li').eq(0).clone();
		$item.attr('id', 'faq_new');
		 
	    $('.faq_view', $item).click(_faqAdminEdit).text("");
        $('.faq_cancel', $item).click(_faqAdminCancel);
        $('.faq_del', $item).click(_faqAdminCancel);
        $('.faq_save', $item).click(_faqAdminSave);
	    
	    $('.faq_edit .faq_question', $item).val('');
	    $('.faq_edit .faq_answer', $item).val('');
	    $('*', $item).removeAttr('rel');
	
	    $('.faq.admin').append($item);
	    
	    $item.find('.faq_view').click();
	}
	
	/**
	 * Delete a question from the DB
	 * 
	 * @access private
	 */
	var _faqAdminDelete = function() {

        if (!confirm("you you really want to deleted this item?")) {
            return;
        }
		
		$("#main_loader").fadeIn();
		
		var $input = $('input', $(this).parents('div'));
		var $output = $('.faq_view', $(this).parents('li'));
        var $cancelButton = $(this).parents('div').find('.faq_cancel');
        var id = $input.attr('rel');
		
		$.ajax({
                url: "/admin/faq/delete/",
                type: "POST",
                dataType: "text",
                data: "&id="+ id,
                success: function(faqId) {
                    $("#main_loader").fadeOut();
					$output.text(''); // so it will be removed when cancel is clicken
                    $cancelButton.click();
                }
        });
    }
	
	/**
	 * Show the form for a question/answer couple
	 * 
	 * @access private
	 */
	var _faqAdminEdit = function() {

	    $(this).slideUp().parents('li').find('.faq_edit').slideDown();
	}
	
	/**
	 * Hide the question/answer form
	 * 
	 * @access private
	 */
	var _faqAdminCancel = function() {

        $(this).parents('li').find('.faq_view').slideDown("medium",function() {
        
          if ($(this).text() == "") {
            $(this).parents('li').remove();
          }
                
        }).end().find('.faq_edit').slideUp();
    }
	
	this.constructor();
}
