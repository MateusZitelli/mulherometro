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
		<meta name="author" content="Felippe Gallo">
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
		<!-- Custom CSS -->
		<link href="css/style_plugin.css" rel="stylesheet">
		<!-- Favicon -->
		<link rel="shortcut icon" href="#">
		<? echo "<script>window.fbtoken='$token'</script>"; ?>
		<script src="js/functions_plugin.js"></script>

	</head>

	<body onload="facebook_init();" >
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
			<br/>
			<img src="img/logo.png" /><br/><br/><br/><br/>
			<a href="http://mulherometro.com" style="text-decoration:none;" target="_blank" ><h1>Mulherômetro</h1></a><br/>
			<!--<a href="http://ranchodoserjao.mulherometro.com/?event=1454054361489648"><h3 style="display:inline; margin-right:80px;">Léo Rodriguez</h3></a>-->
		</div>
	</div>

<div class="row-fluid">
<div class="span6 offset3">

	<!-- EVENT HEADER -->
	<div class="row-fluid offset_header" style="text-align:center;" id="header_event">
			<p>Carregando ...</p>
	</div>

	<!-- EVENT DETAIL -->
	<div class="row-fluid" style="text-align:center;" id="event_stats">
	</div>


	<!-- EVENT DETAIL -->
	<div class="row-fluid" style="text-align:center;" id="event_detail">
		<div class="span12" style="height:217px; overflow-y:auto;">
			<div class="span6 photos1" id="male_column" style="text-align:left;"></div>
			<div class="span6 photos2" id="female_column" style="text-align:left;"></div>
			<div style="display:none;" id="male_count">0</div>
			<div style="display:none;" id="female_count">0</div>
			<div style="display:none;" id="total_count">0</div>
			<div style="display:none;" id="counter_i">0</div>
		</div>
	</div>
</div>
</div>


	<!-- FOOTER -->
	<footer>
		<div class="footer" style="text-align:center; width:100%; margin-top:30px;">
			<p style='font-size:12px;'>&copy; <a style='color:white;' target="_blank" href='http://mulherometro.com'>Mulherômetro.com</a></p>
		</div>
	</footer>

	</div>
	</body>	
</html>
