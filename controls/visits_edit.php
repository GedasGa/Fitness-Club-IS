<?php

	include 'libraries/visits.class.php';
	$visitsObj = new visits();

	// sukuriame klientų klasės objektą
	include 'libraries/customers.class.php';
	$customersObj = new customers();

	// sukuriame sporto klubų klasės objektą
	include 'libraries/gyms.class.php';
	$gymsObj = new gyms();

	$formErrors = null;
	$fields = array();

	// nustatome privalomus formos laukus
	$required = array('visit_date', 'time', 'fk_customer_id', 'fk_fitness_club_id');

	// maksimalūs leidžiami laukų ilgiai
	$maxLengths = array (
		'time' => 8
	);

	// vartotojas paspaudė išsaugojimo mygtuką
	if(!empty($_POST['submit'])) {
		include 'utils/validator.class.php';

		// nustatome laukų validatorių tipus
		$validations = array (
			'visit_date' => 'date',
			'time' => 'alfanum',
			'fk_customer_id' => 'positivenumber',
			'fk_fitness_club_id' => 'positivenumber'
		);

		// sukuriame validatoriaus objektą
		$validator = new validator($validations, $required, $maxLengths);

		// laukai įvesti be klaidų
		if($validator->validate($_POST)) {
			// suformuojame laukų reikšmių masyvą SQL užklausai
			$data = $validator->preparePostFieldsForSQL();
			if(isset($data['id_visit'])) {
				// atnaujiname duomenis
				$visitsObj->updateVisit($data);
			} else {
				// randame didžiausią Visitio id duomenų bazėje
				$latestId = $visitsObj->getMaxIdOfVisit();

				// įrašome naują įrašą
				$data['id_visit'] = $latestId + 1;
				$visitsObj->insertVisit($data);
			}

			// nukreipiame į modelių puslapį
			header("Location: index.php?module={$module}");
			die();
		} else {
			// gauname klaidų pranešimą
			$formErrors = $validator->getErrorHTML();
			// gauname įvestus laukus
			$fields = $_POST;
		}
	} else {
		// tikriname, ar nurodytas elemento id. Jeigu taip, išrenkame elemento duomenis ir jais užpildome formos laukus.
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
			Neįvesti arba neteisingai įvesti šie laukai:
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
						// išrenkame visas markes
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
						// išrenkame visas markes
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
