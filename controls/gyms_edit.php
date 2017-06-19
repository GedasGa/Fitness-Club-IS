<?php

	include 'libraries/gyms.class.php';
	$gymsObj = new gyms();


	include 'libraries/city.class.php';
	$cityObj = new city();


	$formErrors = null;
	$fields = array();

	// set required fields array
	$required = array('name', 'features', 'fk_address_id', 'street', 'house_number', 'post_code', 'fk_city_id', 'city');

	// set maximum length for fields
	$maxLengths = array (
		'post_code' => 6
	);
	// pressed submit button
	if (!empty($_POST['submit'])) {
	    $NewTime = $_POST['NewTime'];
	    $newTimeArray = [];
	    foreach ($NewTime as $key => $n) {
	        foreach ($n as $key2 => $m) {
	            $newTimeArray[$key2][$key] = $m;
	        }
	    }


		// set field validators types
		$validations = array (
			'name' => 'alfanum',
			'features' => 'alfanum',
			'fk_address_id' => 'positivenumber',
			'street' => 'alfanum',
			'house_number' => 'alfanum',
			'post_code' => 'positivenumber',
			'fk_city_id' => 'positivenumber',
			'city' => 'words'
			);

		include 'utils/validator.class.php';
		// creating validator object
		$validator = new validator($validations, $required, $maxLengths);

		// fields entered without mistakes
		if($validator->validate($_POST)) {
			// creating field array of data for SQL query
			$data = $validator->preparePostFieldsForSQL();
			if(isset($data['id_fitness_club'])) {
				// updating Fitness Club
				$gymsObj->updateGym($data);

				if(isset($data['id_address'])){
					$gymsObj->updateAddress($data);
				}

				foreach ($newTimeArray as $n) {
                	$gymsObj->insertScheduleHours($n, $data['id_fitness_club']);
            	}

			} else {
				// finding max id value of Fitness Club in database
				$latestGymId = $gymsObj->getMaxIdOfGym();
				$latestAddressId = $gymsObj->getMaxIdOfAddress();

				// inserting new Fitness Club
				$data['id_fitness_club'] = $latestGymId + 1;
				$data['fk_address_id'] = $latestAddressId+1;
				$data['id_address'] = $latestAddressId+1;
				$gymsObj->insertAddress($data);
				$gymsObj->insertGym($data);

				foreach ($newTimeArray as $n) {
                	$gymsObj->insertScheduleHours($n, $data['id_fitness_club']);
            	}

			}
			// redirecting to Fitness Clubs page
			header("Location: index.php?module={$module}");
			die();
		} else {
			// getting error notification
			$formErrors = $validator->getErrorHTML();
			// getting all information filled into fields
			$fields = $_POST;
		}
		} elseif (!empty($_GET['action']) && !empty($_GET['sid']) && is_numeric($_GET['sid']) && !is_float($_GET['sid'])) {
		    switch ($_GET['action']) {
		        case 'remove':
		            $sid = $_GET['sid'];
		            $gymsObj->deleteScheduleHours($sid);
		            header("Location: index.php?module={$module}&id={$id}");
		            die();
		            break;
		    }
		} else {
		// checking if is selected element id. If yes, getting element data and filled in all fields with that data
			if(!empty($id)) {
				$fields = $gymsObj->getGym($id);
				$fieldsA = $gymsObj->getAddress($id);
	        	$hoursFields = $gymsObj->getScheduleHours($id);
			}
		}
?>

<ul class="list-inline">
	<li class="list-inline-item"><i class="fa fa-home" aria-hidden="true"></i><a href="index.php"> Home Page</a></li>
	<li class="list-inline-item"><i class="fa fa-angle-right" aria-hidden="true"></i></li>
	<li class="list-inline-item"><a href="index.php?module=<?php echo $module; ?>">Fitness Clubs</a></li>
	<li class="list-inline-item"><i class="fa fa-angle-right" aria-hidden="true"></i></li>
	<li class="list-inline-item"><?php if(!empty($id)) echo "Edit Fitness Club"; else echo "Add Fitness Club"; ?></li>
