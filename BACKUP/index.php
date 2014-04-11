<?php 
set_time_limit (60);

//http://developers.facebook.com/docs/reference/api/
//$url=file_get_contents("https://graph.facebook.com/302567693090678/events?access_token=$token&limit=20");
//$url=file_get_contents("https://graph.facebook.com/search?q=coffee&type=place&center=37.76,-122.427&distance=1000&access_token=$token");
//$url=file_get_contents("https://graph.facebook.com/search?q=my friends who like acdc&access_token=$token");
$app_id = "180386015481989";
$app_secret = "aaea528d446f0dfa15f1e84356d0baae";
$app_token_url = "https://graph.facebook.com/oauth/access_token?"
. "client_id=" . $app_id
. "&client_secret=" . $app_secret 
. "&grant_type=client_credentials";

$response = @file_get_contents($app_token_url);
$params = null;
parse_str($response, $params);

$token=$params['access_token'];

if(@$_POST['event'] || @$_GET['event']){
	$gender_list = array();
	$event = @explode("/",$_POST['event']);
	$event = @$event[4];

	if(@$_GET['event']){ 
		$event = @$_GET['event'];
	}

	$url = @file_get_contents("https://graph.facebook.com/fql?access_token=$token&q=select+uid,sex,relationship_status+from+user+where+uid+IN+(+SELECT+uid,rsvp_status+from+event_member+WHERE+eid+=+$event+and+rsvp_status+=+'attending')");
	$obj = json_decode($url,true);

	$url_info = @file_get_contents("https://graph.facebook.com/fql?access_token=$token&q=select+privacy,name,location,start_time,end_time,description,attending_count+from+event+WHERE+eid+=+$event");
	$obj_info = json_decode($url_info,true);

	$event_privacy = @$obj_info['data'][0]['privacy']; 
	$event_name = @$obj_info['data'][0]['name']; 		
	$event_start_time = @$obj_info['data'][0]['start_time']; 		
	$event_end_time = @$obj_info['data'][0]['end_time']; 		
	$event_location = @$obj_info['data'][0]['location'];	
	$event_description = @$obj_info['data'][0]['description']; 		
	$event_attending = @$obj_info['data'][0]['attending_count']; 		

	$total=0;
	$male=0;
	$female=0;

	$male_photos=array();
	$female_photos=array();

	if($event_privacy!=="OPEN"){
		echo "<meta HTTP-EQUIV='REFRESH' content='0; url=index.php?error=privacy'>";
		die;
	}

	for($i=0; $i<$event_attending; $i++){
		$id=@$obj['data'][$i]['uid'];
		$gender=@$obj['data'][$i]['sex'];
		$relationship=@$obj['data'][$i]['relationship_status'];
		if($gender=="male"){
			$male_photos[]= "<div style='display:inline;' ><a target='_blank' href='http://facebook.com/$id'><img src='http://graph.facebook.com/$id/picture?type=square' id='profile_$id'  /></a></div>";
			$male++;
			$total++;
		}elseif($gender=="female"){
			$female_photos[]="<div style='display:inline;'><a target='_blank' href='http://facebook.com/$id'><img src='http://graph.facebook.com/$id/picture?type=square' id='profile_$id'  /></a></div>";
			$female++;
			$total++;	
		}
	}

	$percent_male = @number_format(($male/$total)*100,1);
	//$percent_female  = number_format(($female/$total)*100,1);
	$percent_female = 100 - $percent_male;
	$percent_female = @number_format($percent_female,1);

}else{
	$percent_male = 0;
	$percent_female  = 0;
}


