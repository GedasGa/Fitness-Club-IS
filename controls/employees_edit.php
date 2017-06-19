<?php
	include 'libraries/employees.class.php';
	$employeesObj = new employees();

	// creating Employee class object
	include 'libraries/gyms.class.php';
	$gymsObj = new gyms();

	$formErrors = null;
	$fields = array();

	// set required fields array
	$required = array('personal_id', 'name', 'surname', 'phone_number', 'email', 'recruitment_date', 'position', 'fk_fitness_club_id');

	// set maximum length for fields
	$maxLengths = array (
		'personal_id' => 11,
		'name' => 20,
		'surname' => 20
	);

	// pressed submit button
	if(!empty($_POST['submit'])) {
		include 'utils/validator.class.php';

		// set field validators types
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

		// creating validator object
		$validator = new validator($validations, $required, $maxLengths);

		// fields entered without mistakes
		if($validator->validate($_POST)) {
			// creating field array of data for SQL query
			$data = $validator->preparePostFieldsForSQL();

			if(isset($data['editing'])) {
				// editing Employee
				$employeesObj->updateEmployee($data);
			} else {
				// inserting new Employee
				$employeesObj->insertEmployee($data);
				//var_dump(mysql::error());
				//var_dump($data);
			}
			//exit();
			// redirecting to Employee page
			header("Location: index.php?module={$module}");
			die();
		}
		else {
			// getting error notification
			$formErrors = $validator->getErrorHTML();

			// getting all information filled into fields
			$fields = $_POST;
		}
	}	else {
		// checking if is selected element id. If yes, getting element data and filled in all fields with that data
		if(!empty($id)) {
			// electing Employee
			$fields = $employeesObj->getEmployee($id);
			$fields['editing'] = 1;
		}
		}

?>
<ul class="list-inline">
	<li class="list-inline-item"><i class="fa fa-home" aria-hidden="true"></i><a href="index.php"> Home Page</a></li>
	<li class="list-inline-item"><i class="fa fa-angle-right" aria-hidden="true"></i></li>
	<li class="list-inline-item"><a href="index.php?module=<?php echo $module; ?>">Employees</a></li>
	<li class="list-inline-item"><i class="fa fa-angle-right" aria-hidden="true"></i></li>
	<li class="list-inline-item"><?php if(!empty($id)) echo "Edit employee"; else echo "Add employee"; ?></li>
</ul>
<div class="float-clear"></div>
<div id="formContainer">
	<?php if($formErrors != null) { ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
		  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    <span aria-hidden="true">&times;</span>
		  </button>
			<strong>Fill in all required fields in right format:</strong>
			<?php
				echo $formErrors;
			?>
		</div>
	<?php } ?>
	<form action="" method="post">
		<fieldset>
			<legend class="bg-info" align="center">Employee information</legend>
			<div class="col-10" style="margin: 0 auto;">
					<label class="field" for="personal_id">Personal ID<?php echo in_array('personal_id', $required) ? '<span> *</span>' : ''; ?></label>
					<div class="col-10">
						<?php if(!isset($fields['editing'])) { ?>
							<input type="number" id="personal_id" name="personal_id" class="form-control" placeholder="Enter personal id" value="<?php echo isset($fields['personal_id']) ? $fields['personal_id'] : ''; ?>" />
							<small id="idHelp" class="form-text text-muted"><?php if(key_exists('personal_id', $maxLengths)) echo "<span class='max-len'>(max {$maxLengths['personal_id']} symb.)</span>"; ?></small>
						<?php } else { ?>
							<input type="hidden" name="editing" value="1" />
							<input type="number" name="personal_id" class="form-control" value="<?php echo $fields['personal_id']; ?>"  disabled/>
						<?php } ?>
					</div>
					<label class="field" for="name">Name<?php echo in_array('name', $required) ? '<span> *</span>' : ''; ?></label>
					<div class="col-10">
						<input type="text" id="name" name="name" class="form-control" placeholder="Enter first name" value="<?php echo isset($fields['name']) ? $fields['name'] : ''; ?>" />
						<small id="nameHelp" class="form-text text-muted"><?php if(key_exists('name', $maxLengths)) echo "<span class='max-len'>(max {$maxLengths['name']} symb.)</span>"; ?></small>
					</div>
					<label class="field" for="surname">Surname<?php echo in_array('surname', $required) ? '<span> *</span>' : ''; ?></label>
					<div class="col-10">
						<input type="text" id="surname" name="surname" class="form-control" placeholder="Enter last name" value="<?php echo isset($fields['surname']) ? $fields['surname'] : ''; ?>" />
						<small id="surnameHelp" class="form-text text-muted"><?php if(key_exists('surname', $maxLengths)) echo "<span class='max-len'>(max {$maxLengths['surname']} symb.)</span>"; ?></small>
					</div>
					<label class="field" for="phone_number">Phone Number<?php echo in_array('phone_number', $required) ? '<span> *</span>' : ''; ?></label>
					<div class="col-10">
						<input type="tel" id="phone_number" name="phone_number" class="form-control" placeholder="Enter phone number" value="<?php echo isset($fields['phone_number']) ? $fields['phone_number'] : ''; ?>" />
					</div>
					<label class="field" for="email">Email<?php echo in_array('email', $required) ? '<span> *</span>' : ''; ?></label>
					<div class="col-10">
						<input type="email" id="email" name="email" class="form-control" placeholder="Enter email address" value="<?php echo isset($fields['email']) ? $fields['email'] : ''; ?>" />
					</div>
					<label class="field" for="recruitment_date">Reqruitment date<?php echo in_array('recruitment_date', $required) ? '<span> *</span>' : ''; ?></label>
					<div class="col-10">
						<input type="date" id="recruitment_date" class="form-control" name="recruitment_date" class="form-control" value="<?php echo isset($fields['recruitment_date']) ? $fields['recruitment_date'] : ''; ?>" />
					</div>
				<div class="col-10">
					<label class="field" for="position">Position<?php echo in_array('position', $required) ? '<span> *</span>' : ''; ?></label>
					<select id="position" class="form-control" name="position">
						<option value="-1">Select position</option>
						<?php
							// electing all categories to generate selection field
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
				</div>
				<div class="col-10">
					<label class="field" for="fk_fitness_club_id">Fitness Club<?php echo in_array('fk_fitness_club_id', $required) ? '<span> *</span>' : ''; ?></label>
					<select id="fk_fitness_club_id" class="form-control" name="fk_fitness_club_id">
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
				</div>
			</div>
		</fieldset>
		</br>
		<div class="form-group">
			<div class="col-10">
				<input type="submit" class="btn btn-primary" name="submit" value="Save"><small class="text-muted">* please, fill in all the blanks</small>
			</div>
		</div>
	</form>
</div>
