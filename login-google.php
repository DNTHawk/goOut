<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script src="https://apis.google.com/js/platform.js" async defer></script>
	<meta name="google-signin-client_id" content="771512797239-u8lva28kn04692o9andqfivhlbop6umr.apps.googleusercontent.com">
</head>
<body>
	<div class="g-signin2" data-onsuccess="onSignIn"></div>

	<p id="msg1"></p>

	<p id="msg2"></p>

	<script type="text/javascript">
	function onSignIn(googleUser) {
	  	var profile = googleUser.getBasicProfile();
	  	var userID = profile.getId(); 
	  	var userName = profile.getName();
	  	var urlImage = profile.getImageUrl();
	  	var userEmail = profile.getEmail();
		var userToken = googleUser.getAuthResponse().id_token;

		document.getElementById('msg1').innerHTML = userName;
		document.getElementById('msg2').innerHTML = userEmail;	
	}
</script>
</body>
</html>