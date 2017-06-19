<?php

	include 'libraries/visits.class.php';
	$visitsObj = new visits();

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
								<li class="list-inline-item"><a href="report.php?id=1" title="New report" class="newReport btn btn-info btn-sm">
									<i class="fa fa-list-alt" aria-hidden="true"></i> New report</a><li>
							</ul>
							<ul class="list-group" id="reportInfo" style="text-align:center;">
								<li class="list-group-item bg-inverse text-white" style="display: inline-block;">Visits Report</li>
								</br>
								<li class="list-group-item">Date Issued: <span><?php echo date(" Y-m-d"); ?></span></li>
								<li class="list-group-item">Visits Period:
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
		<body>
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
										<div class="form-group row">
											<legend align="center">Enter report criteria</legend>
											<label class="col-sm-2 col-form-label" for="dateFrom">Visits from</label>
											<div class="col-sm-10">
												<input type="date" id="dateFrom" name="dateFrom" class="form-control" value="<?php echo isset($fields['dateFrom']) ? $fields['dateFrom'] : ''; ?>" />
											</div>
											<label class="col-sm-2 col-form-label" for="dateTill">Visits till</label>
											<div class="col-sm-10">
												<input type="date" id="dateTill" name="dateTill" class="form-control" value="<?php echo isset($fields['dateTill']) ? $fields['dateTill'] : ''; ?>" />
											</div>
										</div>
									</fieldset>
									<div class="form-group">
										<div class="col-10">
											<input type="submit" class="btn btn-primary" name="submit" value="Issue Report">
										</div>
									</div>
								</form>
							</div>
						</div>
			<?php	} else {
							// electing all Visits
							$visitsReportData = $visitsObj->getVisitsReport($data['dateFrom'], $data['dateTill']);
							$visitsReportCount = $visitsObj->getCountOfVisitsReport($data['dateFrom'], $data['dateTill']);

							if(sizeof($visitsReportData) > 0) { ?>
								<table class="table table-bordered table-striped table-hover">
									<thead class="thead-inverse">
										<tr>
											<th>Date</th>
											<th>Time</th>
											<th>Customer</th>
											<th>Fitness Club</th>
										</tr>
									</thead>

									<?php
										// suformuojame lentelę
										foreach($visitsReportData as $key => $val) {
											echo
												"<tr>"
													. "<td>{$val['visit_date']}</td>"
													. "<td>{$val['time']}</td>"
													. "<td>{$val['name']} {$val['surname']}</td>"
													. "<td>{$val['fitness_club']}</td>"
												. "</tr>";
										}
									?>

									<tr class="rowSeparator">
									<td colspan="5"></td>
									</tr>

									<tr class="aggregate">
									<td class="label bg-success" colspan="3">Visits count:</td>
									<td class="border bg-success"><?php echo "{$visitsReportCount[0]['count']}"; ?></td>
									</tr>
								</table>

					<?php   } else { ?>
								<div class="warningBox">
									No visits during the selected period.
								</div>
							<?php
							}
					}
					?>
			</div>
		</div>
	</body>
</html>
