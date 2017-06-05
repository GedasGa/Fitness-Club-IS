<?php
	// nuskaitome konfigūracijų failą
	include 'config.php';

	// iškviečiame prisijungimo prie duomenų bazės klasę
	include 'utils/mysql.class.php';

	// nustatome pasirinktą modulį
	$module = '';
	if(isset($_GET['module'])) {
		$module = mysql::escape($_GET['module']);
	}

	// jeigu pasirinktas elementas (sutartis, automobilis ir kt.), nustatome elemento id
	$id = '';
	if(isset($_GET['id'])) {
		$id = mysql::escape($_GET['id']);
	}

	// nustatome, ar kuriamas naujas elementas
	$action = '';
	if(isset($_GET['action'])) {
		$action = mysql::escape($_GET['action']);
	}

	// jeigu šalinamas elementas, nustatome šalinamo elemento id
	$removeId = 0;
	if(!empty($_GET['remove'])) {
		// paruošiame $_GET masyvo id reikšmę SQL užklausai
		$removeId = mysql::escape($_GET['remove']);
	}

	// nustatome elementų sąrašo puslapio numerį
	$pageId = 1;
	if(!empty($_GET['page'])) {
		$pageId = mysql::escape($_GET['page']);
	}

	// nustatome, kiek įrašų rodysime elementų sąraše
	define('NUMBER_OF_ROWS_IN_PAGE', 10);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="robots" content="noindex">
		<title>Fitness Clubs IS</title>
		<link rel="stylesheet" type="text/css" href="scripts/datetimepicker/jquery.datetimepicker.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="style/main.css" media="screen" />
		<script type="text/javascript" src="scripts/jquery-1.12.0.min.js"></script>
		<script type="text/javascript" src="scripts/datetimepicker/jquery.datetimepicker.full.min.js"></script>
		<script type="text/javascript" src="scripts/main.js"></script>
	</head>
	<body>
		<div id="body">
			<div id="header">
				<h3 id="slogan"><a href="index.php">Fitness Clubs IS</h3>
			</div>
				<div id="content">
					<div id="topMenu">
						<ul class="float-left">
							<li><a href="index.php?module=subscriptions" title="Subscriptions"<?php if($module == 'subscriptions') { echo 'class="active"'; } ?>>Subscriptions</a></li>
							<li><a href="index.php?module=visits" title="Visits"<?php if($module == 'visits') { echo 'class="active"'; } ?>>Visits</a></li>
							<li><a href="index.php?module=payments" title="Payments"<?php if($module == 'payments') { echo 'class="active"'; } ?>>Payments</a></li>
							<li><a href="index.php?module=customers" title="Customers"<?php if($module == 'customers') { echo 'class="active"'; } ?>>Customers</a></li>
							<li><a href="index.php?module=employees" title="Employees"<?php if($module == 'employees') { echo 'class="active"'; } ?>>Employees</a></li>
							<li><a href="index.php?module=gyms" title="Fitness Clubs"<?php if($module == 'gyms') { echo 'class="active"'; } ?>>Fitness Clubs</a></li>
							<li><a href="index.php?module=city" title="Cities"<?php if($module == 'city') { echo 'class="active"'; } ?>>Cities</a></li>
						</ul>
						<ul class="float-right">
							<li><a href="index.php?module=report" title="Reports"<?php if($module == 'report') { echo 'class="active"'; } ?>>Reports</a></li>
						</ul>
					</div>
					<div id="contentMain">
						<?php
							if(!empty($module)) {
								if(empty($id) && empty($action)) {
									include "controls/{$module}_list.php";
								} else {
									include "controls/{$module}_edit.php";
								}
							}
						?>
						<div class="float-clear"></div>
					</div>
				</div>
			<div id="footer">

			</div>
		</div>
	</body>
</html>
