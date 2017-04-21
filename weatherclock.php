<?php
include 'includes/header.php';
include 'includes/nav.php';
$device = 'weather-clock';
if (isset($_GET['device'])) {
    $device = $_GET['device'];
}
    ?>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
      
        // temp chart data and options
        var tempData = google.visualization.arrayToDataTable([
          ['Time', 'Inside Temp [*F]', 'Outside Temp [*F]'],
            <?php
            // get recent readings from device 
            $sql = "SELECT * FROM devices WHERE `device` = '" . $device ."' AND `time` >= CURDATE() - INTERVAL 30 DAY ORDER BY `entry` ASC";
            $myResults = array();
            if ($result = $db->query($sql)) {
                while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                    $myResults[] = $row;
                }
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
            $sql = "SELECT * FROM devices WHERE `device` = '" . $device ."' AND `time` >= CURDATE() - INTERVAL 30 DAY ORDER BY `entry` ASC";
            $myResults = array();
            if ($result = $db->query($sql)) {
                while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                    $myResults[] = $row;
                }
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
        <div class="row">
            <div class="col-md-3">
                <br/>
                Start <div class='input-group date' id='datestart' style="max-width:200px;">
                    <input type='text' class="form-control" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
            <div class="col-md-3">
                <br/>
                End <div class='input-group date' id='dateend' style="max-width:200px;">
                    <input type='text' class="form-control" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
            <div class="col-md-6">&nbsp;</div>
        </div>
        <script type="text/javascript">
            $(function () {
                $('#datestart').datetimepicker();
                $('#dateend').datetimepicker();
            });
        </script>      
        <div id="temp_chart" style="width: 900px; height: 500px"></div>
        <div id="humidity_chart" style="width: 900px; height: 500px"></div>
    </div>
<?php
include 'includes/footer.php';
?>