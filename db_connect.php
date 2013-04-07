<?php 
	try {
		mysql_connect("localhost", "root", "root");
	}catch (Exception $e) 
	{ 
		echo " database not connected " ; 
		die(mysql_error());
	}

 
 
	try { 
		mysql_select_db("hack12") ;
	    }catch  (Exception $e)
	    { 
	   	 echo " database not connected , table not found  " ; 
	   	 die(mysql_error());
	    }
	    
	    
	   function bug($e)
	    {
	    	echo "<pre>";
	    	print_r($e);
	    	echo "</pre>" ; 
	    }
	    function alert($e)
	    {
	    echo '<div class="alert"><a class="close" data-dismiss="alert">×</a><h4>Alert </h4><p>'.$e.' </div>';
	    }
	    
// Retrieve all the data from the "example" table
 // $result = mysql_query("SELECT * FROM example") or die(mysql_error());  
