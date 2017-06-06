<?php

	include 'libraries/customers.class.php';
	$customersObj = new customers();

	$formErrors = null;
	$fields = array();

	// set required fields
	$required = array('personal_id', 'name', 'surname', 'phone_number', 'email', 'first_registration', 'social_status');

	// set maximum length for fields
	$maxLengths = array (
		'personal_id' => 11,
		'name' => 20,
		'surname' => 20
	);

	// pressed submit button
	if(!empty($_POST['submit'])) {
		include 'utils/validator.class.php';

		// set field validator type
		$validations = array (
			'personal_id' => 'positivenumber',
			'name' => 'alfanum',
			'surname' => 'alfanum',
			'phone_number' => 'phone',
			'email' => 'alfanum',
			'first_registration' => 'date',
			'social_status' => 'positivenumber'
		);

		// creating validator object
		$validator = new validator($validations, $required, $maxLengths);

		// fields entered without mistakes
		if($validator->validate($_POST)) {
			// creating field array of data for SQL query
			$data = $validator->preparePostFieldsForSQL();

			if(isset($data['editing'])) {
				// updating Customer
				$customersObj->updateCustomer($data);
			} else {
				// inserting new Customer
				$customersObj->insertCustomer($data);
			}

			// redirecting to Customer page
			header("Location: index.php?module={$module}");
			die();
		}
		else {
			// getting error notification
			$formErrors = $validator->getErrorHTML();

			// getting filled insterted information into fields
			$fields = $_POST;
		}
	}	else {
		// checking if is selected element id. If yes, getting element data and filled in all fields with that data
		if(!empty($id)) {
			// electing Customer
			$fields = $customersObj->getCustomer($id);
			$fields['editing'] = 1;
		}
		}

?>
<ul id="pagePath">
	<li><a href="index.php">Home Page</a></li>
	<li><a href="index.php?module=<?php echo $module; ?>">Customers</a></li>
	<li><?php if(!empty($id)) echo "Edit customer"; else echo "Add customer"; ?></li>
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
			<legend>customer information</legend>
				<p>
					<label class="field" for="personal_id">Personal ID<?php echo in_array('personal_id', $required) ? '<span> *</span>' : ''; ?></label>
					<?php if(!isset($fields['editing'])) { ?>
						<input type="text" id="personal_id" name="personal_id" class="textbox-150" value="<?php echo isset($fields['personal_id']) ? $fields['personal_id'] : ''; ?>" />
						<?php if(key_exists('personal_id', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['personal_id']} simb.)</span>"; ?>
					<?php } else { ?>
						<span class="input-value"><?php echo $fields['personal_id']; ?></span>
						<input type="hidden" name="editing" value="1" />
						<input type="hidden" name="personal_id" value="<?php echo $fields['personal_id']; ?>" />
					<?php } ?>
				</p>
				<p>
					<label class="field" for="name">Name<?php echo in_array('name', $required) ? '<span> *</span>' : ''; ?></label>
					<input type="text" id="name" name="name" class="textbox-150" value="<?php echo isset($fields['name']) ? $fields['name'] : ''; ?>" />
					<?php if(key_exists('name', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['name']} simb.)</span>"; ?>
				</p>
				<p>
					<label class="field" for="surname">Surname<?php echo in_array('surname', $required) ? '<span> *</span>' : ''; ?></label>
					<input type="text" id="surname" name="surname" class="textbox-150" value="<?php echo isset($fields['surname']) ? $fields['surname'] : ''; ?>" />
					<?php if(key_exists('surname', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['surname']} simb.)</span>"; ?>
				</p>
				<p>
					<label class="field" for="phone_number">Phone Number.<?php echo in_array('phone_number', $required) ? '<span> *</span>' : ''; ?></label>
					<input type="text" id="phone_number" name="phone_number" class="textbox-100" value="<?php echo isset($fields['phone_number']) ? $fields['phone_number'] : ''; ?>" />
				</p>
				<p>
					<label class="field" for="email">Email<?php echo in_array('email', $required) ? '<span> *</span>' : ''; ?></label>
					<input type="text" id="email" name="email" class="textbox-150" value="<?php echo isset($fields['email']) ? $fields['email'] : ''; ?>" />
				</p>
				<p>
					<label class="field" for="first_registration">First registration date<?php echo in_array('first_registration', $required) ? '<span> *</span>' : ''; ?></label>
					<input type="text" id="first_registration" name="first_registration" class="textbox-70 date" value="<?php echo isset($fields['first_registration']) ? $fields['first_registration'] : ''; ?>" />
				</p>
				<p>
					<label class="field" for="social_status">Social status<?php echo in_array('social_status', $required) ? '<span> *</span>' : ''; ?></label>
					<select id="social_status" name="social_status">
						<option value="-1">Select social status</option>
						<?php
							// electint all categories to generate selection field
							$SocStausasTypes = $customersObj->getSocialStatusesList();
							foreach($SocStausasTypes as $key => $val) {
								$selected = "";
								if(isset($fields['social_status']) && $fields['social_status'] == $val['id_social_status']) {
									$selected = " selected='selected'";
								}
								echo "<option{$selected} value='{$val['id_social_status']}'>{$val['name']}</option>";
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
