<?php
include 'includes/header.php';
include 'includes/nav.php';
?>
    <div class="container">
        <?php
        $devices = array('magic-mirror', 'weather-clock', 'weather-clock-white', 'weather-clock-duluth', 'trip-computer');
        foreach ($devices as $device) { 
        ?>
        <h1><?=ucwords(preg_replace('/\-/',' ',$device))?></h1>

                <?php
                // get most recent readings from device 
                $sql = "SELECT * FROM devices WHERE `device` = '" . $device ."' ORDER BY `entry` DESC LIMIT 1";

                $myResults = array();
                if ($result = $db->query($sql)) {
                    while($row = $result->fetch_array(MYSQLI_ASSOC)) $myResults[] = $row;
                }
                foreach ($myResults as $myResult) print "<h2> Inside: " . $myResult['value1']. " *F / Inside: " . $myResult['value2']. " %</h2>";
                ?>
        <?php
        }
        ?>
        <br/><br/>
    </div>
<?php
include 'includes/footer.php';
?>
