<!DOCTYPE html>
<?php 
  $server = "localhost";
  $user = "root";
  $password = "";
  $db = "garage";

  $c = mysqli_connect($server, $user, $password, $db);

  if(mysqli_connect_errno())
  {
    die("Connection error:" . mysqli_connect_error());
    header("location: login.php");
    exit();
  }

  session_start();
   
   $identity = $_SESSION['name'];
   $heading = $_SESSION['email'];
   $authority = $_SESSION['conduct'];
   $line = $_SESSION['line'];
   
   $query =  "SELECT * FROM accounts WHERE email = '$heading'";
   $ps = mysqli_query($c, $query);

    if(!$ps)
    {
        die("Failed to retrieve data:" . mysqli_error($c));
        header("location: login.php");
        exit();
    }
    else
    {  
	 if(!isset($_SESSION['name']) && !isset($_SESSION['email']) && !isset($_SESSION['conduct']) && !isset($_SESSION['line']))
	 {
	    header("location:login.php");
	    exit();
	 }
    }

     if ($authority != "manager")
     {
     	header("location:home.php");
        exit();
     }

     $inventory = "";
     $peculiar = "";

     if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$progress = mysqli_real_escape_string($c, $_POST['progress']);
		$direction = mysqli_real_escape_string($c, $_POST['direction']);
		$criterion = mysqli_real_escape_string($c, $_POST['criterion']);

		if (isset($_POST['submit']))
		{	
			$query4 =  "SELECT * FROM accounts WHERE email = '$direction'";
			$ps4 = mysqli_query($c, $query4);


			if(!$ps4)
		    {
		        die("Failed to retrieve data:" . mysqli_error($c));
		        header("location: updateschedule.php");
		        exit();
		    }

		    $feedback = mysqli_num_rows($ps4);

		    if ($feedback != 0)
		    {
		    	$query9 = "SELECT * FROM schedule WHERE email = '$direction' AND serialno = '$criterion'";

				$ps9 = mysqli_query($c, $query9);
				if(!$ps9)
			    {
			        die("Failed to retrieve data:" . mysqli_error($c));
			        header("location: schedule.php");
			        exit();
			    }

			    $instance = mysqli_num_rows($ps9);

			    if ($instance>0)
			    {
			    	$query3 = "UPDATE schedule SET status = '$progress' WHERE email = '$direction' AND serialno = '$criterion'";
					
					$ps3 = mysqli_query($c, $query3);

					if(!$ps3)
				    {
				        die("Failed to update data:" . mysqli_error($c));
				        header("location: updateschedule.php");
				        exit();
				    }
				    else
				    {
				    	$query5 = "SELECT * FROM notifications";

						$ps5 = mysqli_query($c, $query5);
						
						if(!$ps5)
					    {
					        die("Failed to retrieve data:" . mysqli_error($c));
					        header("location: userinsert.php");
					        exit();
					    }
					    
					    $iteration = mysqli_num_rows($ps5);

					    if($iteration == 0)
					    {
					    	$source = 1;
					    }
					    else
					    {
					    	$source = $iteration + 1;
					    }

					    $query6 = "SELECT * FROM notifications WHERE serialno = '$source'";

						$ps6 = mysqli_query($c, $query6);
						if(!$ps6)
					    {
					        die("Failed to retrieve data:" . mysqli_error($c));
					        header("location: userinsert.php");
					        exit();
					    }
					    $valid = mysqli_num_rows($ps6);

					    if($valid != 0)
					    {
					    	while ($rs6 = mysqli_fetch_assoc($ps6))
					    	{
					    		$source = $rs6['serialno'] + 1;
					    	}
					    }

					    $query7  = "SELECT * FROM accounts WHERE email = '$direction'";

						$ps7 = mysqli_query($c, $query7);

						if(!$ps7)
					    {
					        die("Failed to retrieve data:" . mysqli_error($c));
					        header("location: insertnotifications.php");
					        exit();
					    }
					    
					    $reading = mysqli_num_rows($ps7);

					    if($reading != 0)
					    {
					    	while ($rs7 = mysqli_fetch_assoc($ps7))
					    	{
						    	$directory = $rs7['first'];
						    	$surname = $rs7['last'];
						    	$beacon = $rs7['email'];
						    	$contact = $rs7['phone'];

						    	$designate = $directory . " " . $surname;
						    }

							$query8 = "INSERT INTO `notifications` (`serialno`,`reminder`, `category`, `priority`, `username`, `phone`, `email`) VALUES ('$source','Schedule Update', 'schedule', 'high', '$designate', '$contact' ,'$beacon')";
									
							$ps8 = mysqli_query($c, $query8);

							if(!$ps8)
							{
								die("Failed to insert data:" . mysqli_error($c));
								header("location: updateschedule.php");
								exit();
							}

							header("location: schedule.php");
		        			exit();
						}
					}
				}
				else
				{
					$peculiar = "*Entry Unavailable";
				}
		    }
			else
			{
				$inventory = "*User does not Exist";
			}
		}
	}

	mysqli_close($c);
