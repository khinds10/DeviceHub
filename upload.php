<?php
    // get the current DB settings and request type
    require_once("settings.php");
    $db = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB);
    $device = $_GET['device'];
    $action = $_GET['action'];
    
    // if it's 'sync' or 'upload' do the appropriate action
    if ($action == 'sync') {
        $sql = "SELECT time FROM devices WHERE device = '$device' ORDER BY time desc limit 1";

        // we just say yesterday if there's no data for this device yet
        $recentSyncResults = new stdClass();
        $recentSyncResults->time = date("Y-m-d H:i:s", strtotime('-1 day'));
        if ($result = $db->query($sql)) while($row = $result->fetch_array(MYSQLI_ASSOC)) $recentSyncResults = $row;
        print json_encode($recentSyncResults);

    } else if ($action == 'upload') {
        // upload here via HTTP POST
        $data = json_decode($_POST['data']);  
        foreach ($data as $row) {
            $insertSQL = "INSERT INTO `devices` (`time`, `device`, `value1`, `value2`, `value3`, `value4`, `value5`, `value6`, `value7`, `value8`, `value9`, `value10`)
                VALUES
            ('".$row[0]."','".$device."','".$row[1]."','".$row[2]."','".$row[3]."','".$row[4]."','".$row[5]."','".$row[6]."','".$row[7]."','".$row[8]."','".$row[9]."','".$row[10]."')";            
            print "\n\n".$insertSQL."\n";
            $db->query($insertSQL);
        }
    }
