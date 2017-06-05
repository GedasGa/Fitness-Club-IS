<?php
	include 'libraries/employees.class.php';
	$employeesObj = new employees();

	// sukuriame sporto klubų klasės objektą
	include 'libraries/gyms.class.php';
	$gymsObj = new gyms();

	$formErrors = null;
	$fields = array();

	// nustatome privalomus formos laukus
	$required = array('personal_id', 'name', 'surname', 'phone_number', 'email', 'recruitment_date', 'position', 'fk_fitness_club_id');

	// maksimalūs leidžiami laukų ilgiai
	$maxLengths = array (
		'personal_id' => 11,
		'name' => 20,
		'surname' => 20
	);

	// vartotojas paspaudė išsaugojimo mygtuką
	if(!empty($_POST['submit'])) {
		include 'utils/validator.class.php';

		// nustatome laukų validatorių tipus
		$validations = array (
			'personal_id' => 'positivenumber',
			'name' => 'alfanum',
			'surname' => 'alfanum',
			'phone_number' => 'phone',
			'email' => 'alfanum',
			'recruitment_date' => 'date',
			'position' => 'positivenumber',
			'fk_fitness_club_id' => 'positivenumber'
		);

		// sukuriame laukų validatoriaus objektą
		$validator = new validator($validations, $required, $maxLengths);

		// laukai įvesti be klaidų
		if($validator->validate($_POST)) {
			// suformuojame laukų reikšmių masyvą SQL užklausai
			$data = $validator->preparePostFieldsForSQL();

			if(isset($data['editing'])) {
				// redaguojame darbuotoją
				$employeesObj->updateEmployee($data);
			} else {
				// įrašome naują darbuotoją
				$employeesObj->insertEmployee($data);
				//var_dump(mysql::error());
				//var_dump($data);
			}
			//exit();
			// nukreipiame vartotoją į klientų puslapį
			header("Location: index.php?module={$module}");
			die();
		}
		else {
			// gauname klaidų pranešimą
			$formErrors = $validator->getErrorHTML();

			// laukų reikšmių kintamajam priskiriame įvestų laukų reikšmes
			$fields = $_POST;
		}
	}	else {
		// tikriname, ar nurodytas elemento id. Jeigu taip, išrenkame elemento duomenis ir jais užpildome formos laukus.
		if(!empty($id)) {
			// išrenkame darbuotoją
			$fields = $employeesObj->getEmployee($id);
			$fields['editing'] = 1;
		}
		}

?>
<ul id="pagePath">
	<li><a href="index.php">Home Page</a></li>
	<li><a href="index.php?module=<?php echo $module; ?>">Employees</a></li>
	<li><?php if(!empty($id)) echo "Edit employee"; else echo "Add employee"; ?></li>
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
			<legend>Employee information</legend>
				<p>
					<label class="field" for="personal_id">Personal ID<?php echo in_array('personal_id', $required) ? '<span> *</span>' : ''; ?></label>
					<?php if(!isset($fields['editing'])) { ?>
						<input type="text" id="personal_id" name="personal_id" class="textbox-150" value="<?php echo isset($fields['personal_id']) ? $fields['personal_id'] : ''; ?>" />
						<?php if(key_exists('personal_id', $maxLengths)) echo "<span class='max-len'>(max {$maxLengths['personal_id']} symb.)</span>"; ?>
					<?php } else { ?>
						<span class="input-value"><?php echo $fields['personal_id']; ?></span>
						<input type="hidden" name="editing" value="1" />
						<input type="hidden" name="personal_id" value="<?php echo $fields['personal_id']; ?>" />
					<?php } ?>
				</p>
				<p>
					<label class="field" for="name">Name<?php echo in_array('name', $required) ? '<span> *</span>' : ''; ?></label>
					<input type="text" id="name" name="name" class="textbox-150" value="<?php echo isset($fields['name']) ? $fields['name'] : ''; ?>" />
					<?php if(key_exists('name', $maxLengths)) echo "<span class='max-len'>(max {$maxLengths['name']} symb.)</span>"; ?>
				</p>
				<p>
					<label class="field" for="surname">Surname<?php echo in_array('surname', $required) ? '<span> *</span>' : ''; ?></label>
					<input type="text" id="surname" name="surname" class="textbox-150" value="<?php echo isset($fields['surname']) ? $fields['surname'] : ''; ?>" />
					<?php if(key_exists('surname', $maxLengths)) echo "<span class='max-len'>(max {$maxLengths['surname']} symb.)</span>"; ?>
				</p>
				<p>
					<label class="field" for="phone_number">Phone number<?php echo in_array('phone_number', $required) ? '<span> *</span>' : ''; ?></label>
					<input type="text" id="phone_number" name="phone_number" class="textbox-100" value="<?php echo isset($fields['phone_number']) ? $fields['phone_number'] : ''; ?>" />
				</p>
				<p>
					<label class="field" for="email">Email<?php echo in_array('email', $required) ? '<span> *</span>' : ''; ?></label>
					<input type="text" id="email" name="email" class="textbox-150" value="<?php echo isset($fields['email']) ? $fields['email'] : ''; ?>" />
				</p>
				<p>
					<label class="field" for="recruitment_date">Reqruitment date<?php echo in_array('recruitment_date', $required) ? '<span> *</span>' : ''; ?></label>
					<input type="text" id="recruitment_date" name="recruitment_date" class="textbox-70 date" value="<?php echo isset($fields['recruitment_date']) ? $fields['recruitment_date'] : ''; ?>" />
				</p>
				<p>
					<label class="field" for="position">Position<?php echo in_array('position', $required) ? '<span> *</span>' : ''; ?></label>
					<select id="position" name="position">
						<option value="-1">Select position</option>
						<?php
							// išrenkame visas kategorijas sugeneruoti pasirinkimų lauką
							$PareigosTypes = $employeesObj->getPositionList();
							foreach($PareigosTypes as $key => $val) {
								$selected = "";
								if(isset($fields['position']) && $fields['position'] == $val['id_position']) {
									$selected = " selected='selected'";
								}
								echo "<option{$selected} value='{$val['id_position']}'>{$val['name']}</option>";
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
	</form>
</div>
