<?php
include 'includes/header.php';
include 'includes/nav.php';
?>
    <div class="container">
        <?php
        $devices = array('weather-clock', 'magic-mirror', 'weather-clock-white', 'weather-clock-duluth', 'trip-computer');
        foreach ($devices as $device) { 
            $icon = "glyphicon glyphicon-time";
            if ($device == 'weather-clock-duluth') $icon = "glyphicon glyphicon-screenshot";
            if ($device == 'trip-computer') $icon = "glyphicon glyphicon-road";
        ?>
                <h2> <i class='<?=$icon?>'></i> <?=ucwords(preg_replace('/\-/',' ',$device))?></h2>

                <?php
                // get most recent readings from device 
                $sql = "SELECT * FROM devices WHERE `device` = '" . $device ."' ORDER BY `entry` DESC LIMIT 1";

                $myResults = array();
                if ($result = $db->query($sql)) while($row = $result->fetch_array(MYSQLI_ASSOC)) $myResults[] = $row;
                foreach ($myResults as $myResult) print "<h4 style='color:darkblue; padding-left:25px;'>Inside: " . $myResult['value1']. " *F / Inside: " . $myResult['value2']. " %</h4>";
                ?>
        <?php
        }
        ?>
        <br/><br/>
    </div>
<?php
include 'includes/footer.php';
?>

