<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>UWS Solar Car Project - Home</title>
		<link rel="stylesheet" href="master.css" type="text/css" /> 
		<script type="text/javascript" src="jquery-2.1.4.min.js"></script> 
		<script src="amcharts.js" type="text/javascript"></script>
        <script src="serial.js" type="text/javascript"></script>
		<script src="dataloader.min.js" type="text/javascript"></script>
		
		<?php
			session_start();
			
			if (isset($_POST["resetServerDetails"]))
			{
				unset($_SESSION["hostname"]);
				unset($_SESSION["dbname"]);
				unset($_SESSION["port"]);
				unset($_SESSION["username"]);
				unset($_SESSION["password"]);
				header("location: home.php");
			}
		?>

		<script type="text/javascript">
			function refreshTable() 
			{
				if ($('#refreshHomePage').val() == 1)
				$('#secondHomeContent').load( "home.php #secondHomeContent");
			}
			setInterval(refreshTable, 500);
			
			
			
			
		</script>
		
		<script type="text/javascript">
			function playPause()
			{
				if (document.getElementById("refreshHomePage").value == 1)
				{
					document.getElementById("playPauseHome").value = "Play";
					document.getElementById("refreshHomePage").value = 0;
				}
				else
				{
					document.getElementById("playPauseHome").value = "Pause";
					document.getElementById("refreshHomePage").value = 1;
				}
			}
		</script>
		
		<script type="text/javascript">
			/*var chart = AmCharts.makeChart( "Motor_Phases", {
			"type": "serial",
			"mouseWheelScrollEnabled": true,
			"startEffect": "easeOutSine",
			"dataLoader": {
			  "url": "graphDataMotorPhases.php"
			},
			"categoryField": "category",
			"dataDateFormat": "YYYY-MM-DD HH:NN:SS",
			"startDuration": 0,
			"categoryAxis": {
			  "parseDates": true,
			  "minorGridEnabled": true,
			  "twoLineMode": true,
			   "minPeriod": "ss"
			},
			"graphs": [ {
			  "valueField": "Phase C Current",
			  "bullet": "round",
			  "bulletBorderColor": "#000000",
			  "bulletBorderThickness": 2,
			  "lineThickness ": 2,
			  "lineAlpha": 1,
			  "title": "Phase C Current"

			}, {
			  "valueField": "Phase B Current",
			  "bullet": "square",
			  "bulletBorderColor": "#000000",
			  "bulletBorderThickness": 2,
			  "lineThickness ": 2,
			  "lineAlpha": 1,
			  "title": "Phase B Current"
			} ]
		  }

			  );
			  
			   // CURSOR
               var chartCursor = new AmCharts.ChartCursor();
               chartCursor.cursorAlpha = 0.1;
               chartCursor.fullWidth = true;
               chart.addChartCursor(chartCursor);

               // SCROLLBAR
               var chartScrollbar = new AmCharts.ChartScrollbar();
               chart.addChartScrollbar(chartScrollbar);
			  
			  var legend = new AmCharts.AmLegend();
               legend.marginLeft = 110;
               legend.useGraphSettings = true;
               chart.addLegend(legend);	*/	 
	</script>
		
	</head>
	<body>

			<?php 
			require_once("headerBar.php");
			if (!isset($_POST["hostname"]) && !isset($_SESSION["hostname"])) 
			{ ?>
				<div id="content">
					<form method = "post" action = "home.php" id = "pageSetup" >
						<fieldset>
							<legend>Please enter the Data Server Details.</legend>
							<label for = "hostname">Please enter the Host Name: </label><input id = "hostname" name = "hostname" value = "127.0.0.1"/> <br/> <br/>
							<label for = "dbname">Please enter the Database Name: </label><input id = "dbname" name = "dbname" value = "can_bus"/> <br/> <br/>
							<label for = "port">Please enter the Port Number: </label><input id = "port" name = "port" value = "3306"/> <br/> <br/>
							<label for = "username">Please enter the User Name: </label><input id = "username" name = "username" value = "solar"/> <br/> <br/>
							<label for = "password">Please enter the Password: </label><input id = "password" name = "password" value = "solar"/> <br/> <br/>
							<input type = "submit" value="Submit" />
						 </fieldset>
					 </form>
				
				</div>
			<?php }
			else
			{ 
				if (isset($_POST["hostname"]))
				{
					$_SESSION["hostname"] = $_POST["hostname"];
					$_SESSION["dbname"] = $_POST["dbname"];
					$_SESSION["port"] = $_POST["port"];
					$_SESSION["username"] = $_POST["username"];
					$_SESSION["password"] = $_POST["password"];
					
				}?>
				<div id="secondHomeContent">
				<?php 
				if (isset($_SESSION["hostname"]))
				{
					$conn = mysqli_connect($_SESSION["hostname"], $_SESSION["username"], $_SESSION["password"], $_SESSION["dbname"], $_SESSION["port"]);
					$sqlSelectVelocity = "select distinct `Vehicle_Velocity` from `velocity_measurement` order by packet_number desc limit 10";
					$velocityResult = mysqli_query($conn, $sqlSelectVelocity);
					$velocityData = 0;
					$rowCount = 0;
					if ($velocityResult != false)
					{
						while($row = mysqli_fetch_array($velocityResult))
						{
							$velocityData = $row['Vehicle_Velocity'] + $velocityData;
							$rowCount++;
						}
						if ($rowCount != 0)
							$velocityData = $velocityData / $rowCount;
					}
					else
						$velocityData['Vehicle_Velocity'] = "No Data";
						
					$sqlSelectPower = "select distinct `Bus_Current`, `Bus_Voltage` from `bus_measurement`order by packet_number desc limit 10";
					$powerResult = mysqli_query($conn, $sqlSelectPower);
					$busCurrent = 0;
					$busVoltage = 0;
					$rowCount = 0;
					if ($powerResult != false)
					{
						while ($row = mysqli_fetch_assoc($powerResult))
						{
							$busCurrent = $row['Bus_Current'] + $busCurrent;
							$busVoltage = $row['Bus_Voltage'] + $busVoltage;
							$rowCount++;
						}
						if ($rowCount != 0)
						{
							$powerData['Bus_Current'] = $busCurrent / $rowCount;
							$powerData['Bus_Voltage'] = $busVoltage / $rowCount;
						}
					}
					else
					{
						$powerData['Bus_Current'] = "n/a";
						$powerData['Bus_Voltage'] = "n/a";
					}
						
				}?>
				<table>
					<tr>
						<th>
							Vehicle Velocity (km/hr):
						</th>
						<td>
							<?php 
								$velocity = $velocityData['Vehicle_Velocity'] * 3.6;
								echo $velocity;
							?>
						</td>
						<th>
							Power Usage (W):
						</th>
						<td>
							<?php
								if ($powerData['Bus_Current'] == "n/a" || $powerData['Bus_Voltage'] == "n/a")
									echo "No Data";
								else
								{
									$power = $powerData['Bus_Current'] * $powerData['Bus_Voltage'];
									echo "$power";
								}
							?>
						</td>
					</tr>
				</table>
				</div>
				<!--<div id="Motor_Phases" style="width: 100%; height: 400px;"> </div> -->
				<div>
					<form method = "post" action = "home.php" id = "pageReset" >
						<p><input type="hidden" id="resetServerDetails" name="resetServerDetails" value="1"/>
						<input type="button" value="Pause" onclick="playPause()" id = "playPauseHome" name = "playPauseHome"/>
						<input type = "hidden" id = "refreshHomePage" name = "refreshHomePage" value = "1"/>
						<input type = "submit" value="Reset Server Details" /></p>
					</form>
				</div>
			<?php } ?>
			
		

	</body>

</html> 