?>
<html>
<head>
	<title>Update Schedule</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="gms.css">
	<script src="gms.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.6/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>
</head>
	<body>
		<div>
			<ul class= "nav justify-content-end">
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" data-toggle="dropdown">
					<img src = "id icon.png" alt = "Login" id = "scale" class = "rounded">						
					<strong><?php echo $identity; ?></strong>
					</a>
				    <div class="dropdown-menu">
				      <a class="dropdown-item" href="logout.php">
						<img src = "lock.png" alt = "lock" id = "scale" class = "rounded">
						Logout
						</a>
				    </div>
				</li>
			</ul>
		</div>
		<div>
			<center>
				<a href = "home.php"><h1><strong>GARAGE MANAGEMENT SYSTEM</strong></h1></a>
			</center>
		</div>
		<div>
			<center>
				<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
				  <ul class="navbar-nav">
				    <li class="navbar-brand">
				    <a class="nav-link" href="home.php">
						<img src = "home.png" alt = "home" id = "scale" class = "rounded">
						Home
					</a>
				    </li>
				    <li class="nav-item dropdown navbar-brand">
				      <a class="nav-link dropdown-toggle" id="navbardrop" data-toggle="dropdown">
						<img src = "details.png" alt = "profile" id = "scale">
							Profile
							<div class="dropdown-menu">
						        <a class="dropdown-item" href="userprofile.php">
									<img src = "Profile Picture.png" alt = "profile" id = "scale" class = "rounded">
									User Profile
								</a>
						        <a class="dropdown-item" href="adminprofile.php">
									<img src = "group icon.png" alt = "group" id = "scale" class = "rounded">
									View Users
								</a>
							</div>
						</a>
				    </li>
				    <li class="navbar-brand">
				      <a class="nav-link" href="vehicles.php">
						<img src = "vehicle icon.png" alt = "vehicle" id = "scale" class = "rounded">
						Vehicles</a>
				    </li>
					<li class="navbar-brand">
				      <a class="nav-link" href="notifications.php">
						<img src = "notifications.png" alt = "notification" id = "scale" class = "rounded">
						Notifications
						</a>
				    </li>
					<li class="navbar-brand nav-item active">
				      <a class="nav-link" href="schedule.php">
						<img src = "schedule icon.png" alt = "schedule" id = "scale" class = "rounded">
						Schedule
						</a>
				    </li>
					<li class="navbar-brand">
				      <a class="nav-link" href="payment.php">
						<img src = "payment icon.png" alt = "payment" id = "scale" class = "rounded">
						Payment</a>
				    </li>
					<li class="navbar-brand">
				      <a class="nav-link" href="history.php">
						<img src = "history icon.png" alt = "history" id = "scale" class = "rounded">
						Service History
					</a>
				    </li>
				  </ul>
				</nav>
			</center>
		</div>
		<div id = "drape">
			<center>
				<fieldset>
					<form name = "updateschedule" method = "post" action = "updateschedule.php" onsubmit = "return(updateschedule());">
					<div class = "form-group">
						<h1><strong><center>Update Entry</center></strong></h1>
					</div>
					<div class="form-group">
							<div class="form-group">
							    <label>Status:</label>
							    <select class="form-control" name = "progress" id = "modify">
									<option value = "approved">Approved</option>
									<option value = "rejected">Rejected</option>
								</select>
							  </div>
							<div class="form-group">
							    <label>Serial No.:</label>
							    <input type="text" class="form-control" name = "criterion" id = "modify">
						  	</div>
							<div class="form-group">
							    <label>Email Address:</label>
							    <input type="text" class="form-control" name = "direction" id = "modify">
								<div class = "text-danger">
									<?php echo $inventory; ?>
									<?php echo $peculiar; ?>
								</div>
						  	</div>
						<button type="submit" class="btn btn-dark" name = "submit">Update</button>
					</form>
				</fieldset>
			</center>
		</div>
		<div>
			<footer id = "footnote">
				<center>
					<h1> (C). 2017 All Rights Reserved</h1>
				</center>
			</footer>
		</div>
	</body>
</html>