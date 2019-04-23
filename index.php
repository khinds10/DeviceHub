<?php
include 'includes/header.php';
include 'includes/nav.php';

// get current weather for additional info
date_default_timezone_set('EST');
$currentWeather = json_decode(file_get_contents(WEATHERAPI));
$sunRiseTime = '';
if (isset($currentWeather->daily->data[0]->sunriseTime)) $sunRiseTime = date("M. jS, <br/>g:i a", $currentWeather->daily->data[0]->sunriseTime);
$sunSetTime = '';
if (isset($currentWeather->daily->data[0]->sunsetTime)) $sunSetTime = date("M. jS, <br/>g:i a", $currentWeather->daily->data[0]->sunsetTime);

// current weather conditions with color coding
$apparentTemperature = round($currentWeather->currently->apparentTemperature);
$apparentTemperatureColor = file_get_contents(TEMPCOLORAPI . '/?temperature=' . $apparentTemperature);

$temperatureMax = round($currentWeather->daily->data[0]->temperatureMax);
$temperatureMaxColor = file_get_contents(TEMPCOLORAPI . '/?temperature=' . $temperatureMax);

$temperatureMin = round($currentWeather->daily->data[0]->temperatureMin);
$temperatureMinColor = file_get_contents(TEMPCOLORAPI . '/?temperature=' . $temperatureMin);

// current weather additional info
$humidity = $currentWeather->currently->humidity * 100;
$humidityOutsideColor = file_get_contents(TEMPCOLORAPI . '/humidity?humidity=' . $humidity);

$summary = $currentWeather->currently->summary;
$windSpeed = round($currentWeather->currently->windSpeed);
$cloudCover = $currentWeather->currently->cloudCover * 100;
$hourly = $currentWeather->hourly->summary;
?>
<style>
    a:hover{text-decoration: none;}
    a:active{text-decoration: none;}
    a:visited{text-decoration: none;}
    
    #current-forecast {
        font-size: 20px;
        color:white;
    }
    
    .border-image {
        max-width: 100%;
        background-color: black; 
        -moz-background-clip: padding; 
        -webkit-background-clip: padding; 
        background-clip: padding-box;  
        border: 20px solid rgba(25,25,25,0.3); 
        -webkit-border-radius: 25px; 
        -moz-border-radius: 25px; 
        border-radius: 25px;
    }
</style>
    <div class="container">
        <h3>Current Devices</h3><hr/>
        <?php
        $devices = array(
                'weather-clock-attic' => 'Attic'
                , 'weather-clock-red' => 'Sam'
                , 'weather-clock-yellow' => 'Guest'
                , 'weather-clock-white' => 'Bed'
                , 'weather-clock' => 'Living'
                , 'weather-clock-small-white' => 'Kitchen'
                , 'weather-clock-gray' => 'Basement'
                , 'weather-clock-nissan' => 'Nissan'
                , 'weather-clock-duluth' => 'Duluth'
            );
            
        foreach ($devices as $device => $deviceName) { 
            $icon = "glyphicon glyphicon-time";
            if ($device == 'weather-clock-duluth') $icon = "glyphicon glyphicon-screenshot";
            if ($device == 'trip-computer') $icon = "glyphicon glyphicon-road";
        ?>
                <a href="weatherclock.php?device=<?=$device?>"><h4><i class='<?=$icon?>'></i> <?=$deviceName?></h4></a>
                <?php
                // get most recent readings from device 
                $sql = "SELECT * FROM devices WHERE `device` = '" . $device ."' ORDER BY `entry` DESC LIMIT 1";

                $myResults = array();
                if ($result = $db->query($sql)) while($row = $result->fetch_array(MYSQLI_ASSOC)) $myResults[] = $row;
                foreach ($myResults as $myResult) {
                    $tempColor = file_get_contents(TEMPCOLORAPI . '/?temperature=' . $myResult['value1']);
                    $humidityColor = file_get_contents(TEMPCOLORAPI . '/humidity?humidity=' . $myResult['value2']);
                    print "<h5 style='padding-left:25px;'><span style='color:$tempColor;'>Inside: " . $myResult['value1']. " *F </span> / <span style='color:$humidityColor;'>Inside: " . $myResult['value2']. " %</span></h5>";
                    if ($myResult['device'] == 'weather-clock-nissan') {
                        $carConditions = "<h5 style='padding-left:25px;'><span style='color:$tempColor;'>" . $myResult['value1']. " *F </span> / <span style='color:$humidityColor;'> " . $myResult['value2']. " %</span></h5>";;
                    }
                }
                ?>
        <?php
        }
        ?>
        <br/><br/>
        <h3>Home Heatmap</h3><hr/>
        <img class="border-image" src="<?=CLOCKHEATMAP?>/img/house.jpg?<?=rand()?>"/>
        <hr/>
        <div style="position: relative;">
            <img class="border-image" src="/img/car.png?<?=rand()?>" style="max-width: 350px; margin-left: 175px;"/>
            <div style="position: absolute; top: 50px; left:300px;">
                <?=$carConditions?>
            </div>
        </div>
        <br/><br/>
        <h3>Current Conditions</h3><hr/>

        <div id="current-forecast">
            <span style="color:<?=$apparentTemperatureColor?>"> <?=$apparentTemperature?>*F </span> / <span style="color:<?=$humidityOutsideColor?>"><?=$humidity?>%</span> [<?=$summary?>]<br/>
            Wind: <?=$windSpeed?> mph / Clouds: <?=$cloudCover?>%<br/>
            <span style="color:<?=$temperatureMaxColor?>">High: <?=$temperatureMax?>*F </span> / <span style="color:<?=$temperatureMinColor?>">Low: <?=$temperatureMin?>*F </span><br/><br/>
            Next Hour: <?=$hourly?><br/>
        </div>
        <br/><br/>
        <h3>Webcam Views</h3><hr/>
        <div class="container">
          <div class="row">
            <div class="col-sm-3">
              <h3 style="color:yellow;">Sunrise</h3>
              <a href="<?=MYWEBCAMURL?>/mostColorful.jpg?<?=rand()?>"><img class="border-image" src="<?=MYWEBCAMURL?>/mostColorful.jpg?<?=rand()?>"/></a>
              <?=$sunRiseTime;?>
            </div>    
            <div class="col-sm-3">
              <h3>Front</h3>
              <a href="<?=MYWEBCAMURL?>webcam.jpg?<?=rand()?>"><img class="border-image" src="<?=MYWEBCAMURL?>webcam.jpg?<?=rand()?>"/></a>
            </div>
            <div class="col-sm-3">
              <h3>Back</h3>
              <a href="<?=MYWEBCAMURL?>webcam-rear.jpg?<?=rand()?>"><img class="border-image" src="<?=MYWEBCAMURL?>webcam-rear.jpg?<?=rand()?>"/></a>
            </div>
            <div class="col-sm-3">
              <h3 style="color:yellow;">Sunset</h3>
              <a href="<?=MYWEBCAMURL?>/mostColorful-sunset.jpg?<?=rand()?>"><img class="border-image" src="<?=MYWEBCAMURL?>/mostColorful-sunset.jpg?<?=rand()?>"/></a>
              <?=$sunSetTime;?>
            </div>
          </div>
        </div>
        <br/><br/>
    </div>    
<?php
include 'includes/footer.php';
?>
