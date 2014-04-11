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
//$token="CAACkD2RgEIUBABZAxh42M9B0fYXx7zmNZCtk6XE0uG5AgXD25HTaGa0ov4lzS7PJencwy8fMyzZCzzkZC9IBWy4ZAOYRRN8kZAZAi2e0ZBixT20AeYKRCFPUBteuYMw1om85tJAaxWEV9le1I7wUTM4EtkWjZCJgf9wSelDirW67OMy9F6dpRA5qNPxCVUn6WUTkZD"; 
?>

<!DOCTYPE html>
<html>
	<head>
		<meta name="description" content="Oferta e demanda">
		<meta name="keywords" content="mulherometro, festas, eventos, medidor">
		<meta name="author" content="ResponsiveWebInc , Felippe Gallo">
		<meta charset="UTF-8">
		<!-- Title here -->
		<title>Mulherômetro</title>
		<!-- Description, Keywords and Author -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Lobster+Two' rel='stylesheet' type='text/css'>
		<!-- Styles -->
		<!-- Bootstrap CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<!-- Font awesome CSS -->
		<link href="css/font-awesome.min.css" rel="stylesheet">	
		<!-- Social CSS -->
		<link href="css/social.css" rel="stylesheet">	
		<!-- Custom CSS -->
		<link href="css/style.css" rel="stylesheet">
		<!-- Favicon -->
		<link rel="shortcut icon" href="#">
		<?  echo "<script>window.fbtoken='$token'; </script>"; ?>
		<script src="js/functions.js"></script>

	</head>

	<body onload="facebook_init();">
	<div id="fb-root"></div>
	<script>
	(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/pt_BR/all.js";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>

	<!-- HEADER -->
	<div class="row-fluid offset_header" style="text-align:center;" id="header">
		<div class="span12">
			<h1>Mulherômetro</h1>
			<p>Oferta e demanda</p>
		</div>
	</div>

	<!-- SEARCH INPUT -->
	<div class="row-fluid" style="text-align:center; " id="search_input">
		<div class="span12" >
			<input id='input_link' type='text' name='event' style='width:260px; height:40px; text-align:center; display:;' onfocusout='search_show();' onfocus='search_show();' onkeyup='search_drop();' placeholder='Pesquise aqui'>
		</div>
	</div>

	<!-- SEARCH BOX -->
	<div class="row-fluid"  style="display:none;" id="search_box">
		<div class="span12" style="text-align:center;" ><p>Pesquisando ... </p></div>
	</div>

	<!-- MY EVENTS -->
	<div class="row-fluid" style="text-align:center; display:none;"  id="my_events">
	</div>


	<!-- FACEBOOK BUTTON -->
	<div class="row-fluid" style="text-align:center; " id="facebook_button">
		<div class="span12">
				<a href='#'  class='btn btn-large btn-facebook'  onclick='facebook_login();'>Connect with Facebook</a>
		</div>
	</div>

	<!-- EVENT DETAIL -->
	<div class="row-fluid" style="text-align:center;" id="event_stats">
	</div>


	<!-- EVENT DETAIL -->
	<div class="row-fluid" style="text-align:center;" id="event_detail">
		<div class="span12">
		<div class="span6 photos1" id="male_column"></div>
		<div class="span6 photos2" id="female_column"></div>
		<div style="display:none;" id="male_count">0</div>
		<div style="display:none;" id="female_count">0</div>
		<div style="display:none;" id="total_count">0</div>
		<div style="display:none;" id="counter_i">0</div>
	</div>
	</div>


	<!-- FOOTER -->
	<footer>
		<div class="footer" style="text-align:center; width:100%; margin-top:30px;">
			<div class="fb-like" data-href="http://mulherometro.com/" data-width="200" data-height="300" data-colorscheme="light" data-layout="standard" data-action="like" data-show-faces="false" ></div>
			<p style='font-size:12px;'>&copy; <a style='color:white;' href='http://felippegallo.com.br'>Felippe Gallo 2013</a> - ideia por Gustavo Gorba</p>
		</div>
	</footer>


	</div>
	</body>	
</html>
