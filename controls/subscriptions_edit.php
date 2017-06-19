<?php

	include 'libraries/subscriptions.class.php';
	$subscriptionsObj = new subscriptions();

	// sukuriame klientų klasės objektą
	include 'libraries/customers.class.php';
	$customersObj = new customers();



	$formErrors = null;
	$fields = array();

	// set required fields array
	$required = array('valid_from', 'valid_till', 'price', 'type', 'fk_customer_id');

	// pressed submit button
	if(!empty($_POST['submit'])) {
	    $NewTime = $_POST['NewTime'];
	    $newTimeArray = [];
	    foreach ($NewTime as $key => $n) {
	        foreach ($n as $key2 => $m) {
	            $newTimeArray[$key2][$key] = $m;
	        }
	    }

		// set field validators types
		$validations = array (
			'valid_from' => 'date',
			'valid_till' => 'date',
			'price' => 'price',
			'type' => 'positivenumber',
			'fk_customer_id' => 'alfanum'
		);

		include 'utils/validator.class.php';
		// creating validator object
		$validator = new validator($validations, $required);

		// fields entered without mistakes
		if($validator->validate($_POST)) {
			// creating field array of data for SQL query
			$data = $validator->preparePostFieldsForSQL();
			if(isset($data['id_subscription'])) {
				// atnaujiname duomenis
				$subscriptionsObj->updateSubscription($data);

				foreach ($newTimeArray as $n) {
                	$subscriptionsObj->insertScheduleHours($n, $data['id_subscription']);
            	}

			} else {
				// finding max id value of Subscription in database
				$latestId = $subscriptionsObj->getMaxIdOfSubscription();

				// inserting new Subscription
				$data['id_subscription'] = $latestId + 1;
				$subscriptionsObj->insertSubscription($data);

				foreach ($newTimeArray as $n) {
                	$subscriptionsObj->insertScheduleHours($n, $data['id_subscription']);
            	}
			}
			// redirecting to Subscriptions page
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
		            $subscriptionsObj->deleteScheduleHours($sid);
		            header("Location: index.php?module={$module}&id={$id}");
		            die();
		            break;
		    }
	} else {
		// checking if is selected element id. If yes, getting element data and filled in all fields with that data
		if(!empty($id)) {
			$fields = $subscriptionsObj->getSubscription($id);
	        $hoursFields = $subscriptionsObj->getScheduleHours($id);
	    }
	}
?>
<ul class="list-inline">
	<li class="list-inline-item"><i class="fa fa-home" aria-hidden="true"></i><a href="index.php"> Home Page</a></li>
	<li class="list-inline-item"><i class="fa fa-angle-right" aria-hidden="true"></i></li>
	<li class="list-inline-item"><a href="index.php?module=<?php echo $module; ?>">Subscriptions</a></li>
	<li class="list-inline-item"><i class="fa fa-angle-right" aria-hidden="true"></i></li>
	<li class="list-inline-item"><?php if(!empty($id)) echo "Edit subscription"; else echo "Add subscription"; ?></li>
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
			<legend class="bg-info" align="center">Subscription information</legend>
			<div class="col-10" style="margin: 0 auto;">
			<div class="form-group">
				<label class="field" for="valid_from">Valid from<?php echo in_array('valid_from', $required) ? '<span> *</span>' : ''; ?></label>
				<div class="col-10">
					<input type="date" id="valid_from" name="valid_from" class="form-control" value="<?php echo isset($fields['valid_from']) ? $fields['valid_from'] : ''; ?>" />
				</div>
				<label class="field" for="valid_till">Valid till<?php echo in_array('valid_till', $required) ? '<span> *</span>' : ''; ?></label>
				<div class="col-10">
					<input type="ate" id="valid_till" name="valid_till" class="form-control" value="<?php echo isset($fields['valid_till']) ? $fields['valid_till'] : ''; ?>" />
				</div>
				<label class="field" for="price">Subsription price<?php echo in_array('price', $required) ? '<span> *</span>' : ''; ?></label>
				<div class="input-group col-10">
					<input type="number" step="0.01" id="price" name="price" class="form-control" placeholder="Enter subscription price" value="<?php echo isset($fields['price']) ? $fields['price'] : ''; ?>">
					<span class="input-group-addon">$</span>
				</div>
				<label class="field" for="type">Type<?php echo in_array('type', $required) ? '<span> *</span>' : ''; ?></label>
				<div class="col-10">
					<select id="type"  class="form-control" name="type">
						<option value="-1">Select subscription type</option>
						<?php
							// electing all Subscriptions
							$PositionsTypes = $subscriptionsObj->getTipasList();
							foreach($PositionsTypes as $key => $val) {
								$selected = "";
								if(isset($fields['type']) && $fields['type'] == $val['id_type']) {
									$selected = " selected='selected'";
								}
								echo "<option{$selected} value='{$val['id_type']}'>{$val['name']}</option>";
							}
						?>
					</select>
				</div>
				<label class="field" for="fk_customer_id">Customer<?php echo in_array('fk_customer_id', $required) ? '<span> *</span>' : ''; ?></label>
				<div class="col-10">
					<select id="fk_customer_id" class="form-control" name="fk_customer_id">
						<option value="-1">Select a Customer</option>
						<?php
							// išrenkame visas markes
							$cutomers = $customersObj->getCustomersList();
							foreach($cutomers as $key => $val) {
								$selected = "";
								if(isset($fields['fk_customer_id']) && $fields['fk_customer_id'] == $val['personal_id']) {
									$selected = " selected='selected'";
								}
								echo "<option{$selected} value='{$val['personal_id']}'>{$val['name']} {$val['surname']}</option>";
							}
						?>
					</select>
				</div>
			</div>
		</div>
		</fieldset>
		<fieldset>
			<legend class="bg-info" align="center">Entrance hours</legend>
			<div class="col-10" style="margin: 0 auto;">
			<div class="childRowContainer">
				<div class="row col-10">
					<div class="col-4<?php if(empty($hoursFields) || sizeof($hoursFields) == 0) echo ' hidden'; ?>">Weekday</div>
					<div class="col-3<?php if(empty($hoursFields) || sizeof($hoursFields) == 0) echo ' hidden'; ?>">Enter from</div>
					<div class="col-3<?php if(empty($hoursFields) || sizeof($hoursFields) == 0) echo ' hidden'; ?>">Leave till</div>
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
																				<a href="<?php echo "index.php?module={$module}&id={$id}&sid={$val['id_entrance_time']}&action=remove"; ?>" class="btn btn-danger removeChild">delete</a>
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
				<input type="submit" class="btn btn-primary" name="submit" value="Save"><small class="muted-text">* please, fill in all the blanks</p>
			</div>
		</div>
		<?php if(isset($fields['id_subscription'])) { ?>
			<input type="hidden" name="id_subscription" value="<?php echo $fields['id_subscription']; ?>" />
		<?php } ?>
		<?php if(isset($hoursFields['id_entrace_time'])) { ?>
			<input type="hidden" name="id_entrace_time" value="<?php echo $hoursFields['id_entrace_time']; ?>" />
		<?php } ?>
	</form>
</div>
</div>
