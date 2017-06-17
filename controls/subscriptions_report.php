<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="../../favicon.ico">

		<!-- Bootstrap core CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css"
		integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="utils/style/navbar.css" rel="stylesheet">
	</head>
<?php

	include 'libraries/subscriptions.class.php';
	$subscriptionsObj = new subscriptions();

	$formErrors = null;
	$fields = array();
	$formSubmitted = false;

	$data = array();
	if(!empty($_POST['submit'])) {
		$formSubmitted = true;

		// set field validator types
		$validations = array (
			'dateFrom' => 'date',
			'dateTill' => 'date');

		// creating validator object
		include 'utils/validator.class.php';
		$validator = new validator($validations);


		if($validator->validate($_POST)) {
			// creating field array of data for SQL query
			$data = $validator->preparePostFieldsForSQL();
		} else {
			// getting error notification
			$formErrors = $validator->getErrorHTML();
			// getting all information filled into fields
			$fields = $_POST;
		}
	}

if($formSubmitted == true && ($formErrors == null)) { ?>
	<body>
			<div class="container">
				<div class="jumbotron">
						<div class="col-sm-12 mr-auto">
							<ul class="list-inline text-right">
								<li class="list-inline-item"><a href="report.php?id=3" title="New report" class="newReport btn btn-info btn-sm"><i class="fa fa-list-alt" aria-hidden="true"></i> New report</a><li>
							</ul>
							<ul class="list-group" id="reportInfo" style="text-align:center;">
								<li class="list-group-item bg-inverse text-white" style="display: inline-block;"/>Customers Subscriptions Report</li>
								</br>
								<li class="list-group-item">Date issued: <span><?php echo date("Y-m-d"); ?></span></li>
								<li class="list-group-item">Subscription period:
									<span>
										<?php
											if(!empty($data['dateFrom'])) {
												if(!empty($data['dateTill'])) {
													echo "from {$data['dateFrom']} till {$data['dateTill']}";
												} else {
													echo "from {$data['dateFrom']}";
												}
											} else {
												if(!empty($data['dateTill'])) {
													echo "till {$data['dateTill']}";
												} else {
													echo "not stated";
												}
											}
										?>
									</span>
								</li>
							</ul>
						</div>
				</div>
			</div>
		<?php } ?>
		<div class="container">
			<div class="jumbotron">
					<div class="col-sm-12 mr-auto">
					<?php if($formSubmitted == false || $formErrors != null) { ?>
							<div id="formContainer">
								<?php if($formErrors != null) { ?>
									<div class="errorBox">
										<div class="alert alert-danger alert-dismissible fade show" role="alert">
										  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
										    <span aria-hidden="true">&times;</span>
										  </button>
											<strong>Fill in all required fields in right format:</strong>
											<?php
												echo $formErrors;
											?>
										</div>
									</div>
								<?php } ?>
									<form action="" method="post">
										<fieldset>
											<legend align="center">Enter report criteria</legend>
											<div class="form-group row">
												<label class="col-sm-2 col-form-label" for="dateFrom">Subscriptions made from</label>
												<div class="col-sm-10">
													<input type="date" id="dateFrom" name="dateFrom" class="form-control" value="<?php echo isset($fields['dateFrom']) ? $fields['dateFrom'] : ''; ?>" />
												</div>
												<label class="col-sm-2 col-form-label" for="dateTill">Subscriptions made till</label>
												<div class="col-sm-10">
													<input type="date" id="dateTill" name="dateTill" class="form-control" value="<?php echo isset($fields['dateTill']) ? $fields['dateTill'] : ''; ?>" />
												</div>
											</div>
										</fieldset>
										<div class="form-group">
										 <div class="col-sm-10">
											 <input type="submit" class="btn btn-primary" name="submit" value="Issue Report">
										 </div>
									 </div>
									</form>
								</div>
							</div>
					<?php } else {

							// electing subscription report data
							$subscriptionData = $subscriptionsObj->getCustomerSubscriptions($data['dateFrom'], $data['dateTill']);
							$totalSubscriptionsPrice = $subscriptionsObj->getSumPriceOfCustomerSubscriptions($data['dateFrom'], $data['dateTill']);

							if(sizeof($subscriptionData) > 0) { ?>

								<table class="table table-bordered table-hover">
									<thead class="thead-inverse">
										<tr>
											<th>Subscription (Number, Date)</th>
											<th>Subscription period</th>
											<th>Type</th>
											<th>Price</th>
										</tr>
									</thead>

									<?php

										// forming a table
										for($i = 0; $i < sizeof($subscriptionData); $i++) {

											if($i == 0 || $subscriptionData[$i]['personal_id'] != $subscriptionData[$i-1]['personal_id']) {
												echo
													 "<tr class='rowSeparator'><td colspan='5'></td></tr>"
													. "<tr class='bg-info'>"
														. "<td class='groupSeparator' colspan='4'>{$subscriptionData[$i]['name']} {$subscriptionData[$i]['surname']}</td>"
													. "</tr>";
											}

											echo
												"<tr>"
													. "<td>#{$subscriptionData[$i]['id_subscription']}, {$subscriptionData[$i]['valid_from']}</td>"
													. "<td>{$subscriptionData[$i]['valid_from']} - {$subscriptionData[$i]['valid_till']}</td>"
													. "<td>{$subscriptionData[$i]['type']}</td>"
													. "<td>{$subscriptionData[$i]['price']} &euro;</td>"
												."</tr>";

											if($i == (sizeof($subscriptionData) - 1) || $subscriptionData[$i]['personal_id'] != $subscriptionData[$i+1]['personal_id']) {
												echo
													"<tr class='aggregate'>"
														. "<td colspan='3'></td>"
														. "<td class='border bg-warning'>{$subscriptionData[$i]['total_customer_subscriptions_price']} &euro;</td>"
													. "</tr>";
											}

										}
									?>

									<tr class="rowSeparator">
										<td colspan="5"></td>
									</tr>

									<tr class="rowSeparator">
										<td colspan="5"></td>
									</tr>

									<tr class="aggregate bg-success">
										<td class="label" colspan="3">Amount:</td>
										<td class="border"><?php echo $totalSubscriptionsPrice[0]['total_amount']; ?> &euro;</td>
									</tr>
								</table>
						<?php   } else { ?>
									<div class="warningBox">
										No subscriptions purchased during selected period.
									</div>
								<?php
								}
						} ?>
					</div>
			</div>
		</div>
	</body>
</html>
