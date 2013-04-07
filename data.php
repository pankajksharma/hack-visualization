<?php
	include 'db_connect.php';
	if (isset($_REQUEST['month']) && isset($_REQUEST['year']) && isset($_REQUEST['district'])) {
			$commodity = $_REQUEST['commodity'];
			$month=$_REQUEST['month'];
			$year=$_REQUEST['year'];
			$district=$_REQUEST['district'];
			if($month<10)
				$month = '0'.$month;
			$sdate = $month.'/01/'.$year;
			$edate = $month.'/31/'.$year;
          	$mn = array("Jan","Feb","Mar","Apr","May","June","July","Aug","Sept","Oct","Nov","Dec");
          	$month=$mn[(int)$month];
			$query="SELECT distinct `market` from $commodity where district='$district' and date >= '$sdate' and date <= '$edate'";
			#echo $query;
			$res = mysql_query($query);
			$modal=array();
			while($row = mysql_fetch_array($res)){
				$market = $row['market'];
				$modal[$market] = array();
				$q = "SELECT * from $commodity where market='$market' and date >= '$sdate' and date <= '$edate'";
				$r = mysql_query($q);
				while ($s = mysql_fetch_array($r)) {
					$modal[$market][$s['date']] = $s['modal_price'];
				}
			}
			$query = "SELECT distinct `date` from $commodity where district='$district' and date >= '$sdate' and date <= '$edate'";
			$res = mysql_query($query);
			$dates = array();
			$c=0;
			while ($row = mysql_fetch_array($res)) {
				$dates[$c] = $row['date'];
				$c++;

			}
				$markets = array_keys($modal);
				$header = "['Date',";
				foreach ($markets as $key) {
					$header .= " '".$key."'";
				}
				$header .= "],\n";

?>
<!doctype html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>
      <?php echo "Variance in price of $commodity for month of $month, $year"?>
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
          		#echo "$date";
          		$header = "['".$date."'";
          		foreach ($markets as $market) {
          			if(isset($modal[$market][$date]))
          			{
          				$header.=", ".$modal[$market][$date];
          			}
          			else{
          				$header.=", 0";	
          			}
          		}
          		$header .= "],\n";
          		echo $header;
          	}
          ?>
        ]);
      
        // Create and draw the visualization.
        new google.visualization.ColumnChart(document.getElementById('visualization')).
            draw(data,
                 {title:'<?php echo "Variance in price of $commodity for month of $month, $year"?>',
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