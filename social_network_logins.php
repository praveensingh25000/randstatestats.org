<!-- socialize.js script should only be included once -->
<script type="text/javascript" src="http://cdn.gigya.com/js/socialize.js?apiKey=3_Pb6ZDx0QQaJ4-I0RoFsYOCd0by0wFVS-9rgXc60cZqxSBfVC18XByjs6xFBJXvnY">
{
	siteName: 'randstatestats.org (randstatestats.org)'
	,enabledProviders: 'facebook,twitter,googleplus,linkedin'
}
</script>

<script>
// This method is activated when the page is loaded
jQuery(document).ready(function(){
	// register for login event
	gigya.socialize.addEventHandlers({
	context: { str: 'congrats on your' }
	, onLogin: onLoginHandler
	});
});

// onLogin Event handler
function onLoginHandler(eventObj) {	
	//alert(eventObj.context.str + ' ' + eventObj.eventName + ' to ' + eventObj.provider + '!\n' + eventObj.provider + ' user ID: ' +  eventObj.user.identities[eventObj.provider].providerUID);
	// verify the signature ...
	verifyTheSignature(eventObj.UID, eventObj.timestamp, eventObj.signature);

	// Check whether the user is new by searching if eventObj.UID exists in your database
	var newUser = true; // lets assume the user is new
	
	if (newUser) {
		// 1. Register user 
		// 2. Store new user in DB
		// 3. link site account to social network identity
		// 3.1 first construct the linkAccounts parameters

		// Current time in Unix format(i.e. the number of seconds since Jan. 1st 1970)
		var dateStr = Math.round(new Date().getTime()/1000.0); 
		
		// siteUID should be taken from the new user record you have stored in your DB in the previous step
		var siteUID = 'uTtCGqDTEtcZMGL08w'; 
		var yourSig = createSignature(siteUID, dateStr);

		var params = {
			siteUID: siteUID, 
			timestamp:dateStr,
			cid:'',
			signature:yourSig
		};
		
		//  3.1 call linkAccounts method:
		gigya.socialize.notifyRegistration(params);
	}
}

// Note: the actual signature calculation implementation should be on server side encode the UID parameter before sending it to the server.On server side use decodeURIComponent() function to decode an encoded UID
function createSignature(UID, timestamp) {
	encodedUID = encodeURIComponent(UID); 
	return '';
}

// Note: the actual signature calculation implementation should be on server side encode the UID parameter before sending it to the server.On server side use decodeURIComponent() function to decode an encoded UID
function verifyTheSignature(UID, timestamp, signature) {
	encodedUID = encodeURIComponent(UID); 
	//alert('Your UID: ' + UID + '\n timestamp: ' + timestamp + '\n signature: ' + signature + '\n Your UID encoded: ' + encodedUID);
}

// Logout from Gigya platform. This method is activated when "Logout" button is clicked 
//function logoutFromGS() {
	//gigya.services.socialize.logout(); // logout from Gigya platform
//}

</script>

<div id="loginDiv"></div>
<script type="text/javascript">
	gigya.socialize.showLoginUI({containerID: "loginDiv", cid:'', width:220, height:60,
	redirectURL: URL_SITE+"/social_network_loginDetails.php",
	showTermsLink:false, 
	hideGigyaLink:true // remove 'Terms' and 'Gigya' links
	});
</script>