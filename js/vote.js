/**
* jQuery Voting System with PHP and MySQL
*/

jQuery( function($) {
	// any voting button (up/down) clicked event
	$('.vote').click(function(){
		var self = $(this); // cache $this
		var action = self.data('action'); // grab action data up/down 
		var parent = self.parent().parent(); // grab grand parent .item
		var postid = parent.data('postid'); // grab post id from data-postid
		var score = parent.data('score'); // grab score form data-score
		var email = parent.data('email');

		// only works where is no disabled class
		if (!parent.hasClass('.disabled')) {
			// vote up action
			if (action == 'up') {
				// increase vote score and color to orange
				parent.find('.vote-score').html(++score).css({'color':'#4f8000'});
				// change vote up button color to orange
				self.css({'color':'#4f8000'});
				
				// send ajax request with post id & action
				var data = {
    				action: 'voting_up',
    				'postid' : postid, 
    				'method' : 'up',
    				'email' : email
    			};
    			$.post(ajax_vote.ajaxurl, data, function(response) {
                });
                // add disabled class with .item
			    parent.addClass('.disabled');
			    fire();
			}
			// voting down action
			else if (action == 'down'){
				// decrease vote score and color to red
				parent.find('.vote-score').html(--score).css({'color':'red'});
				// change vote up button color to red
				self.css({'color':'red'});
				// send ajax request
				$.post({
				    action: 'voting_up',
				    data: 
				        {'postid' : postid, 'method' : 'down'}
				}); 
			};


		};
		   
	});
	
	$('#submit').click(function() {
        fire();
    });
    $('.close-reveal-modal').click(function() {
        userClosePopup();
    });  
    $('.underlay').click(function() {
        userClosePopup();
    });        
    $(document).mouseup(function (e)
    {
        var container = $("#ouibounce-modal");
    
        if (!container.is(e.target) // if the target of the click isn't the container...
            && container.has(e.target).length == 0) // ... nor a descendant of the container
        {
            container.fadeOut();
        }
    });       
    
    function fire() {
        // You can use ouibounce without passing an element
        // https://github.com/carlsednaoui/ouibounce/issues/30
    
        //if (el) el.style.display = 'block';
        var elem = $('#ouibounce-modal');
        if (elem) {
            $(elem).fadeIn();
        }
     }
     
    function userClosePopup() {
    	//showhidediv('ouibounce-modal',false); 
    	$('#ouibounce-modal').fadeOut();
    } 
    
    function showhidediv(elementname,show) {
    	if (show==true ) return;
    	var myelement = document.getElementById(elementname);
    	if (myelement) myelement.style.display = show ? "" : "none";
    }    	
});

