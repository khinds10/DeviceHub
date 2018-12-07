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
                'weather-clock-red' => 'Sam'
                , 'weather-clock-yellow' => 'Guest'
                , 'weather-clock-white' => 'Bed'
                , 'weather-clock' => 'Living'
                , 'weather-clock-small-white' => 'Kitchen'
                , 'weather-clock-gray' => 'Basement'
            );
            
        foreach ($devices as $device => $deviceName) { 
            $icon = "glyphicon glyphicon-time";
            if ($device == 'weather-clock-duluth') $icon = "glyphicon glyphicon-screenshot";
            if ($device == 'trip-computer') $icon = "glyphicon glyphicon-road";
        ?>
                <a href="weatherclock.php?device=<?=$device?>"><h3><i class='<?=$icon?>'></i> <?=$deviceName?></h3></a>
                <?php
                // get most recent readings from device 
                $sql = "SELECT * FROM devices WHERE `device` = '" . $device ."' ORDER BY `entry` DESC LIMIT 1";

                $myResults = array();
                if ($result = $db->query($sql)) while($row = $result->fetch_array(MYSQLI_ASSOC)) $myResults[] = $row;
                foreach ($myResults as $myResult) {
                    $tempColor = file_get_contents(TEMPCOLORAPI . '/?temperature=' . $myResult['value1']);
                    $humidityColor = file_get_contents(TEMPCOLORAPI . '/humidity?humidity=' . $myResult['value2']);
                    print "<h4 style='padding-left:25px;'><span style='color:$tempColor;'>Inside: " . $myResult['value1']. " *F </span> / <span style='color:$humidityColor;'>Inside: " . $myResult['value2']. " %</span></h4>";
                }
                ?>
        <?php
        }
        ?>
        <br/><br/>
        <h3>Home Heatmap</h3><hr/>
        <img class="border-image" src="<?=CLOCKHEATMAP?>/img/house.jpg?<?=rand()?>"/>

        <br/><br/>
        <h3>Current Conditions</h3><hr/>

        <div id="current-forecast">
            <span style="color:<?=$apparentTemperatureColor?>"> <?=$apparentTemperature?>*F </span> / <span style="color:<?=$humidityOutsideColor?>"><?=$humidity?>%</span> [<?=$summary?>]<br/>
            Wind: <?=$windSpeed?> mph / Clouds: <?=$cloudCover?>%<br/>
            <span style="color:<?=$temperatureMaxColor?>">High: <?=$temperatureMax?>*F </span> / <span style="color:<?=$temperatureMinColor?>">Low: <?=$temperatureMin?>*F </span><br/><br/>
            Next Hour: <?=$hourly?><br/>
        </div>

        <h3>Front</h3>
        <img class="border-image" src="<?=MYWEBCAMURL?>webcam.jpg?<?=rand()?>"/>
        <br/><br/>
        <h3>Back</h3>
        <img class="border-image" src="<?=MYWEBCAMURL?>webcam-rear.jpg?<?=rand()?>"/>
        <br/><br/>
                                
        <h3 style="color:yellow;">Sunrise - <?=$sunRiseTime;?></h3><hr/>
        <img class="border-image" src="<?=MYWEBCAMURL?>/mostColorful.jpg?<?=rand()?>"/>

        <h3 style="color:yellow;">Sunset - <?=$sunSetTime;?></h3><hr/>
        <img class="border-image" src="<?=MYWEBCAMURL?>/mostColorful-sunset.jpg?<?=rand()?>"/>

    </div>    
<?php
include 'includes/footer.php';
?>
