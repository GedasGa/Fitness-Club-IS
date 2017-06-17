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
		// set field validators types
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
			// getting all information filled into fields
			$fields = $_POST;
		}
	} else {
		// checking if is selected element id. If yes, getting element data and filled in all fields with that data
		if(!empty($id)) {
			$fields = $cityObj->getCity($id);
		}
	}
?>
<ul class="list-inline">
	<li class="list-inline-item"><i class="fa fa-home" aria-hidden="true"></i><a href="index.php"> Home Page</a></li>
	<li class="list-inline-item"><i class="fa fa-angle-right" aria-hidden="true"></i></li>
	<li class="list-inline-item"><a href="index.php?module=<?php echo $module; ?>">Cities</a></li>
	<li class="list-inline-item"><i class="fa fa-angle-right" aria-hidden="true"></i></li>
	<li class="list-inline-item"><?php if(!empty($id)) echo "Edit city"; else echo "Add city"; ?></li>
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
	<form action="" method="post" style="margin: 0 auto;">
		<fieldset>
			<legend class="bg-info" align="center">City information</legend>
			<div class="col-10" style="margin: 0 auto;">
			<div class="form-group">
				<label for="city">Name<?php echo in_array('city', $required) ? '<span> *</span>' : ''; ?></label>
					<div class="col-10">
						<input type="text" id="city" name="city" class="form-control" placeholder="Enter city name" value="<?php echo isset($fields['city']) ? $fields['city'] : ""; ?>">
						<small id="nameHelp" class="form-text text-muted"><?php if(key_exists('city', $maxLengths)) echo "<span class='max-len'>(max {$maxLengths['city']} symb.)</span>"; ?></small>
					</div>
			</div>
			</div>
		</fieldset>
		</br>
		<div class="form-group">
		 <div class="col-sm-10">
			 <input type="submit" class="btn btn-primary" name="submit" value="Save"><small class="text-muted">* please, fill in all the blanks</small>
		 </div>
	 </div>
		<?php if(isset($fields['id_city'])) { ?>
			<input type="hidden" name="id_city" value="<?php echo $fields['id_city']; ?>" />
		<?php } ?>
	</form>
</div>
