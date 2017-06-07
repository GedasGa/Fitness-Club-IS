<?php

	include 'libraries/visits.class.php';
	$visitsObj = new visits();

	// creating Customer class object
	include 'libraries/customers.class.php';
	$customersObj = new customers();

	// creating Fitness Club class object
	include 'libraries/gyms.class.php';
	$gymsObj = new gyms();

	$formErrors = null;
	$fields = array();

	// set required fields array
	$required = array('visit_date', 'time', 'fk_customer_id', 'fk_fitness_club_id');

	// set maximum length for fields
	$maxLengths = array (
		'time' => 8
	);

	// pressed submit button
	if(!empty($_POST['submit'])) {
		include 'utils/validator.class.php';

		// set field validators types
		$validations = array (
			'visit_date' => 'date',
			'time' => 'alfanum',
			'fk_customer_id' => 'positivenumber',
			'fk_fitness_club_id' => 'positivenumber'
		);

		// creating validator object
		$validator = new validator($validations, $required, $maxLengths);

		// fields entered without mistakes
		if($validator->validate($_POST)) {
			// creating field array of data for SQL query
			$data = $validator->preparePostFieldsForSQL();
			if(isset($data['id_visit'])) {
				// updating Visits
				$visitsObj->updateVisit($data);
			} else {
				// finding max id value of Visits in database
				$latestId = $visitsObj->getMaxIdOfVisit();

				// inserting new Visit
				$data['id_visit'] = $latestId + 1;
				$visitsObj->insertVisit($data);
			}

			// redirecting to Visits page
			header("Location: index.php?module={$module}");
			die();
		} else {
			// getting error notification
			$formErrors = $validator->getErrorHTML();
			// getting all information filled into fields
			$fields = $_POST;
		}
	} else {
		// checking if is selected element id. If yes, getting element data and filled in all fields with that data
		if(!empty($id)) {
			$fields = $visitsObj->getVisit($id);
		}
	}
?>
<ul id="pagePath">
	<li><a href="index.php">Home Page</a></li>
	<li><a href="index.php?module=<?php echo $module; ?>">Visits</a></li>
	<li><?php if(!empty($id)) echo "Edit visit"; else echo "Add visit"; ?></li>
</ul>
<div class="float-clear"></div>
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
			<legend>Visit information</legend>
			<p>
				<label class="field" for="visit_date">Visit date<?php echo in_array('visit_date', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="visit_date" name="visit_date" class="textbox-70 date" value="<?php echo isset($fields['visit_date']) ? $fields['visit_date'] : ''; ?>" />
			</p>
			<p>
				<label class="field" for="time">Visit time<?php echo in_array('visit_date', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="time" name="time" class="textbox-70" value="<?php echo isset($fields['time']) ? $fields['time'] : ''; ?>" />
				<?php if(key_exists('time', $maxLengths)) echo "<span class='max-len'>(hh:mm:ss)</span>"; ?>
			</p>
			<p>
				<label class="field" for="fk_customer_id">Customer<?php echo in_array('fk_customer_id', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="fk_customer_id" name="fk_customer_id">
					<option value="-1">Select customer</option>
					<?php
						// electing all customers
						$customers = $customersObj->getCustomersList();
						foreach($customers as $key => $val) {
							$selected = "";
							if(isset($fields['fk_customer_id']) && $fields['fk_customer_id'] == $val['personal_id']) {
								$selected = " selected='selected'";
							}
							echo "<option{$selected} value='{$val['personal_id']}'>{$val['name']} {$val['surname']}</option>";
						}
					?>
				</select>
			</p>
			<p>
				<label class="field" for="fk_fitness_club_id">Fitness Club<?php echo in_array('fk_fitness_club_id', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="fk_fitness_club_id" name="fk_fitness_club_id">
					<option value="-1">Select fitness club</option>
					<?php
						// electing all Fitness Clubs
						$gyms = $gymsObj->getGymsList();
						foreach($gyms as $key => $val) {
							$selected = "";
							if(isset($fields['fk_fitness_club_id']) && $fields['fk_fitness_club_id'] == $val['id_fitness_club']) {
								$selected = " selected='selected'";
							}
							echo "<option{$selected} value='{$val['id_fitness_club']}'>{$val['name']}</option>";
						}
					?>
				</select>
			</p>
		</fieldset>
		<p class="required-note">* please, fill in all the blanks</p>
		<p>
			<input type="submit" class="submit" name="submit" value="Save">
		</p>
		<?php if(isset($fields['id_visit'])) { ?>
			<input type="hidden" name="id_visit" value="<?php echo $fields['id_visit']; ?>" />
		<?php } ?>
	</form>
</div>