echo "
<!DOCTYPE html>
<html lang='en'>
  <head>
    <meta charset='UTF-8'>
    <title>Mulherômetro</title>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta name='description' content=''>
    <meta name='author' content=''>
   <meta property='fb:admins' content='felippe.gallogomes'/>

	<link href='css/bootstrap.css' rel='stylesheet'>
	<link href='css/social-buttons.css' rel='stylesheet'>
	<link href='css/stickyfooter.css' rel='stylesheet'>

    <style type='text/css'>
      body {
        padding-top: 20px;
        padding-bottom: 40px;
        background-image:url('img/bg.png');
	color:white;
	font-family: Verdana,Arial,serif;
	text-shadow: 1px 1px #333;
      }

      .bg {
	background:rgba(0,0,100,0.5);
	height:auto;
	min-height:100%;
	width:100%;
	position:absolute;
	top:0px;
	left:0px;
      }

	::-webkit-scrollbar
	{
		width: 4px;
	}

	::-webkit-scrollbar-thumb
	{
		border-radius: 2px;
		background-color: #24335A;
	}

	::-webkit-scrollbar-track
	{
		border-radius: 10px;
	}

	h1,h2,h3,h4{
		color:white;
	}

	.photos1 img {
		box-shadow: 0 0 2px #fff, 0 0 5px #55ff55,inset 0px 0px 10px rgba(0,0,0,0.9);
		margin:2px;
	}

	.photos2 img {
		box-shadow: 0 0 2px #fff, 0 0 5px #ff00de;
		margin:2px;
	}

      /* Custom container */
      .container-narrow {
        margin: 0 auto;
        max-width: 700px;
      }
      .container-narrow > hr {
        margin: 30px 0;
      }

      /* Main marketing message and sign up button */
      .jumbotron {
        margin: 60px 0;
        text-align: center;
      }
      .jumbotron h1 {
        font-size: 72px;
        line-height: 1;
      }
      .jumbotron .btn {
        font-size: 21px;
        padding: 14px 24px;
      }

      /* Supporting marketing content */
      .marketing {
        margin: 60px 0;
      }
      .marketing p + h4 {
        margin-top: 28px;
      }
    </style>
    <link href='css/bootstrap-responsive.css' rel='stylesheet'>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src='js/html5shiv.js'></script>
    <![endif]-->

    <!-- Fav and touch icons -->
	<link rel='shortcut icon' href='img/favicon.png'>
  </head>

  <body onload='facebook_init();'>
    <div class='bg'>


<div id=\"fb-root\"></div>
<script>
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = \"//connect.facebook.net/pt_BR/all.js\";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>


    <div class='container-narrow' style='min-height:100%;'>
";

