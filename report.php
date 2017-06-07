<?php
	// reading configuration file
	include 'config.php';

	// include login to database class
	include 'utils/mysql.class.php';

	// set selected report id
	$id = '';
	if(isset($_GET['id'])) {
		$id = mysql::escape($_GET['id']);
	}

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
	<body class="report">
		<div id="body">
			<?php
				switch($id) {
					case 1: include "controls/visits_report.php"; break;
					case 2: include "controls/payments_report.php"; break;
					case 3: include "controls/subscriptions_report.php"; break;
					default: break;
				}
			?>
		</div>
	</body>
</html>
