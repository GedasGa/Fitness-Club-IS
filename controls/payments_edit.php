<?php

	include 'libraries/payments.class.php';
	$paymentsObj = new payments();

	include 'libraries/employees.class.php';
	$employeesObj = new employees();

	include 'libraries/customers.class.php';
	$customersObj = new customers();

	include 'libraries/subscriptions.class.php';
	$subscriptionsObj = new subscriptions();

	$formErrors = null;
	$fields = array();

	// set required fields array
	$required = array('number', 'invoice_date', 'invoice_amount', 'fk_subscription_id', 'fk_employee_id', 'payment_date', 'amount', 'fk_customer_id');

	// pressed submit button
	if(!empty($_POST['submit'])) {
	    $NewPayment = $_POST['NewPayment'];
	    $newPayment = [];
			echo($NewPayment);
	    foreach ($NewPayment as $key => $n) {
	        foreach ($n as $key2 => $m) {
	            $newPayment[$key2][$key] = $m;
	        }
	    }

		// set field validators types
		$validations = array (
			'number' => 'positivenumber',
			'invoice_date' => 'date',
			'invoice_amount' => 'price',
			'fk_subscription_id' => 'positivenumber',
			'fk_employee_id' => 'positivenumber',
			'payment_date' => 'date',
			'amount' => 'price',
			'fk_customer_id' => 'positivenumber'
		);

		include 'utils/validator.class.php';
		// creating validator object
		$validator = new validator($validations, $required);

		// fields entered without mistakes
		if($validator->validate($_POST)) {
			// creating field array of data for SQL query
			$data = $validator->preparePostFieldsForSQL();

			if(isset($data['editing'])) {
				if(isset($data['number'])) {
					// updating Payment
					$paymentsObj->updateAccount($data);
					foreach ($newPayment as $n) {
	                	$paymentsObj->insertAccountPayment($n, $data['number'], $data['fk_customer_id'] );
	                	//var_dump(mysql::error());

	            	}
	         	}

			} else {
				// checking if there is no invoice with the same id
				$tmp = $paymentsObj->getAccount($data['']);

				if(isset($tmp['number'])) {
					// creating error notification
					$formErrors = "Invoice with this number already exist";
					// getting filled insterted information into fields
					$fields = $_POST;
				} else {
					// inserting new Invoice
					$paymentsObj->insertAccount($data);

					foreach ($newPayment as $n) {
                		$paymentsObj->insertAccountPayment($n, $data['number'], $data['fk_customer_id']);
					}
				}

			}

			// redirecting to Payments page
			if($formErrors == null) {
				header("Location: index.php?module={$module}");
				die();
			}
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
		            $paymentsObj->deleteAccountPayment($sid);
		            header("Location: index.php?module={$module}&id={$id}");
		            die();
		            break;
		    }
	} else {
		// checking if selected element id. If yes, electing all element data from database and filled in all forms with data
		if(!empty($id)) {
			$fieldsA = $paymentsObj->getAccount($id);
			$AccountPayment = $paymentsObj->getAccountPayment($id);
			$fields['editing'] = 1;
		}
	}