if($percent_male !==0 && $percent_female !==0){
	$event_start_time = @strtotime("$event_start_time");
	$event_end_time = @strtotime("$event_end_time");
	$event_start_time = @date("d/m á\s G\h",$event_start_time);
	$event_end_time = @date("d/m á\s G\h",$event_end_time);
	echo "
	<div class='row-fluid ' style='text-align:center;'>
		<a target='_blank' href='http://www.facebook.com/$event'><h1>$event_name</h1></a>
		<!--<p>$event_description</p>-->
		<!--<p><i class='icon-map-marker icon-white'></i>$event_location (de: $event_start_time até: $event_end_time )</p>-->
		<p><i class='icon-globe'></i> $event_location <br/> ($event_start_time)</p>
	</div>

	<div class='row-fluid '>
		<div class='span6' style='text-align:center;'>
			<img src='img/male.png' />
			<h1 style='margin-left:30px;'>$percent_male %</h1>
		</div>
		<div class='span6' style='text-align:center;'>
			<img src='img/female.png' />
			<h1 style='margin-left:30px;'>$percent_female %</h1>
		</div>
	</div>

	<div class='row-fluid '>
		<div class='span12' style='text-align:center;'>
			<div style='width:100%; background:#ff88de; padding:0px; height:40px; border-radius:15px; overflow:hidden; -moz-box-shadow: inset 0 0 5px #000000; -webkit-box-shadow: inset 0 0 5px #000000; box-shadow: inset 0 0 5px #000000;'>
				<div style='width:$percent_male%; background:#5F5; height:40px; -moz-box-shadow: inset 0 0 5px #000000; -webkit-box-shadow: inset 0 0 5px #000000; box-shadow: inset 0 0 5px #000000;'></div>
			</div>
		</div>
	</div>
	<div class='row-fluid ' style='margin-top:10px;'>
		<p style='float:left; text-align:center;'><img src='img/male.png' style='width:32px;' /><br/><b>$male</b></p>
		<p style='float:right;  text-align:center;'><img src='img/female.png' style='width:32px;' /><br/><b>$female</b></p>
	</div>";


	if(sizeof($male_photos) > sizeof($female_photos) ){
		$max = sizeof($male_photos)/6;
	}else{
		$max = sizeof($female_photos)/6;
	}

	if($max>500){
		$max = 500;
	}

	shuffle($male_photos);
	shuffle($female_photos);

	echo 
	"<div class='row-fluid ' style='text-align:center; margin-bottom:20px; margin-top:-55px;'>
		<a href='index.php' class='btn btn-large'><i class='icon-home'></i> </a>
		<a href='index.php?event=$event' class='btn btn-large'><i class='icon-refresh'></i></a>
		<a class='btn btn-large' href='http://www.facebook.com/share.php?u=http://mulherometro.com/index.php?event=$event&t=Compartilhar_mulherometro' onclick=\"window.open('http://www.facebook.com/share.php?u=http://mulherometro.com/index.php?event=$event&t=Compartilhar_mulherometro','popup','width=655,height=405,scrollbars=no,resizable=no,toolbar=no,directories=no,location=no,menubar=no,status=no,left=0,top=0'); return false\" target='_blank'> <i class='icon-share'></i></a>
		<!--
		<div class='btn-group'>
			<button onclick='show_select();' id='btn_select' class='btn btn-large'><i class='icon-check'></i></button>
			<button onclick='show_view();' id='btn_view' class='btn  btn-large disabled'><i class='icon-eye-open'></i></button>
		</div>
		-->
	</div>

	<div class='span6 alert alert-warning' id='select_message' style='position:absolute; margin-top:20px; z-index:10; display:none;'>
		<button onclick='document.getElementById(\"select_message\").style.display=\"none\";' type='button' class='close' data-dismiss='alert'>&times;</button>
		<strong>Select Mode:</strong><br/>
		O modo de seleção permite que você selecione as pessoas que tem interesse em conhecer. <br/>
		Assim quando elas se logarem no Mulherômetro elas verão seu interesse e poderão visitar seu perfil.
	</div>
	";


	//div view
	echo "<div class='row-fluid ' id='div_view'>";
		echo "<div class='span12' style='height:auto; max-height:324px; margin-bottom:120px; overflow:auto; overflow-x:hidden;'>";
			for($i=0; $i<$max;$i++){
				echo "<div class='row-fluid '>";
				echo "<div class='span6 photos1' >";
				for($j=$i*6; $j<$i*6+6;$j++){
					echo @$male_photos[$j];
				}
				echo "</div>";
				echo "<div class='span6 photos2' >";
				for($j=$i*6; $j<$i*6+6;$j++){
					echo @$female_photos[$j];
				}
				echo "</div>";
				echo "</div>";
			}
		echo "</div>";
	echo "</div>";

	//div select
	echo "<div class='row-fluid ' id='div_select' style='display:none;'>";
		echo "<div class='span12' style='height:auto; max-height:324px; margin-bottom:120px; overflow:auto; overflow-x:hidden;'>";
			for($i=0; $i<$max;$i++){
				echo "<div class='row-fluid '>";
				echo "<div class='span6'>";
				for($j=$i*6; $j<$i*6+6;$j++){
					$male_photos[$j] = @str_replace("http://facebook.com/","#",$male_photos[$j]);
					$male_photos[$j] = @str_replace("target='_blank'","",$male_photos[$j]);
					$male_photos[$j] = @str_replace("profile","profile_select",$male_photos[$j]);
					$male_photos[$j] = @str_replace("img src","img style='margin:2px; opacity:0.2; border:0px solid white;' onclick='select(this.id);' src",$male_photos[$j]);
					echo @$male_photos[$j];
				}
				echo "</div>";
				echo "<div class='span6'>";
				for($j=$i*6; $j<$i*6+6;$j++){
					$female_photos[$j] = @str_replace("http://facebook.com/","#",$female_photos[$j]);
					$female_photos[$j] = @str_replace("target='_blank'","",$female_photos[$j]);
					$female_photos[$j] = @str_replace("profile","profile_select",$female_photos[$j]);
					$female_photos[$j] = @str_replace("img src","img style='margin:2px; opacity:0.2; border:0px solid white;' onclick='select(this.id);' src",$female_photos[$j]);
					echo @$female_photos[$j];
				}
				echo "</div>";
				echo "</div>";
			}
		echo "</div>";
	echo "</div>";



}else{

	$error=@$_GET['error'];
	$message="";
	if($error=="privacy"){
		$message = "
			<div class='span6 alert alert-danger' id='alert' style='position:absolute; margin-top:90px; z-index:10;'>
				<button onclick='document.getElementById(\"alert\").style.display=\"none\";' type='button' class='close' data-dismiss='alert'>&times;</button>
				<strong>Ops !</strong><br/>
				Infelizmente não temos acesso a eventos restritos. <br/>
				Por favor, consulte um evento público
			</div>";
	}

	echo "
	      <div class='jumbotron' style='margin-bottom:0px; margin-top:30px;'>
		$message
		<h1 style='font-size:46px;'>Mulherômetro</h1>
		<p class='lead'>Oferta e Procura</p>
		<form  class='form-search' action='index.php' method='post'>
		<fieldset>
		<!--
		<input id='input_link' type='text' name='event' style='width:260px; height:40px; text-align:center'  placeholder='Cole o link do evento do facebook'>
		-->

		<input id='input_link' type='text' name='event' style='width:260px; height:40px; text-align:center' onfocusout='search_show();' onfocus='search_show();' onkeyup='search_drop();' placeholder='Cole ou pesquise um evento do facebook'>


		<button id='input_button' onclick='loading();' type='submit' class='btn btn-large'  /><i class='icon-search'></i></button><br/><br/>

		<div class='span4 offset1 alert alert-info' id='search_box' style='margin-top:-10px; background:white; background:rgba(255,255,255,0.9);max-height:170px; overflow:auto; z-index:10; display:none; text-align:left; position:absolute;'>
			<strong>Pesquisando ... </strong>
		</div>

		<a href='#' class='btn btn-facebook' style='color:white;' id='facebook_button' onclick='facebook_login();'>Connect with Facebook</a>

		<p id='loading' style='display:none;'>Aguarde enquanto carregamos os convidados <span id='load_anim'></span></p>
		</fieldset>
		</form>
	      </div>

		<div class='jumbotron' id='my_events'>
		</div>


	";

/*
$lat = "40";
$long = "30";

// using offset gives us a "square" on the map from where to search the events
$offset = 0.4;

$events = 'SELECT pic_big, name, venue, start, location_time, eid FROM event WHERE eid IN (SELECT eid FROM event_member WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = me()) AND start_time > '. $created_time .' OR uid = me()) AND start_time > '. $created_time .' AND venue.longitude < \''. ($long+$offset) .'\' AND venue.latitude < \''. ($lat+$offset) .'\' AND venue.longitude > \''. ($long-$offset) .'\' AND venue.latitude > \''. ($lat-$offset) .'\' ORDER BY start_time ASC '. $limit;
*/


/*
	echo "
	      <div class='jumbotron'>
		<h1>Mulherômetro</h1>
		<p class='lead'>Oferta e Procura</p>
			<div class='alert alert-danger' id='alert'>
				<strong>Ops !</strong><br/>
				Estamos em manutenção. <br/>
				Voltamos em breve!
			</div>
	      </div>
	";
*/


}

echo "
	</div>

	<footer>
		<div class='footer' style='text-align:center;'>

			<div class=\"fb-like\" data-href=\"http://mulherometro.com/\" data-width=\"200\" data-height=\"300\" data-colorscheme=\"light\" data-layout=\"standard\" data-action=\"like\" data-show-faces=\"false\" ></div>
			<p style='font-size:12px;'>&copy; <a style='color:white;' href='http://felippegallo.com.br'>Felippe Gallo 2013</a> - ideia por Gustavo Gorba</p>
		</div>
	</footer>


	<script src='functions.js'></script>

<!--
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-44499803-1', 'felippegallo.com.br');
	  ga('send', 'pageview');
	</script>
-->

    </div class='bg'>
  </body>
</html>
";

?>

