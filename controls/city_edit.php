<?php

	include 'libraries/city.class.php';
	$cityObj = new city();

	$formErrors = null;
	$fields = array();

	// nustatome privalomus laukus
	$required = array('city');

	// maksimalūs leidžiami laukų ilgiai
	$maxLengths = array (
		'city' => 20
	);

	// paspaustas išsaugojimo mygtukas
	if(!empty($_POST['submit'])) {
		// nustatome laukų validatorių tipus
		$validations = array (
			'city' => 'words'
		);

		// sukuriame validatoriaus objektą
		include 'utils/validator.class.php';
		$validator = new validator($validations, $required, $maxLengths);

		if($validator->validate($_POST)) {
			// suformuojame laukų reikšmių masyvą SQL užklausai
			$data = $validator->preparePostFieldsForSQL();
			if(isset($data['id_city'])) {
				// atnaujiname duomenis
				$cityObj->updateCity($data);
			} else {
				// randame didžiausią miesto id duomenų bazėje
				$latestId = $cityObj->getMaxIdOfCity();

				// įrašome naują įrašą
				$data['id_city'] = $latestId + 1;
				$cityObj->insertCity($data);
			}

			// nukreipiame į miestų puslapį
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
			Neįvesti arba neteisingai įvesti šie laukai:
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
