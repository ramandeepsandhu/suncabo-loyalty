<!--https://tzamtzis.gr/2011/web-design/execute-javascript-facebook-button-clicked/ -->

<!--
<div>
<div id="fb-root"></div>
<script src="http://connect.facebook.net/en_US/all.js"></script>
<script>
FB.init({
appId  : '1403473566365645',
status : true, // check login status
cookie : true, // enable cookies to allow the server to access the session
xfbml  : true, // parse XFBML
channelUrl : 'http://localhost/loyality/channel.html', // channel.html file
oauth  : true // enable OAuth 2.0
});
</script>
</div>


<script>
   FB.Event.subscribe('edge.create', function(href, widget) {
	alert("Like button clicked");
   });
</script>
<script>
   FB.Event.subscribe('edge.remove', function(href, widget) {
	alert("Like button unclicked");
   });
</script>


<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=1403473566365645&amp;xfbml=1"></script><fb:like href="https://www.facebook.com/BritishAcademyJaitu" send="false" width="450" show_faces="true" action="like" font=""></fb:like> -->
<!--<script src="http://connect.facebook.net/en_US/all.js"></script><script>  
            FB.init({
                appId: '1403473566365645', 
                status: false,
                cookie: false, 
                xfbml: true
            });FB.Event.subscribe('edge.create', handleResponse);
var handleResponse = function(response) {
   alert ('You liked the URL: ' + response);
};FB.Event.unsubscribe('edge.create', handleResponse);(function (d) {
            var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
            if (d.getElementById(id)) { return; }
            js = d.createElement('script'); js.id = id; js.async = true;
            js.src = "//connect.facebook.net/en_US/all.js";
            ref.parentNode.insertBefore(js, ref);
        } (document));</script>
    <fb:like href="https://www.facebook.com/BritishAcademyJaitu" send="false" layout="button_count" width="200" show_faces="false"></fb:like>-->
<!--<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8&appId=1403473566365645" nonce="tRGaHh8W"></script>-->
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '801274146550373',
      cookie     : true,
      xfbml      : true,
      version    : 'v19.0',
      status	: true,
      logging: true
    });FB.Event.subscribe('edge.create', handleResponse);FB.Event.unsubscribe('edge.create', handleResponse);
 	var handleResponse = function(response) {
 		alert('hi');
   		console.log ('You liked the URL: ' + response);
	};
    FB.AppEvents.logPageView();   
  };(function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
	<div class="fb-like"  onclick="Login();" data-href="https://www.facebook.com/twinklesdaycare" data-width="" data-layout="" data-action="" data-size="" data-share="true"></div>
	<input type="button" value="Login" onclick="Login();" />
	<script>function Login() {
     FB.Event.subscribe('edge.create', handleResponse);} function handleResponse(url, html_element) {
 		console.log('hi');
   		console.log ('You liked the URL: ' + response);
	};</script>