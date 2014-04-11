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
				document.getElementById("input_link").style.display="";
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
	document.getElementById("my_events").style.display="";
	FB.api('/fql', {q: query}, function(obj) {
		//console.log(obj)
		document.getElementById("my_events").innerHTML="<div class='span12'><p>Meus eventos</p></div>";

		for(i=0; i<obj['data'].length;i++){
			search_id = obj['data'][i]['eid'];
			search_name = obj['data'][i]['name'];
			if(search_name.length > 20){
				search_name = search_name.substring(0,20)+" ...";					
			}
			search_pic = obj['data'][i]['pic_small'];
			search_venue = obj['data'][i]['venue'];
			document.getElementById("my_events").innerHTML+="<div class='row-fluid'><div class='item_search'><a style='color:white; cursor:pointer;' onclick='event_show("+search_id+");'  ><img src='"+search_pic+"' style='width:48px; margin:10px;' /><p style='display:inline;'>"+search_name+"</p></a></div></div>";
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
			//console.log('Access Token = '+ access_Token);
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
			var namesArray = new Array();
			document.getElementById("search_box").innerHTML="<div class='span12' style='text-align:center;' ><p>Pesquisando ... </p></div>";
			//FB.api('/fql?access_token='+window.fbtoken, {q: query}, function(obj) {
			FB.api('/fql', {q: query}, function(obj) {
				document.getElementById("search_box").innerHTML="";
				//console.log(obj)
				for(i=0; i<obj['data'].length;i++){
					search_id = obj['data'][i]['eid'];
					search_name = obj['data'][i]['name'];
					if(search_name.length > 20){
						search_name = search_name.substring(0,20)+" ...";					
					}
					search_pic = obj['data'][i]['pic_small'];
					search_venue = obj['data'][i]['venue'];
					if(namesArray.indexOf(search_name) < 0){
						namesArray.push(search_name);
						document.getElementById("search_box").innerHTML+="<div class='row-fluid'><div class='item_search'><a style='color:white; cursor:pointer;' onclick='event_show("+search_id+");'  ><img src='"+search_pic+"' style='width:48px; margin:10px;' /><p style='display:inline;'>"+search_name+"</p></a></div></div>";
					}
				}
			});
			window.countdown=1;
		}, 1000);
	}
}

