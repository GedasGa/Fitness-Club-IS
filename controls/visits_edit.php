<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="../../favicon.ico">

		<!-- Bootstrap core CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css"
		integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="utils/style/navbar.css" rel="stylesheet">
	</head>

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
<ul class="list-inline">
	<li class="list-inline-item"><i class="fa fa-home" aria-hidden="true"></i><a href="index.php"> Home Page</a></li>
	<li class="list-inline-item"><i class="fa fa-angle-right" aria-hidden="true"></i></li>
	<li class="list-inline-item"><a href="index.php?module=<?php echo $module; ?>">Visits</a></li>
	<li class="list-inline-item"><i class="fa fa-angle-right" aria-hidden="true"></i></li>
	<li class="list-inline-item"><?php if(!empty($id)) echo "Edit visit"; else echo "Add visit"; ?></li>
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
			<legend class="bg-info" align="center">Visit information</legend>
			<div class="col-10" style="margin: 0 auto;">
				<label class="field" for="visit_date">Visit date<?php echo in_array('visit_date', $required) ? '<span> *</span>' : ''; ?></label>
				<div class="col-10">
					<input type="date" id="visit_date" name="visit_date" class="form-control" value="<?php echo isset($fields['visit_date']) ? $fields['visit_date'] : ''; ?>" />
				</div>
				<label class="field" for="time">Visit time<?php echo in_array('visit_date', $required) ? '<span> *</span>' : ''; ?></label>
				<div class="col-10">
					<input type="time" id="time" name="time" class="form-control" value="<?php echo isset($fields['time']) ? $fields['time'] : ''; ?>" />
				<small id="nameHelp" class="form-text text-muted"><?php if(key_exists('time', $maxLengths)) echo "<span class='max-len'>(hh:mm:ss)</span>"; ?></small>
				</div>
				<label class="field" for="fk_customer_id">Customer<?php echo in_array('fk_customer_id', $required) ? '<span> *</span>' : ''; ?></label>
				<div class="col-10">
					<select id="fk_customer_id" class="form-control" name="fk_customer_id">
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
				</div>
				<label class="field" for="fk_fitness_club_id">Fitness Club<?php echo in_array('fk_fitness_club_id', $required) ? '<span> *</span>' : ''; ?></label>
				<div class="col-10">
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
				<input type="submit" class="btn btn-primary" name="submit" value="Save"><small class="text-muted">* please, fill in all the blanks</p>
			</div>
		</div>
		<?php if(isset($fields['id_visit'])) { ?>
			<input type="hidden" name="id_visit" value="<?php echo $fields['id_visit']; ?>" />
		<?php } ?>
	</form>
</div>
