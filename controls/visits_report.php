<?php

	include 'libraries/visits.class.php';
	$visitsObj = new visits();

	$formErrors = null;
	$fields = array();
	$formSubmitted = false;

	$data = array();
	if(!empty($_POST['submit'])) {
		$formSubmitted = true;

		// nustatome laukų validatorių tipus
		$validations = array (
			'dateFrom' => 'date',
			'dateTill' => 'date');

		// sukuriame validatoriaus objektą
		include 'utils/validator.class.php';
		$validator = new validator($validations);


		if($validator->validate($_POST)) {
			// suformuojame laukų reikšmių masyvą SQL užklausai
			$data = $validator->preparePostFieldsForSQL();
		} else {
			// gauname klaidų pranešimą
			$formErrors = $validator->getErrorHTML();
			// gauname įvestus laukus
			$fields = $_POST;
		}
	}

if($formSubmitted == true && ($formErrors == null)) { ?>
	<div id="header">
		<ul id="reportInfo">
			<li class="title">Vists report</li>
			<li>Date issued: <span><?php echo date("Y-m-d"); ?></span></li>
			<li>Visits period:
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
				<a href="report.php?id=1" title="New report" class="newReport">New report</a>
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
							Neįvesti arba neteisingai įvesti šie laukai:
							<?php
								echo $formErrors;
							?>
						</div>
					<?php } ?>
					<form action="" method="post">
						<fieldset>
							<legend>Enter report criteria</legend>
							<p><label class="field" for="dateFrom">Visits from</label><input type="text" id="dateFrom" name="dateFrom" class="textbox-100 date" value="<?php echo isset($fields['dateFrom']) ? $fields['dateFrom'] : ''; ?>" /></p>
							<p><label class="field" for="dateTill">Visits till</label><input type="text" id="dateTill" name="dateTill" class="textbox-100 date" value="<?php echo isset($fields['dateTill']) ? $fields['dateTill'] : ''; ?>" /></p>
						</fieldset>
						<p><input type="submit" class="submit" name="submit" value="Issue report"></p>
					</form>
				</div>
	<?php	} else {
					// išrenkame ataskaitos duomenis
					$visitsReportData = $visitsObj->getVisitsReport($data['dateFrom'], $data['dateTill']);
					$visitsReportCount = $visitsObj->getCountOfVisitsReport($data['dateFrom'], $data['dateTill']);

					if(sizeof($visitsReportData) > 0) { ?>
						<table class="reportTable">
							<tr>
								<th>Date</th>
								<th>Time</th>
								<th>Customer</th>
								<th>Fitness Club</th>
							</tr>

							<tr>
								<td class="separator" colspan="5"></td>
							</tr>

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
							<td class="label" colspan="3">Visits count:</td>
							<td class="border"><?php echo "{$visitsReportCount[0]['count']}"; ?></td>
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
