<?php
include 'includes/header.php';
include 'includes/nav.php';
?>
<style>
    a:hover{text-decoration: none;}
    a:active{text-decoration: none;}
    a:visited{text-decoration: none;}
    
    #home-heatmap {
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
                , 'weather-clock-duluth' => 'Duluth'
            );
            //trip-computer
            
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
                    print "<h4 style='padding-left:25px;'><span style='color:$tempColor;'>Inside: " . $myResult['value1']. " *F </span> / Inside: " . $myResult['value2']. " %</h4>";
                }
                ?>
        <?php
        }
        ?>
        <br/><br/>
        <h3>Home Heatmap</h3><hr/>
        <img id="home-heatmap" src="<?=CLOCKHEATMAP?>/img/house.jpg"/>
    </div>
<?php
include 'includes/footer.php';
?>

