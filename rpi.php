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
				$('#content').load( "it.php #content");
			}
			setInterval(refreshTable, 3000);
		</script>
		
		<?php 
		session_start();
		
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
			
			?> 
	</head>
	<body>
		<div id="container">
			<div id="headerbar">
				<h1>UWS Solar Car Project - IT Admin</h1>
				<p> 
					Menu Options: <a href="home.php">Home</a> <a href="electrical.php">Electrical</a> <a href="battery.php">Battery</a> <a href="motors.php">Motors</a> <a href="it.php">IT Admin</a>
				</p>
			</div>
			<div id="content">
				<p> 
					<form>
						<div id="PiSelect">
						<table style="width:100%">
						  
							<?php 
								// SQL For the rasberry pies
								// SQL DB: MAIN : SQL TABLE: 'RPi'
								// FORMAT: PI NAME, PI IP ADDRESS, PI PING , PI LAST SEEN, SERVICES (SSH/PHP/DBM)
								$servername = "localhost";
								$username = "solar"; //...READ ONLY PERMISSIONS
								$password = "solar";
								$dbname = "main";
								
								// Create connection
								$conn = new mysqli($servername, $username, $password, $dbname);
								// Check connection
								if ($conn->connect_error) {
									die("Connection failed: " . $conn->connect_error);
								} 

								$sql = "SELECT id, ip, ping FROM RPi";
								$result = $conn->query($sql);

								if ($result->num_rows > 0) {
									// output data of each row
									while($row = $result->fetch_assoc()) {
										echo "<tr>
												<td>id: " . $row["ID"]. " - Name: " . $row["NAME"]. " " . $row["IP"]. "<br></td>
											 <tr>";
									}
								} else {
									echo "0 results";
								}
								$conn->close();
								
							?>
						</table>
						</div>
						
						<div id="PiControl">
							<?php 
								//....DISPLAY RASBERRY PI :
								if ( isset ( $_SESSION['PiControl'] ) ) {
									
								}else {
										?>
											<h2> PLEASE SELECT A RASBERRY FROM THE LIST </h2>
											<p> Raspberry Pi control panel: </p>									
										<?php 
								}
							?>
						</div>						
					</form>
				</p>
			</div>
		</div>
	</body>
</html> 