</ul>
<div class="float-clear"></div>
<div id="formContainer">
	<?php if($formErrors != null) { ?>
		<div class="errorBox">
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
			<legend class="bg-info" align="center">Fitness Clubs information</legend>
			<div class="col-10" style="margin: 0 auto;">
			<div class="form-group">
					<label class="field" for="name">Name<?php echo in_array('name', $required) ? '<span> *</span>' : ''; ?></label>
					<div class="col-10">
						<input type="text" id="name" name="name" class="form-control" placeholder="Enter fitness club name" value="<?php echo isset($fields['name']) ? $fields['name'] : ''; ?>">
					</div>
					<label class="field" for="features">Features<?php echo in_array('features', $required) ? '<span> *</span>' : ''; ?></label>
					<div class="col-10">
						<input type="text" id="features" name="features" placeholder="Enter fitness club features" class="form-control" value="<?php echo isset($fields['features']) ? $fields['features'] : ''; ?>">
					</div>
			</div>
		</div>
		</fieldset>
		<fieldset>
				<legend class="bg-info" align="center">Address</legend>
				<div class="col-10" style="margin: 0 auto;">
				<div class="form-group">
					<label class="field" for="house_number">House number<?php echo in_array('house_number', $required) ? '<span> *</span>' : ''; ?></label>
					<div class="col-10">
						<input type="text" id="house_number" name="house_number" class="form-control" placeholder="Enter house number" value="<?php echo isset($fieldsA['house_number']) ? $fieldsA['house_number'] : ''; ?>">
					</div>
					<label class="field" for="street">Street<?php echo in_array('street', $required) ? '<span> *</span>' : ''; ?></label>
					<div class="col-10">
							<input type="text" id="street" name="street" class="form-control" placeholder="Enter street name" value="<?php echo isset($fieldsA['street']) ? $fieldsA['street'] : ''; ?>">
					</div>
						<label class="field" for="post_code">Post code<?php echo in_array('post_code', $required) ? '<span> *</span>' : ''; ?></label>
					<div class="col-10">
						<input type="number" id="post_code" name="post_code" placeholder="Enter post code" class="form-control"value="<?php echo isset($fieldsA['post_code']) ? $fieldsA['post_code'] : ''; ?>">
						<small id="nameHelp" class="form-text text-muted"><?php if(key_exists('post_code', $maxLengths)) echo "<span class='max-len'>(max {$maxLengths['post_code']} symb.)</span>"; ?></small>
					</div>
				</div>
				</div>
		</fieldset>
		<fieldset>
			<legend class="bg-info" align="center">City</legend>
			<div class="col-10" style="margin: 0 auto;">
			<div class="form-group">
				<label class="field" for="fk_city_id">City<?php echo in_array('fk_city_id', $required) ? '<span> *</span>' : ''; ?></label>
				<div class="col-10">
					<select class="form-control" id="fk_city_id" name="fk_city_id">
						<option value="-1">Choose a City</option>
						<?php
							// electing all Cities
							$citys = $cityObj->getCityList();
							foreach($citys as $key => $val) {
								$selected = "";
								if(isset($fieldsA['fk_city_id']) && $fieldsA['fk_city_id'] == $val['id_city']) {
									$selected = " selected='selected'";
								}
								echo "<option{$selected} value='{$val['id_city']}'>{$val['city']}</option>";
							}
						?>
					</select>
				</div>
			</div>
			</div>
		</fieldset>
		<fieldset>
			<legend class="bg-info" align="center">Working Hours</legend>
			<div class="col-10" style="margin: 0 auto;">
			<div class="childRowContainer">
				<div class="row col-10">
					<div class="col-4<?php if(empty($hoursFields) || sizeof($hoursFields) == 0) echo ' hidden'; ?>">Weekday</div>
					<div class="col-3<?php if(empty($hoursFields) || sizeof($hoursFields) == 0) echo ' hidden'; ?>">Open from</div>
					<div class="col-3<?php if(empty($hoursFields) || sizeof($hoursFields) == 0) echo ' hidden'; ?>">Open till</div>
				</div>
				<?php
					if(empty($hoursFields) || sizeof($hoursFields) == 0) {
				?>

					<div class="childRow row col-10 hidden">
						<input type="text" class="col-4 form-control" name="NewTime[weekday][]" value="" class="textbox-100" disabled="disabled" />
						<input type="text" class="col-3 form-control" name="NewTime[from][]" value="" class="textbox-100" disabled="disabled" />
						<input type="text" class="col-3 form-control" name="NewTime[till][]" value="" class="textbox-100" disabled="disabled" />
						<input type="hidden" class="isDisabledForEditing" name="neaktyvus[]" value="0" />

					</div>
					<div class="float-clear"></div>

				<?php
					} else {
						foreach($hoursFields as $key => $val) {

				?>
                                    <div class="childRow row col-10">
                                        <input type="text" class="col-4 form-control" name="NewTime[weekday][]" value="<?php echo $val['weekday']; ?>" class="textbox-100" disabled/>
                                        <input type="text" class="col-3 form-control" name="NewTime[from][]" value="<?php echo $val['from']; ?>" class="textbox-100" disabled/>
                                        <input type="text" class="col-3 form-control" name="NewTime[till][]" value="<?php echo $val['till']; ?>" class="textbox-100" disabled/>
                                        <a href="<?php echo "index.php?module={$module}&id={$id}&sid={$val['id_working_hours']}&action=remove"; ?>" class="btn btn-danger removeChild">delete</a>
                                    </div>
                                    <div class="float-clear"></div>
				<?php
						}
					}
				?>
			</div>
			<div class="col-10" id="newItemButtonContainer">
				<a href="#" title="" class="btn btn-success addChild">Add</a>
			</div>
		</fieldset>
		</br>
		<div class="form-group">
			<div class="col-10">
				<input type="submit" class="btn btn-primary" name="submit" value="Save"><small class="text-muted">* please, fill in all the blanks</small>
			</div>
		</div>
		<?php if(isset($fields['id_fitness_club'])) { ?>
			<input type="hidden" name="id_fitness_club" value="<?php echo $fields['id_fitness_club']; ?>" />
		<?php } ?>
		<?php if(isset($fieldsA['id_address'])) { ?>
			<input type="hidden" name="id_address" value="<?php echo $fieldsA['id_address']; ?>" />
		<?php } ?>
		<?php if(isset($hoursFields['id_working_hours'])) { ?>
			<input type="hidden" name="id_working_hours" value="<?php echo $hoursFields['id_working_hours']; ?>" />
		<?php } ?>

	</form>
</div>
</div>
