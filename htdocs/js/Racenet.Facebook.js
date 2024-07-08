window.fbAsyncInit = function() {

	FB.init({
		appId      : '242776135771357',
		status     : true, 
		cookie     : true,
		xfbml      : true
	});
  
	FB.getLoginStatus(function(response) {
		if (!response.session) {
			$('#fb-login').html('<a href="javascript:void(0);" onclick="fb_login();"><img src="/gfx/fblogin.png" border="0"></a>');
		} else {
			fb_getUserData();
		}
	});
  
	fb_getUserData = function() {
	
		FB.api('/me', function(user) {
			 $.ajax({
				url: '/settings/facebook',
				data: user,
				type: 'POST',
				dataType: 'json',
				success: function( response ) {
					console.log(response);
					switch (response.code) {
					
						case 1:
							alert("Your racenetID is now connected to your facebook profile.");
							break;
							
						case 2:
							// nothing todo, is logged in via faceebook
							break;
							
						case 3:
							window.location.reload();
							break;
							
						case 4:
							alert('Welcome '+ user.name + ',\nPlease login with your racenetID or register a new\nacount to connect it with your facebook profile.');
							break;
					}
				}
			});
		});
	}
  
	fb_login = function() {
  
		FB.login(function(response) {
			if (response.session) {
				fb_getUserData();
			} else {
				alert('User cancelled login or did not fully authorize.');
			}
		},{scope: 'email'});
	}
};