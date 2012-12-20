//-----------------------------------------------------------------------------------------------------------------------
var appId = '';

$(document).ready(function() {
	
	// login facebook
	window.fbAsyncInit = function() {
		
		FB.init({
			
		    appId      : (this).appId,
		    status     : true, 
		    cookie     : true,
		    xfbml      : true
		    
	    });
	
	};
	
});

function facebookInit( appId ) {
	
	(this).appId = appId;

}

//-----------------------------------------------------------------------------------------------------------------------

(function(d){
	
    var js, id = 'facebook-jssdk'; 
    
    if (d.getElementById(id)) {
    
    	return;
    
    }
    
    js = d.createElement('script'); js.id = id; js.async = true;
    js.src = "//connect.facebook.net/en_US/all.js";
    d.getElementsByTagName('head')[0].appendChild(js);
    
}(document));

//-----------------------------------------------------------------------------------------------------------------------

/*(function(d, s, id) {
	
    var js, fjs = d.getElementsByTagName(s)[0];
    
    if (d.getElementById(id)) {
    	return;
    }
    
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
    fjs.parentNode.insertBefore(js, fjs);
    
}( document, 'script', 'facebook-jssdk') );*/

//-----------------------------------------------------------------------------------------------------------------------

function fbComplete() {
	
    FB.getLoginStatus(function(response) {

        console.debug(response);

        FB.api('/me?fields=email,name,birthday,location,link', function(response) {
	
            console.debug(response);
        });

    });
    
    showLoading();
    
    window.location.reload( true );
}

//-----------------------------------------------------------------------------------------------------------------------

function fbLogout ( site_permalink ){
		
	FB.logout(function(response) {

	   window.location.href = '/' + site_permalink + '/user/logout';
    	
		 
	});
}