function event_show(search_id){

	document.getElementById("event_detail").style.display="";
	document.getElementById("search_input").style.display="none";
	document.getElementById("search_box").style.display="none";
	document.getElementById("my_events").style.display="none";
	var string = search_id;

	var query = 'select privacy,name,location,start_time,end_time,description,attending_count from event WHERE eid = "'+search_id+'"';
	FB.api('/fql?access_token='+window.fbtoken, {q: query}, function(obj) {
		//console.log(obj)
		event_privacy = obj['data'][0]['privacy']; 
		event_name = obj['data'][0]['name']; 		
		event_start_time = obj['data'][0]['start_time']; 		
		event_end_time = obj['data'][0]['end_time']; 		
		event_location = obj['data'][0]['location'];	
		event_description = obj['data'][0]['description']; 		
		event_attending = obj['data'][0]['attending_count']; 		
		if(!event_location){event_location=""};
		if(!event_start_time){event_start_time=""};
		if(!event_end_time){event_end_time=""};
		document.getElementById("header").innerHTML='<div class="span12"><a style="text-decoration:none;" target="_blank" href="https://www.facebook.com/events/'+search_id+'"><h1>'+event_name+'</h1></a><p>'+event_location+'</p></div>';
	});

	var query = 'select uid,sex,relationship_status from user where uid IN ( SELECT uid,rsvp_status from event_member WHERE eid = "'+search_id+'" and rsvp_status = "attending")';
	var bar="";
	var male="";
	var female="";
	FB.api('/fql?access_token='+window.fbtoken, {q: query}, function(obj) {
		for(k=0; k<obj['data'].length;k++){
			setTimeout(function(){
				i=document.getElementById("counter_i").innerHTML;
				id=obj['data'][i]['uid'];
				gender=obj['data'][i]['sex'];
				relationship=obj['data'][i]['relationship_status'];
				if(gender=="male"){
					if(document.getElementById("male_count").innerHTML<84){
						document.getElementById("male_column").innerHTML+='<div style="display:inline;"><a target="_blank" href="http://facebook.com/'+id+'"><img src="http://graph.facebook.com/'+id+'/picture?type=square" id="'+id+'"  /></a></div>';
					}
					document.getElementById("male_count").innerHTML++;
					document.getElementById("total_count").innerHTML++;
				}else{
					if(document.getElementById("female_count").innerHTML<84){
						document.getElementById("female_column").innerHTML+='<div style="display:inline;"><a target="_blank" href="http://facebook.com/'+id+'"><img src="http://graph.facebook.com/'+id+'/picture?type=square" id="'+id+'"  /></a></div>';
					}
					document.getElementById("female_count").innerHTML++;
					document.getElementById("total_count").innerHTML++;
				}

				bar_male = (document.getElementById("male_count").innerHTML*100/document.getElementById("total_count").innerHTML).toFixed(0);
				bar_female = (100 - bar_male).toFixed(0);
				male = document.getElementById("male_count").innerHTML;
				female = document.getElementById("female_count").innerHTML;
				document.getElementById("event_stats").innerHTML=

				"<div class='row-fluid ' style='margin-top:10px;'>"+
					"<div class='span10 offset1' style='text-align:center;'>"+
						"<p style='float:left; text-align:center;'><b style='margin:5px; display:inline; font-size:42px;'>"+bar_male+" % </b></p>"+
						"<p style='float:right;  text-align:center;'><b style='margin:5px; display:inline; font-size:42px;'>"+bar_female+" % </b></p>"+
					"</div>" +
				"</div>" +
				"<div class='row-fluid '>"+
					"<div class='span10 offset1' style='text-align:center;'>"+
						"<div style='width:100%; background:#ff88de; padding:0px; height:40px; border-radius:15px; overflow:hidden; -moz-box-shadow: inset 0 0 5px #000000; -webkit-box-shadow: inset 0 0 5px #000000; box-shadow: inset 0 0 5px #000000;'>"+
						"<div style='width:"+bar_male+"%; background:#5F5; height:40px; -moz-box-shadow: inset 0 0 5px #000000; -webkit-box-shadow: inset 0 0 5px #000000; box-shadow: inset 0 0 5px #000000;'></div>"+
						"</div>"+
					"</div>"+
				"</div>"+
				"<div class='row-fluid ' style='margin-top:10px;'>"+
					"<div class='span10 offset1' style='text-align:center;'>"+
						"<p style='float:left; text-align:center;'><img src='img/male.png' style='width:32px;' /><b style='margin:5px; display:inline;'>"+male+"</b></p>"+
						"<p style='float:right;  text-align:center;'><b style='margin:5px; display:inline;'>"+female+"</b><img src='img/female.png' style='width:32px;' /></p>"+
					"</div>"+
				"</div>"+
				"<div class='row-fluid '>"+
					"<div class='span10 offset1' style='text-align:center;margin-top:-45px; margin-bottom:20px;'>"+
						"<a href='index.php' class='btn btn-large'><i class='icon-home'></i></a>"+
						"<!--"+
						"<a href='index.php?event="+search_id+"' class='btn btn-large'><i class='icon-refresh'></i></a>"+
						"<a class='btn btn-large' href='http://www.facebook.com/share.php?u=http://mulherometro.com/index.php?event="+search_id+"&amp;t=Compartilhar_mulherometro' onclick='window.open('http://www.facebook.com/share.php?u=http://mulherometro.com/index.php?event=591480820920928&amp;t=Compartilhar_mulherometro','popup','width=655,height=405,scrollbars=no,resizable=no,toolbar=no,directories=no,location=no,menubar=no,status=no,left=0,top=0'); return false' target='_blank'> <i class='icon-share'></i></a>"+
						"<div class='btn-group'>"+
							"<button onclick='show_select();' id='btn_select' class='btn btn-large'><i class='icon-check'></i></button>"+
							"<button onclick='show_view();' id='btn_view' class='btn  btn-large disabled'><i class='icon-eye-open'></i></button>"+
						"</div>"+
						"-->"+
					"</div>"+
				"</div>";


				document.getElementById("counter_i").innerHTML++;

			},k*50);
		}
	});

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

