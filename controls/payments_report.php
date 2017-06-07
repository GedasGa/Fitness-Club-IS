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
	<div id="header">
		<ul id="reportInfo">
			<li class="title">Invoices report</li>
			<li>Date issued: <span><?php echo date("Y-m-d"); ?></span></li>
			<li>Invoices period:
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
				<a href="report.php?id=2" title="New report" class="newReport">New report</a>
			</li>
		</ul>
	</div>
<?php } ?>
<div id="content">
	<div id="contentMain">
		<?php
			if($formSubmitted == false || $formErrors != null) { ?>
				<div id="formContainer">
					<?php if($formErrors != null) { ?>
						<div class="errorBox">
							Fill in all required fields in right format:
							<?php
								echo $formErrors;
							?>
						</div>
					<?php } ?>
					<form action="" method="post">
						<fieldset>
							<legend>Enter report criteria</legend>
							<p><label class="field" for="dateFrom">Contracts made from</label><input type="text" id="dateFrom" name="dateFrom" class="textbox-100 date" value="<?php echo isset($fields['dateFrom']) ? $fields['dateFrom'] : ''; ?>" /></p>
							<p><label class="field" for="dateTill">Contracts made till</label><input type="text" id="dateTill" name="dateTill" class="textbox-100 date" value="<?php echo isset($fields['dateTill']) ? $fields['dateTill'] : ''; ?>" /></p>
						</fieldset>
						<p><input type="submit" class="submit" name="submit" value="Issue report"></p>
					</form>
				</div>
	<?php	} else {
					// electing Payment data
					$AccountReportData = $paymentsObj->getAccountReport($data['dateFrom'], $data['dateTill']);
					$AccountSumData = $paymentsObj->getAccountSum($data['dateFrom'], $data['dateTill']);

					if(sizeof($AccountReportData) > 0) { ?>

						<table class="reportTable">
							<tr>
								<th>Invoice</th>
								<th>Employee</th>
								<th>Customer</th>
								<th>Contract price</th>
								<th>Left to pay</th>
							</tr>

							<tr>
								<td class="separator" colspan="5"></td>
							</tr>

							<?php
								// generating table
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

							<tr class="aggregate">
							<td class="label" colspan="3">Suma:</td>
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
