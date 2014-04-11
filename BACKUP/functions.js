window.countdown=1;

function facebook_init(){
    FB.init({
      appId      : '180386015481989',                        // App ID from the app dashboard
      channelUrl : '//mulherometro.com/index.php', // Channel file for x-domain comms
      frictionlessRequests: true,
      oauth: true,
      status     : true,                                 // Check Facebook Login status
      xfbml      : true                                  // Look for social plugins on the page
    });
    FB.getLoginStatus(function(response){
	facebook_check();
    });

  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-44614857-1', 'mulherometro.com');
  ga('send', 'pageview');

}


function facebook_check(){
	FB.getLoginStatus(function(response) {
		if (response.status === 'connected') {
			if(document.getElementById("facebook_button")){
				document.getElementById("facebook_button").style.display="none";
			}

			var uid = response.authResponse.userID;
			var accessToken = response.authResponse.accessToken;
			//console.log('Access Token = '+ accessToken);
			facebook_show_user_events();
		}else if (response.status === 'not_authorized') {
			//facebook_login();
		}else{
			//facebook_login();
		}
	});
}

function facebook_show_user_events(){
	var query = 'SELECT eid, name, pic_small, description, start_time, end_time, location FROM event WHERE privacy="OPEN" AND eid IN (SELECT eid FROM event_member WHERE uid=me() and start_time >=now())';
//	var query = 'SELECT eid, name, pic_small, description, start_time, end_time, location FROM event WHERE privacy="OPEN" AND eid IN (SELECT eid FROM event_member WHERE uid=me() and start_time >=now()) OR eid IN (SELECT eid from event_member WHERE uid IN (SELECT uid2 FROM friend WHERE uid1=me()) and start_time >=now() LIMIT 10)';
	document.getElementById("my_events").innerHTML="";
	FB.api('/fql', {q: query}, function(obj) {
		//console.log(obj)
		//document.getElementById("my_events").innerHTML+="<h1>Meus eventos</h1>";

		for(i=0; i<obj['data'].length;i++){
			search_id = obj['data'][i]['eid'];
			search_name = obj['data'][i]['name'];
			search_pic = obj['data'][i]['pic_small'];
			search_venue = obj['data'][i]['venue'];
			document.getElementById("my_events").innerHTML+="<a href='index.php?event="+search_id+"' ><img src='"+search_pic+"' style='width:64px; margin:5px;' /></a>";
		}
	});
}

function search_show(){
	document.getElementById("search_box").style.display="none";
	if(document.getElementById("input_link").value){
		document.getElementById("search_box").style.display="";
	}
}

function facebook_login(){
	FB.login(function(response) {
		if (response.authResponse) {
			var access_Token =   FB.getAuthResponse()['accessToken'];
			console.log('Access Token = '+ access_Token);
			document.getElementById("facebook_button").style.display="none";
			//facebook_show_user_events();
			location.reload();
		}
	}, {scope: 'user_relationships,user_birthday,user_events,friends_events'});
}

function facebook_logout(){
	FB.logout(function(response) {
		console.log("Logout ok");
		location.reload();
	});
}

function search_drop(){
	if(window.countdown==1){
		window.countdown=0;
		document.getElementById("search_box").style.display="";
		var realtime = window.setTimeout(function (){
			var string = document.getElementById("input_link").value;
			//var query = 'SELECT eid,name,venue,pic_small FROM event WHERE CONTAINS ("'+string+'") AND start_time >= now() AND privacy = "OPEN" AND venue.country="brazil" ORDER BY name ASC Limit 30';
			var query = 'SELECT eid,name,venue,pic_small FROM event WHERE CONTAINS ("'+string+'") AND start_time >= now() AND privacy = "OPEN" ORDER BY name ASC Limit 30';

			document.getElementById("search_box").innerHTML="";
			FB.api('/fql', {q: query}, function(obj) {
				//console.log(obj)
				for(i=0; i<obj['data'].length;i++){
					search_id = obj['data'][i]['eid'];
					search_name = obj['data'][i]['name'];
					if(search_name.length > 20){
						search_name = search_name.substring(0,20)+" ...";					
					}
					search_pic = obj['data'][i]['pic_small'];
					search_venue = obj['data'][i]['venue'];
					document.getElementById("search_box").innerHTML+="<a style='font-size:14px;' href='index.php?event="+search_id+"' ><img src='"+search_pic+"' style='width:48px; margin-right:10px;' />"+search_name+"</a><br/><br/>";
				}
			});
			window.countdown=1;
		}, 1000);
	}
}



function select(id){
	if(document.getElementById(id).style.border=='2px solid white'){
		console.log('unselect');
		document.getElementById(id).style.border='0px solid white';
		document.getElementById(id).style.margin='2px';
		document.getElementById(id).style.opacity='0.2';
	}else{
		console.log('select');
		document.getElementById(id).style.border='2px solid white';
		document.getElementById(id).style.margin='0px';
		document.getElementById(id).style.opacity='1';
	}
}

function show_view(){
	document.getElementById("div_select").style.display='none';
	document.getElementById("div_view").style.display='';
	document.getElementById("btn_select").className='btn btn-large';
	document.getElementById("btn_view").className='btn  btn-large disabled';
	document.getElementById("select_message").style.display='none';
}

function show_select(){
	document.getElementById("div_select").style.display='';
	document.getElementById("div_view").style.display='none';
	document.getElementById("btn_select").className='btn  btn-large disabled';
	document.getElementById("btn_view").className='btn btn-large';
	document.getElementById("select_message").style.display='';
}

function loading(){
	document.getElementById("input_link").style.display='none';
	document.getElementById("input_button").style.display='none';
//	document.getElementById("search_box").style.display='none';
//	document.getElementById("facebook_button").style.display='none';
	document.getElementById("loading").style.display='';
	window.setInterval(blink, 500);
}

function blink(){
	if(document.getElementById("load_anim").innerHTML=='...'){
		document.getElementById("load_anim").innerHTML='';
	}

	if(document.getElementById("load_anim").innerHTML=='..'){
		document.getElementById("load_anim").innerHTML='...';
	}

	if(document.getElementById("load_anim").innerHTML=='.'){
		document.getElementById("load_anim").innerHTML='..';
	}

	if(document.getElementById("load_anim").innerHTML==''){
		document.getElementById("load_anim").innerHTML='.';
	}
}
