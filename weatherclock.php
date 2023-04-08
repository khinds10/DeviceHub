<?php
include 'includes/header.php';
include 'includes/nav.php';
$device = 'weather-clock';
if (isset($_GET['device'])) $device = $_GET['device'];

// if date range set, then apply it to the result set
$dateRange = "`time` >= CURDATE() - INTERVAL 1 DAY";
if (isset($_POST['dateStart']) && isset($_POST['dateEnd'])) $dateRange = "`time` >= '" . date('Y-m-d G:i:s' , strtotime($_POST['dateStart'])) . "' AND `time` <= '" . date('Y-m-d G:i:s' , strtotime($_POST['dateEnd'])) ."'";
?>
<style>
    #temp_chart {
        width: 900px; 
        height: 500px;
    }

    #humidity_chart {
        width: 900px; 
        height: 500px;
    }

    h4 {
        display:inline;
    }

    @media (max-width: 900px) {
        #temp_chart {
            width: 450px; 
            height: 250px;
        }
        
        #humidity_chart {
            width: 450px; 
            height: 250px;
        }    
    }
</style>
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
  
    // temp chart data and options
    var tempData = google.visualization.arrayToDataTable([
      ['Time', 'Inside Temp [*F]', 'Outside Temp [*F]'],
        <?php
        // get recent readings from device 
        $sql = "SELECT * FROM devices WHERE `device` = '" . $device ."' AND $dateRange ORDER BY `entry` ASC";
        $myResults = array();
        if ($result = $db->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)) $myResults[] = $row;
        }
        foreach ($myResults as $myResult) print "['".date('M. j',strtotime($myResult['time']))."',".$myResult['value1'].",".$myResult['value3']."],";
        ?>
    ]);

    var tempOptions = {
      title: 'Weather Clock Temperature [*F]',
      curveType: 'function',
      legend: { position: 'bottom' }
    };

    // draw temp chart
    var tempChart = new google.visualization.LineChart(document.getElementById('temp_chart'));
    tempChart.draw(tempData, tempOptions);

    // humdity chart data and options
    var humidityData = google.visualization.arrayToDataTable([
      ['Time', 'Inside Humidity [%]', 'Outside Humidity [%]'],
        <?php
        // get recent readings from device 
        $sql = "SELECT * FROM devices WHERE `device` = '" . $device ."' AND $dateRange ORDER BY `entry` ASC";
        $myResults = array();
        if ($result = $db->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)) $myResults[] = $row;
        }
        foreach ($myResults as $myResult) print "['".date('M. j',strtotime($myResult['time']))."',".$myResult['value2'].",".$myResult['value4']."],";
        ?>
    ]);

    var humidityOptions = {
      title: 'Weather Clock Humidity [%]',
      curveType: 'function',
      legend: { position: 'bottom' }
    };

    // draw humdity chart
    var humdityChart = new google.visualization.LineChart(document.getElementById('humidity_chart'));
    humdityChart.draw(humidityData, humidityOptions);
  }
</script>
<div class="container">
        <h1><?=ucwords(preg_replace('/\-/',' ',$device))?></h1>
        
        <?php
        // get most recent readings from device 
        $sql = "SELECT * FROM devices WHERE `device` = '" . $device ."' ORDER BY `entry` DESC LIMIT 1";
        $myResults = array();
        if ($result = $db->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)) $myResults[] = $row;
        }
        foreach ($myResults as $myResult) {
        
            // get the device last updated time with the timezone accounted for
            $deviceGTMTime = new DateTime($myResult['time'], new DateTimeZone('GMT'));
            $userTimezone = new DateTimeZone('America/New_York');
            $timestamp = strtotime($myResult['time']) + (int) $userTimezone->getOffset($deviceGTMTime);
            $lastUpdate = date("M. j, g:i a", $timestamp);
            
            // get temp colors and display current device stats
            $tempColor = file_get_contents(TEMPCOLORAPI . '/?temperature=' . $myResult['value1']);
            $humidityColor = file_get_contents(TEMPCOLORAPI . '/humidity?humidity=' . $myResult['value2']);
            print "<h4> <span style='color:$tempColor'>Inside: " . $myResult['value1']. " *F </span> / </h4>";
            print "<h4> <span style='color:$humidityColor'>Inside: " . $myResult['value2']. " %</span></h4>";
            print "<h6> <span>$lastUpdate</span></h6>";
        }   
        ?>
    <div class="row">
        <form method="post" name="tempDataDateForm" id="tempDataDateForm">
            <div class="col-md-3">
                <br/> Start <div class='input-group date' id='dateStart' style="max-width:200px;">
                <input type='text' class="form-control" name="dateStart" value="<?=isset($_POST['dateStart']) ? $_POST['dateStart'] : ''?>"/>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
            </div>
            <div class="col-md-3">
                    <br/> End <div class='input-group date' id='dateEnd' style="max-width:200px;">
                    <input type='text' class="form-control" name="dateEnd" value="<?=isset($_POST['dateEnd']) ? $_POST['dateEnd'] : ''?>"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <br/><br/>
                <button id="submitDataForm" type="button" class="btn btn-default" aria-label="Left Align" type="submit">
                  <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Get Data
                </button>
            </div>
        </form>
    </div><br/>
    <script type="text/javascript">
        $(function () {
            $('#dateStart').datetimepicker();
            $('#dateEnd').datetimepicker();
            $( "#submitDataForm" ).click(function() {
              $( "#tempDataDateForm" ).submit();
            });
        });
    </script>      
    <div id="temp_chart" style="width: 900px; height: 500px"></div>
    <div id="humidity_chart" style="width: 900px; height: 500px"></div>
</div>
<?php
include 'includes/footer.php';
?>
