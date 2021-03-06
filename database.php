<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>UWS Solar Car Project - IT Admin</title>
		<link rel="stylesheet" href="master.css" type="text/css" /> 
		<script src="jquery-2.1.4.min.js"></script>
		<script>
			function refreshTable() 
			{
				$('#content').load( "database.php #content");
			}
			setInterval(refreshTable, 10000);
		</script>
		<style>
			body:after 			{	
				background-image: url("car.jpg");
				background-size: 1920px 1080px;		
			}
		</style>
		
		<?php 
		session_start();
		
		// SQL For the rasberry pies
		// SQL DB: MAIN : SQL TABLE: 'RPi'
		// FORMAT: PI NAME, PI IP ADDRESS, PI PING , PI LAST SEEN, SERVICES (SSH/PHP/DBM)
		
		//...Check if we have host name
		if (!isset($_SESSION["hostname"]))
				header("location: home.php");
			else 
			{
				//Set SQL Database Settings
				$servername = $_SESSION["hostname"];
				$username = $_SESSION["username"];
				$password = $_SESSION["password"];
				$dbname = $_SESSION["dbname"];
				$port = $_SESSION["port"];
			}
			
		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		
		//...POST FROM a form
		if (  isset($_POST["RPiControlPannel"]) ) {
			//...Control Pannel was executed : 
			
		}else if (  isset($_POST["PiSelectPannel"]) ) {
			//...Rasberry Pi selected : 
			if ( isset ( $_POST['Submit'] ) && isset ( $_POST['PiSelectedIP'] )) {
					//...We have a rasberry selected;
					$_SESSION['PiControl']="ACTIVE";
					
			}else if ( isset ( $_POST['Reset'] ) ) {
					unset ( $_SESSION['PiControl'] );
			}
			
		}		
			
			?> 
	</head>
	<body>
		<div id="container">
			<?php require_once("headerBar.php"); ?>
			<div id="content">
                <?php
                    
                ?>
                <form id="DatabaseControlForm" method="POST">
                    <div id="DBTablePannel">
                    <table>
					<tr>
						<th>
							Table Number
						</th>
						<th>
							Table Name
						</th>
						<th>
							Number of Rows
						</th>
					</tr>
                    <?php
                        function getTableRowCount($xTable , $sqlConn ) {
                            $row_cnt = 0;
							$selectStatement = "SELECT count(*) FROM `".$xTable."`;";
							
                                /* determine number of rows result set */
                            $result = mysqli_query($sqlConn, $selectStatement);
							if (mysqli_num_rows ($result)>0){
								$row = mysqli_fetch_array ( $result );
								$row_cnt = $row[0];
							}
                            return $row_cnt;
                        }
                        // List all tables under current database
                        echo "<h2> Database Tables Display</h2>";
                        $dbname = $_SESSION['dbname'];                 

                        $sql = "SHOW TABLES FROM $dbname;";
                        $result = mysqli_query ( $conn , $sql);

                        if (!$result) {
                            echo "DB Error, could not list tables\n";
                            echo 'MySQL Error: ' . mysql_error();
                            exit;
                        }
                        $_tRowCount = 1;
                        
                        //...Print out all the tables
                         while($row = mysqli_fetch_array ( $result ) ) {
                            $_qRowCount = getTableRowCount ( $row[0] , $conn );
                            echo "<tr><td>$_tRowCount</td><td> {$row[0]} </td><td> $_qRowCount </td> </tr>\n";
                            $_tRowCount = $_tRowCount + 1;
                        }

                        mysqli_free_result($result);
                    ?>
                    </table>
                    <div id= "DBControlPannel" >
                        <h1> Database Control Panel </h1>
                        <h3> Current Database: </h3>
                        <h3> Delete Database </h3>
                        <h3> Create Database </h3>
                    </div>    
                </form>
			</div>
		</div>
	</body>
	<?php 	
		//Close the connection
		mysqli_close ( $conn ); 
	?>
</html> 