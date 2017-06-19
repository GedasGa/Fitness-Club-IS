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
