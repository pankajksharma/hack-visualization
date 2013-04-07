<?php
	include 'db_connect.php';
	$state = $_REQUEST['state'];
	$commodity = $_REQUEST['commodity'];
	$query = "select distinct(district) from $commodity where state='$state'";
	#echo "get_district.php?state=$state&commodity=$commodity";
	#echo $query;
	$res = mysql_query($query);
	while ($row = mysql_fetch_array($res)) {
		echo '<option value="'.$row['district'].'">'.$row['district'].'</option>';
	}
?>