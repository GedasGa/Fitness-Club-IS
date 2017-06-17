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

	include 'libraries/payments.class.php';
	$paymentsObj = new payments();

	$formErrors = null;
	$fields = array();
	$formSubmitted = false;

	$data = array();
	if(!empty($_POST['submit'])) {
		$formSubmitted = true;

		// set field validators types
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
							<li class="list-inline-item"><a href="report.php?id=2" title="New report" class="newReport btn btn-info btn-sm"><i class="fa fa-list-alt" aria-hidden="true"></i> New report</a><li>
						</ul>
						<ul class="list-group" id="reportInfo" style="text-align:center;">
							<li class="list-group-item bg-inverse text-white" style="display: inline-block;">Invoices Report</li>
							</br>
							<li class="list-group-item">Date issued: <span><?php echo date("Y-m-d"); ?></span></li>
							<li class="list-group-item">Invoices period:
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
							<?php
								if($formSubmitted == false || $formErrors != null) { ?>
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
												<label class="col-sm-2 col-form-label" for="dateFrom">Invoices made from</label>
												<div class="col-sm-10">
													<input type="date" id="dateFrom" name="dateFrom" class="form-control" value="<?php echo isset($fields['dateFrom']) ? $fields['dateFrom'] : ''; ?>" />
												</div>
												<label class="col-sm-2 col-form-label" for="dateTill">Invoices made till</label>
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
						<?php	} else {
									// electing Payment data
									$AccountReportData = $paymentsObj->getAccountReport($data['dateFrom'], $data['dateTill']);
									$AccountSumData = $paymentsObj->getAccountSum($data['dateFrom'], $data['dateTill']);

									if(sizeof($AccountReportData) > 0) { ?>

										<table class="table table-bordered table-striped table-hover">
											<thead class="thead-inverse">
												<tr>
													<th>Invoice</th>
													<th>Employee</th>
													<th>Customer</th>
													<th>Contract price</th>
													<th>Left to pay</th>
												</tr>
											</thead>

											<?php
												// forming table
												foreach($AccountReportData as $key => $val) {

													if($val['payments'] != "payed") {
													$val['payments'] .= " &euro;";
													}

													echo
															"<tr>"
																. "<td>#{$val['number']}, {$val['invoice_date']}</td>"
																. "<td>{$val['employee_name']} {$val['employee_surname']}</td>"
																. "<td>{$val['customer_name']} {$val['customer_surname']}</td>"
																. "<td>{$val['invoice_amount']} &euro;</td>"
																. "<td>{$val['payments']}</td>"
															. "</tr>";
												}
											?>

											<tr class="rowSeparator">
											<td colspan="5"></td>
											</tr>

											<tr class="aggregate bg-success">
											<td class="label" colspan="3">Total price (amount payed):</td>
											<td class="border"><?php echo $AccountSumData[0]['invoices_amount']; ?> &euro;</td>
											<td class="border">
												<?php
													if($AccountSumData[0]['payments_amount'] == 0) {
														$AccountSumData[0]['payments_amount'] = "not payed";
													} else {
														$AccountSumData[0]['payments_amount'] .= " &euro;";
													}

													echo $AccountSumData[0]['payments_amount'];
												?>
											</td>
										</tr>
										</table>

							<?php   } else { ?>
										<div class="warningBox">
											No contracts issued during selected period.
										</div>
									<?php
									}
							}
							?>
					</div>
				</div>
		</body>
</html>
