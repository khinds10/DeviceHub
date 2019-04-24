<?php
require_once("settings.php");
$db = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Device Hub</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="/img/favicon.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://bootswatch.com/3/cyborg/bootstrap.min.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>   
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="/js/skycons.js"></script>
    <script>
	    // based on current conditions set the "skycon" animated canvas
        function setWeatherIcon(element, icon) {
        	var icons = new Skycons({"color": "white"}),
            list  = ["clear-day", "clear-night", "partly-cloudy-day","partly-cloudy-night", "cloudy", "rain", "sleet", "snow", "wind","fog"],i;
          	for(i = list.length; i--; ) icons.set(element, icon);
          	icons.play();
        };
    </script>
  </head>
  <body>
