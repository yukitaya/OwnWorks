<?php
$con=mysql_connect("localhost","root","password") or die("Failed to connect with database!!!!");
mysql_select_db("counter", $con); 
$sth = mysql_query("SELECT * FROM os ORDER BY date DESC");

$rows = array();
$flag = true;
$table = array();
$table['cols'] = array(
    array('label' => 'date', 'type' => 'string'),
    array('label' => 'os', 'type' => 'string'),
    array('label' => 'count', 'type' => 'number')
);

$rows = array();
while($r = mysql_fetch_assoc($sth)) {
    $temp = array();
    // the following line will be used to slice the Pie chart
    $temp[] = array('v' => (string) $r['date']); 

    // Values of each slice
    $temp[] = array('v' => (string) $r['os']);
    $temp[] = array('v' => (int) $r['count']);
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

    google.load('visualization', '1.1', {'packages':['table']});
    google.setOnLoadCallback(drawTable);

    function drawTable() {
      var data = new google.visualization.DataTable(<?=$jsonTable?>);
      var table = new google.visualization.Table(document.getElementById('table_div'));
      table.draw(data, {showRowNumber: true, width: '100%', height: '100%'});
    }
    </script>
  </head>

  <body>
    <!--this is the div that will hold the pie chart-->
    <div id="table_div"></div>
  </body>
</html>
