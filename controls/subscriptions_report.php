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
	<div id="header">
		<ul id="reportInfo">
			<li class="title">Customers Subscriptions report</li>
			<li>Date issued: <span><?php echo date("Y-m-d"); ?></span></li>
			<li>Subscription period:
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
				<a href="report.php?id=3" title="New report" class="newReport">New report</a>
			</li>
		</ul>
	</div>
<?php } ?>
<div id="content">
	<div id="contentMain">
		<?php if($formSubmitted == false || $formErrors != null) { ?>
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
						<p><label class="field" for="dateFrom">Subscriptions made from</label><input type="text" id="dateFrom" name="dateFrom" class="textbox-100 date" value="<?php echo isset($fields['dateFrom']) ? $fields['dateFrom'] : ''; ?>" /></p>
						<p><label class="field" for="dateTill">Subscriptions made till</label><input type="text" id="dateTill" name="dateTill" class="textbox-100 date" value="<?php echo isset($fields['dateTill']) ? $fields['dateTill'] : ''; ?>" /></p>
					</fieldset>
					<p><input type="submit" class="submit" name="submit" value="Issue report"></p>
				</form>
			</div>
		<?php } else {

				// išrenkame ataskaitos duomenis
				$subscriptionData = $subscriptionsObj->getCustomerSubscriptions($data['dateFrom'], $data['dateTill']);
				$totalSubscriptionsPrice = $subscriptionsObj->getSumPriceOfCustomerSubscriptions($data['dateFrom'], $data['dateTill']);

				if(sizeof($subscriptionData) > 0) { ?>

					<table class="reportTable">
						<tr>
							<th>Subscription (Number, Date)</th>
							<th>Subscription period</th>
							<th>Type</th>
							<th>Price</th>
						</tr>

						<?php

							// suformuojame lentelę
							for($i = 0; $i < sizeof($subscriptionData); $i++) {

								if($i == 0 || $subscriptionData[$i]['personal_id'] != $subscriptionData[$i-1]['personal_id']) {
									echo
										"<tr class='rowSeparator'><td colspan='5'></td></tr>"
										. "<tr>"
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
											. "<td class='border'>{$subscriptionData[$i]['total_customer_subscriptions_price']} &euro;</td>"
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

						<tr class="aggregate">
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
