jQuery( function($) {
   
    
    window.fbAsyncInit = function() {
        FB.Event.subscribe('edge.create',
             function(response) {
                   //_gaq.push(['_trackSocial', 'facebook', 'like', document.location.href]);
                   console.log("voting: " + response + " ");
                   fire();
             }
        );
        

       
    }; 
    
    
});

  
