<?php

	include 'libraries/city.class.php';
	$cityObj = new city();

	$formErrors = null;
	$fields = array();

	// set required fields array
	$required = array('city');

	// set maximum length for fields
	$maxLengths = array (
		'city' => 20
	);

	// pressed submit button
	if(!empty($_POST['submit'])) {
		// set field validator type
		$validations = array (
			'city' => 'words'
		);

		// creating validator object
		include 'utils/validator.class.php';
		$validator = new validator($validations, $required, $maxLengths);

		if($validator->validate($_POST)) {
			// creating field array of data for SQL query
			$data = $validator->preparePostFieldsForSQL();
			if(isset($data['id_city'])) {
				// updating data
				$cityObj->updateCity($data);
			} else {
				// finding City max id value in Database
				$latestId = $cityObj->getMaxIdOfCity();

				// inserting new record
				$data['id_city'] = $latestId + 1;
				$cityObj->insertCity($data);
			}

			// redirecting to City page
			header("Location: index.php?module={$module}");
			die();
		} else {
			// getting error notification
			$formErrors = $validator->getErrorHTML();
			// getting filled in fields
			$fields = $_POST;
		}
	} else {
		// checking if is selected element id. If yes, getting element data and filled in all fields with that data
		if(!empty($id)) {
			$fields = $cityObj->getCity($id);
		}
	}
?>
<ul id="pagePath">
	<li><a href="index.php">Home Page</a></li>
	<li><a href="index.php?module=<?php echo $module; ?>">City</a></li>
	<li><?php if(!empty($id)) echo "Edit city"; else echo "Add city"; ?></li>
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
			<legend>City information</legend>
			<p>
				<label class="field" for="city">Name<?php echo in_array('city', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="city" name="city" class="textbox-255" value="<?php echo isset($fields['city']) ? $fields['city'] : ''; ?>">
				<?php if(key_exists('city', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['city']} simb.)</span>"; ?>
			</p>
		</fieldset>
		<p class="required-note">* please, fill in all the blanks</p>
		<p>
			<input type="submit" class="submit" name="submit" value="Save">
		</p>
		<?php if(isset($fields['id_city'])) { ?>
			<input type="hidden" name="id_city" value="<?php echo $fields['id_city']; ?>" />
		<?php } ?>
	</form>
</div>
