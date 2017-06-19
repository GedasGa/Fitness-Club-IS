<?php
	// reading configuration file
	include 'config.php';

	// logging in to database class
	include 'utils/mysql.class.php';

	// set selected module
	$module = '';
	if(isset($_GET['module'])) {
		$module = mysql::escape($_GET['module']);
	}

	// set selected element id
	$id = '';
	if(isset($_GET['id'])) {
		$id = mysql::escape($_GET['id']);
	}

	// checking if it is new element which we want to create
	$action = '';
	if(isset($_GET['action'])) {
		$action = mysql::escape($_GET['action']);
	}

	// if element is going to be deleted, set its id
	$removeId = 0;
	if(!empty($_GET['remove'])) {
		// set $_GET array id value for SQL query
		$removeId = mysql::escape($_GET['remove']);
	}

	// set elements list page number
	$pageId = 1;
	if(!empty($_GET['page'])) {
		$pageId = mysql::escape($_GET['page']);
	}

	// set, how many records will be showed in elements list
	define('NUMBER_OF_ROWS_IN_PAGE', 10);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="../../favicon.ico">

		<title>Fitness Clubs IS</title>

		<!-- Bootstrap core CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css"
		integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="utils/style/navbar.css" rel="stylesheet">
	</head>
	<body>
		<div class="container">
      <nav class="navbar navbar-inverse bg-inverse rounded navbar-toggleable-md">
					<img src="utils/gym-logo.png" alt="Gym Logo" style="width:45px;">
        	<a class="navbar-brand text-warning" href="index.php">Fitness Clubs IS</a>

        <div class="collapse navbar-collapse" id="containerNavbar">
          <ul class="navbar-nav mr-auto">
						<li class="nav-item">
							<a href="index.php?module=subscriptions" title="Subscriptions"
							<?php if($module == 'subscriptions') { echo 'class="nav-link active"'; }
										else { echo 'class="nav-link"'; }?>>Subscriptions</a>
						</li>
						<li class="nav-item">
							<a href="index.php?module=visits" title="Visits"
							<?php if($module == 'visits') { echo 'class="nav-link active"'; }
										else { echo 'class="nav-link"'; }?>>Visits</a>
						</li>
						<li class="nav-item">
							<a href="index.php?module=payments" title="Payments"
							<?php if($module == 'payments') { echo 'class="nav-link active"'; }
										else { echo 'class="nav-link"'; }?>>Payments</a>

						</li>
						<li class="nav-item">
							<a href="index.php?module=customers" title="Customers"
							<?php if($module == 'customers') { echo 'class="nav-link active"'; }
										else { echo 'class="nav-link"'; }?>>Customers</a>
						</li>
						<li class="nav-item">
							<a href="index.php?module=employees" title="Employees"
							<?php if($module == 'employees') { echo 'class="nav-link active"'; }
										else { echo 'class="nav-link"'; }?>>Employees</a>
						</li>
						<li class="nav-item">
							<a href="index.php?module=gyms" title="Fitness Clubs"
							<?php if($module == 'gyms') { echo 'class="nav-link active"'; }
										else { echo 'class="nav-link"'; }?>>Gyms</a>
						</li>
						<li class="nav-item">
							<a href="index.php?module=city" title="Cities"
							<?php if($module == 'city') { echo 'class="nav-link active"'; }
										else { echo 'class="nav-link"'; }?>>Cities</a>
						</li>
						</ul>
						<ul class="nav navbar-nav ml-auto">
							<li class="nav-item">
								<a href="index.php?module=report" title="Reports"
								<?php if($module == 'report') { echo 'class="nav-link active"'; }
											else { echo 'class="nav-link"'; }?>>Reports</a>
							</li>
						</ul>
        </div>
      </nav>
      <div class="jumbotron">
        <div class="col-sm-12 mr-auto">
					<div id="contentMain">
						<?php
							if(!empty($module)) {
								if(empty($id) && empty($action)) {
									include "controls/{$module}_list.php";
								} else {
									include "controls/{$module}_edit.php";
								}
							} else {
								echo "<img class='float-left' src='utils/gym-logo.png'  alt='Gym Logo'>";
								echo "<h1 class='text-warning' style='text-align:center;'>Gold's Gym Information system<h1>";
								echo "<h5 style='text-align:center;'>Using this website you can select, insert, update and delete
								all data and records in the database using PHP language and SQL queries.<h5></br>";
								echo "<h5 style='text-align:center;'>This project was made by:<strong> \"LET'S WORKOUT\" TEAM</strong><h5></br></br>";
								echo "<h5 style='text-align:right;'>Our team members:<h5>";
								echo "<h6 style='text-align:right;'>Seung Jun Lee</br>Min Seok Jung</br>Han Pyo Lee</br>Gedas Gardauskas</br>Mindaugas Pazereckas<h5>";
							}
						?>
						<div class="float-clear"></div>
					</div>
        </div>
      </div>
    </div>

		<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
		<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js"
		integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"
		integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"
		integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
		<!--JavaScript for Adding childs and confirmation  -->
		<script src="scripts/main.js"></script>
	</body>
</html>
