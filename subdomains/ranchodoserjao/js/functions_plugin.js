function facebook_init(){
    FB.init({
      appId      : '180386015481989',                        // App ID from the app dashboard
      channelUrl : '//ranchodoserjao.mulherometro.com/index.php', // Channel file for x-domain comms
      frictionlessRequests: true,
      oauth: true,
      status     : true,                                 // Check Facebook Login status
      xfbml      : true                                  // Look for social plugins on the page
    });

  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-44614857-1', 'mulherometro.com');
  ga('send', 'pageview');

	FB.getLoginStatus(function(response){
		//var accessToken = response.authResponse.accessToken;
		if(window.location.search){
			var event_id = window.location.search.replace("?event=","");
		}else{
			var event_id = "1454054361489648";
		}
		setTimeout(function () { event_show(event_id); }, 3000);
	});


}

function event_show(search_id){

	document.getElementById("event_detail").style.display="";
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
		//document.getElementById("header").innerHTML='<div class="span12"><a style="text-decoration:none;" target="_blank" href="https://www.facebook.com/events/'+search_id+'"><h3>'+event_name+'</h3></a><p>'+event_start_time+'</p></div>';
		document.getElementById("header_event").innerHTML='<div class="span12"><a style="text-decoration:none;" target="_blank" href="https://www.facebook.com/events/'+search_id+'"><p>'+event_name+'</p></a></div>';
	});

	var query = 'select uid,sex,relationship_status from user where uid IN ( SELECT uid,rsvp_status from event_member WHERE eid = "'+search_id+'" and rsvp_status = "attending")';
	var bar="";
	var male="";
	var female="";
	FB.api('/fql?access_token='+window.fbtoken, {q: query}, function(obj) {
//	FB.api('/fql', {q: query}, function(obj) {
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
						"<div style='width:100%; background:#ff88de; padding:0px; height:20px; border-radius:15px; overflow:hidden; -moz-box-shadow: inset 0 0 2px #000000; -webkit-box-shadow: inset 0 0 2px #000000; box-shadow: inset 0 0 2px #000000;'>"+
						"<div style='width:"+bar_male+"%; background:#5F5; height:20px; -moz-box-shadow: inset 0 0 2px #000000; -webkit-box-shadow: inset 0 0 2px #000000; box-shadow: inset 0 0 2px #000000;'></div>"+
						"</div>"+
					"</div>"+
				"</div>"+
				"<div class='row-fluid ' style='margin-top:10px;'>"+
					"<div class='span10 offset1' style='text-align:center;'>"+
						"<p style='float:left; text-align:center;'><img src='img/male.png' style='width:32px;' /><b style='margin:5px; display:inline;'>"+male+"</b></p>"+
						"<p style='float:right;  text-align:center;'><b style='margin:5px; display:inline;'>"+female+"</b><img src='img/female.png' style='width:32px;' /></p>"+
					"</div>"+
				"</div>";


				document.getElementById("counter_i").innerHTML++;

			},k);
		}
	});

}


