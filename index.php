<?php
	include 'db_connect.php';
	$commodity1 = $_REQUEST['commodity1'];
	$commodity2 = $_REQUEST['commodity2'];
	$query = "select distinct(state) from $commodity1 union all select distinct(state) from $commodity2";
	$res = mysql_query($query);
?>
<!doctype html>
<html>
<head>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
</head>
<body>

	<form action="comp.php" method="POST">
		<input type="hidden" name="commodity1" value="rice">
		<input type="hidden" name="commodity2" value="wheat">
	Select State: <select id="state" name="state">
	<option>Select State</option>
	<?php
				
		while ($key = mysql_fetch_array($res)){
			echo'<option value="'.$key['state'].'">'.$key['state'].'</option>';
		}
	?>
	</select><br>
	<?php
	$month = array("Jan","Feb","Mar","Apr","May","June","July","Aug","Sept","Oct","Nov","Dec");
	?>

	Select District: 
	<select id="district" name="district" >
	</select>
	<br>

	Select Month and Year: 
	<select name="month">
		<?php
				$b=1;
				foreach ($month as $i) {
					echo "<option value=".$b.">".$i."</option>";
					$b++;
				}
		?>
	</select>
	<select name="year">
		<?php
			for ($i=2000; $i <date("Y"); $i++) { 
		echo "<option value=".$i.">".$i."</option>";
			}
			
		?>
	</select>
	<input type="submit" name="submit">Submit</input>
</form>
	
</body>
<script type="text/javascript">
	$('#state').change(function (){
		$url = 'get_district.php?state='+$(this).val()+'&commodity=<?php echo $commodity1; ?>';
		$.get($url, function(res){
			$('#district').html(res);
		});
	});
</script>
</html>