<?php
	include 'db_connect.php';
	if (isset($_REQUEST['month']) && isset($_REQUEST['year']) && isset($_REQUEST['district'])) {
			$commodity1 = $_REQUEST['commodity1'];
      $commodity2 = $_REQUEST['commodity2'];
			$month=$_REQUEST['month'];
			$year=$_REQUEST['year'];
			$district=$_REQUEST['district'];
			if($month<10)
				$month = '0'.$month;
			$sdate = $month.'/01/'.$year;
			$edate = $month.'/31/'.$year;
      $mn = array("Jan","Feb","Mar","Apr","May","June","July","Aug","Sept","Oct","Nov","Dec");
      $month=$mn[(int)$month];
			$header = "['Date', '".ucwords($commodity1)."', '".ucwords($commodity2)."'],\n";
      $query = "SELECT distinct date from $commodity1 where date>='$sdate' and date <= '$edate' union all SELECT distinct date from $commodity2  where date>='$sdate' and date <= '$edate'";
      $date = mysql_query($query);
      $dates = array();
      $c = 0;
      while ($row=mysql_fetch_array($date)) {
        $dates[$c++] = $row['date'];
      }
      $query = "SELECT * from $commodity1 where date>='$sdate' and date <= '$edate'";
      $com1 = mysql_query($query);
      $mcom1 = array();
      while ($row=mysql_fetch_array($com1)) {
        $mcom1[$row['date']] = $row['modal_price'];
      }
      $query = "SELECT * from $commodity2 where date>='$sdate' and date <= '$edate'";
      $com2 = mysql_query($query);
      $mcom2 = array();
      while ($row=mysql_fetch_array($com2)) {
        $mcom2[$row['date']] = $row['modal_price'];
      }
?>
<!doctype html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>
      <?php echo "Comparison between prices of $commodity1 and $commodity2 for month of $month, $year"?>
    </title>
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load('visualization', '1', {packages: ['corechart']});
    </script>
    <script type="text/javascript">
      function drawVisualization() {
        // Create and populate the data table.
        var data = google.visualization.arrayToDataTable([
          <?php 
          	echo $header; 
          	foreach ($dates as $date) {
          		$header = "['".$date."'";
                if(isset($mcom1[$date]))
                {
                  $header.=", ".$mcom1[$date];
                }
                else{
                  $header.=", 0"; 
                }
                if(isset($mcom2[$date]))
                {
                  $header.=", ".$mcom2[$date];
                }
                else{
                  $header.=", 0"; 
                }
              $header .= "],\n";
          		echo $header;
          	}
          ?>
        ]);
      
        // Create and draw the visualization.
        new google.visualization.ColumnChart(document.getElementById('visualization')).
            draw(data,
                 {title:'<?php echo "Variance in price of $commodity1 for month of $month, $year"?>',
                  width:1100, height:400,
                  hAxis: {title: "Date"}}
            );
      }
      

      google.setOnLoadCallback(drawVisualization);
    </script>
  </head>
  <body style="font-family: Arial;border: 0 none;">
    <div id="visualization" style="width: 600px; height: 400px;"></div>
  </body>
</html>
<?php

	}
	else
		echo "Sorry Info was'nt complete"
?>