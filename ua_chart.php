<?php
$con=mysql_connect("localhost","root","password") or die("Failed to connect with database!!!!");
mysql_select_db("counter", $con); 
$sth = mysql_query("SELECT ua,SUM(count) FROM ua WHERE date >= DATE_ADD(NOW(),INTERVAL -1 MONTH) GROUP BY ua ORDER BY SUM(count) DESC");

$rows = array();
$flag = true;
$table = array();
$table['cols'] = array(
    array('label' => 'ua', 'type' => 'string'),
    array('label' => 'SUM(count)', 'type' => 'number')
);

$rows = array();
while($r = mysql_fetch_assoc($sth)) {
    $temp = array();
    // the following line will be used to slice the Pie chart
    $temp[] = array('v' => (string) $r['ua']); 

    // Values of each slice
    $temp[] = array('v' => (int) $r['SUM(count)']); 
    $rows[] = array('c' => $temp);
}

$table['rows'] = $rows;
$jsonTable = json_encode($table);
//echo $jsonTable;
?>

<html>
  <head>
    <!--Load the Ajax API-->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script type="text/javascript">

    google.load('visualization', '1', {'packages':['corechart']});
    google.setOnLoadCallback(drawChart);

    function drawChart() {
      var data = new google.visualization.DataTable(<?=$jsonTable?>);
      var options = {
          title: 'UserAgent share (Last a month)',
          is3D: 'false',
          width: 1024,
          height: 800
       };
      var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
    </script>
  </head>

  <body>
    <!--this is the div that will hold the pie chart-->
    <div id="chart_div"></div>
  </body>
</html>