?>
<ul class="list-inline">
	<li class="list-inline-item"><i class="fa fa-home" aria-hidden="true"></i><a href="index.php"> Home Page</a></li>
	<li class="list-inline-item"><i class="fa fa-angle-right" aria-hidden="true"></i></li>
	<li class="list-inline-item"><a href="index.php?module=<?php echo $module; ?>">Payments</a></li>
	<li class="list-inline-item"><i class="fa fa-angle-right" aria-hidden="true"></i></li>
	<li class="list-inline-item"><?php if(!empty($id)) echo "Edit invoice"; else echo "Add invoice"; ?></li>
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
			<legend class="bg-info" align="center">Invoice information</legend>
			<div class="col-10" style="margin: 0 auto;">
			<div class="form-group">
				<div class="col-10">
					<label class="field" for="number">Number<?php echo in_array('number', $required) ? '<span> *</span>' : ''; ?></label>
					<?php if(!isset($fields['editing'])) { ?>
						<input type="text" id="number" name="number" class="form-control" placeholder="Enter invoice number" value="<?php echo isset($fieldsA['number']) ? $fieldsA['number'] : ''; ?>">
					<?php } else { ?>
							<input type="hidden" name="editing" value="1" />
							<input type="number" name="number" class="form-control" value="<?php echo $fieldsA['number']; ?>" disabled/>
					<?php } ?>
				</div>
				<label class="field" for="invoice_date">Invoice Date<?php echo in_array('invoice_date', $required) ? '<span> *</span>' : ''; ?></label>
				<div class="col-10">
					<input type="date" id="invoice_date" name="invoice_date" class="form-control" value="<?php echo isset($fieldsA['invoice_date']) ? $fieldsA['invoice_date'] : ''; ?>">
				</div>
				<label class="field" for="invoice_amount">Amount<?php echo in_array('invoice_amount', $required) ? '<span> *</span>' : ''; ?></label>
				<div class="input-group col-10">
					<input type="number" step="0.01" id="invoice_amount" name="invoice_amount" class="form-control" placeholder="Enter price" value="<?php echo isset($fieldsA['invoice_amount']) ? $fieldsA['invoice_amount'] : ''; ?>">
					<span class="input-group-addon">$</span>
				</div>
				<label class="field" for="fk_employee_id">Employee<?php echo in_array('fk_employee_id', $required) ? '<span> *</span>' : ''; ?></label>
				<div class="col-10">
					<select id="fk_employee_id" class="form-control" name="fk_employee_id">
						<option value="">Select employee</option>
						<?php
							// electing all Employees
							$data = $employeesObj->getEmployeesList();
							foreach($data as $key => $val) {
								$selected = "";
								if(isset($fieldsA['fk_employee_id']) && $fieldsA['fk_employee_id'] == $val['personal_id']) {
									$selected = " selected='selected'";
								}
								echo "<option{$selected} value='{$val['personal_id']}'>{$val['name']} {$val['surname']}</option>";
							}
						?>
					</select>
				</div>
				<label class="field" for="fk_customer_id">Customer<?php echo in_array('fk_customer_id', $required) ? '<span> *</span>' : ''; ?></label>
				<div class="col-10">
					<select id="fk_customer_id" class="form-control" name="fk_customer_id">
						<option value="">Select customer</option>
						<?php
							// selecting all Customers
							$data = $customersObj->getCustomersList();
							foreach($data as $key => $val) {
								$selected = "";
								if(isset($fieldsA['fk_customer_id']) && $fieldsA['fk_customer_id'] == $val['personal_id']) {
									$selected = " selected='selected'";
								}
								echo "<option{$selected} value='{$val['personal_id']}'>{$val['name']} {$val['surname']}</option>";
							}
						?>
					</select>
				</div>
				<label class="field" for="fk_subscription_id">Subscrioption<?php echo in_array('fk_subscription_id', $required) ? '<span> *</span>' : ''; ?></label>
				<div class="col-10">
					<select id="fk_subscription_id" class="form-control" name="fk_subscription_id">
						<option value="">Select subscription</option>
						<?php
							// selecting all subscriptions
							$subscrioption =  $subscriptionsObj->getSubscriptionList();
							foreach($subscrioption as $key => $val) {
								$selected = "";
								if(isset($fieldsA['fk_subscription_id']) && $fieldsA['fk_subscription_id'] == $val['id_subscription']) {
									$selected = " selected='selected'";
								}
								echo "<option{$selected} value='{$val['id_subscription']}'> ID-{$val['id_subscription']} / {$val['price']} eu</option>";
							}
						?>
					</select>
				</div>
			</div>
			</div>
		</fieldset>

		<fieldset>
			<legend align="center">Payment information</legend>
			<div class="col-10" style="margin: 0 auto;">
			<div class="childRowContainer">
				<div class="row col-10">
					<div class="col-4<?php if(empty($AccountPayment) || sizeof($AccountPayment) == 0) echo ' hidden'; ?>">Date</div>
					<div class="col-4<?php if(empty($AccountPayment) || sizeof($AccountPayment) == 0) echo ' hidden'; ?>">Amount</div>
				</div>
				<?php
					if(empty($AccountPayment) || sizeof($AccountPayment) == 0) {
				?>

					<div class="childRow row col-10 hidden">
						<input type="text" class="col-4 form-control" name="NewPayment[payment_date][]" value="" class="textbox-100" disabled="disabled"/>
						<input type="text" class="col-4 form-control" name="NewPayment[payment_amount][]" value="" class="textbox-100" disabled="disabled" />
						<input type="hidden" class="isDisabledForEditing" name="neaktyvus[]" value="0" />
					</div>
					<div class="float-clear"></div>

				<?php
					} else {
						foreach($AccountPayment as $key => $val) {
				?>

                                    <div class="childRow row col-10">
                                        <input type="text" class="col-4 form-control" name="NewPayment[payment_date][]" value="<?php echo $val['payment_date']; ?>" class="textbox-100" disabled/>
                                        <input type="text" class="col-4 form-control" name="NewPayment[payment_amount][]" value="<?php echo $val['payment_amount']; ?>" class="textbox-100" disabled/>
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
					<input type="submit" class="btn btn-primary" name="submit" value="Save"> <small class="text-muted">* please, fill in all the blanks</small>
				</div>
			</div>
	</form>
</div>
</div>
