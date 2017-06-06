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

	// nustatome privalomus laukus
	$required = array('number', 'invoice_date', 'invoice_amount', 'fk_subscription_id', 'fk_employee_id', 'payment_date', 'amount', 'fk_customer_id');

	// vartotojas paspaudė išsaugojimo mygtuką
	if(!empty($_POST['submit'])) {
	    $NewPayment = $_POST['NewPayment'];
	    $newPayment = [];
			echo($NewPayment);
	    foreach ($NewPayment as $key => $n) {
	        foreach ($n as $key2 => $m) {
	            $newPayment[$key2][$key] = $m;
	        }
	    }

		// nustatome laukų validatorių tipus
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
		// sukuriame laukų validatoriaus objektą
		$validator = new validator($validations, $required);

		// laukai įvesti be klaidų
		if($validator->validate($_POST)) {
			// suformuojame laukų reikšmių masyvą SQL užklausai
			$data = $validator->preparePostFieldsForSQL();

			if(isset($data['editing'])) {
				if(isset($data['number'])) {
					// atnaujiname duomenis
					$paymentsObj->updateAccount($data);
					foreach ($newPayment as $n) {
	                	$paymentsObj->insertAccountPayment($n, $data['number'], $data['fk_customer_id'] );
	                	//var_dump(mysql::error());

	            	}
	         	}

			} else {
				// patikriname, ar nėra sutarčių su tokiu pačiu numeriu
				$tmp = $paymentsObj->getAccount($data['']);

				if(isset($tmp['number'])) {
					// sudarome klaidų pranešimą
					$formErrors = "Sąskaita su įvestu numeriu jau egzistuoja.";
					// laukų reikšmių kintamajam priskiriame įvestų laukų reikšmes
					$fields = $_POST;
				} else {
					// įrašome naują sutartį
					$paymentsObj->insertAccount($data);

					foreach ($newPayment as $n) {
                		$paymentsObj->insertAccountPayment($n, $data['number'], $data['fk_customer_id']);
					}
				}

			}

			// nukreipiame vartotoją į sutarčių puslapį
			if($formErrors == null) {
				header("Location: index.php?module={$module}");
				die();
			}
		} else {
			// gauname klaidų pranešimą
			$formErrors = $validator->getErrorHTML();

			// laukų reikšmių kintamajam priskiriame įvestų laukų reikšmes
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
		// tikriname, ar adreso eilutėje nenurodytas elemento id. Jeigu taip, išrenkame elemento duomenis ir jais užpildome formos laukus.
		if(!empty($id)) {
			$fieldsA = $paymentsObj->getAccount($id);
			$AccountPayment = $paymentsObj->getAccountPayment($id);
			$fields['editing'] = 1;
		}
	}

?>
<ul id="pagePath">
	<li><a href="index.php">Home Page</a></li>
	<li><a href="index.php?module=<?php echo $module; ?>">Payments</a></li>
	<li><?php if(!empty($id)) echo "Edit invoice"; else echo "Add invoice"; ?></li>
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
			<legend>Invoice information</legend>
			<p>
				<?php if(!isset($fields['editing'])) { ?>
					<label class="field" for="number">Number<?php echo in_array('number', $required) ? '<span> *</span>' : ''; ?></label>
					<input type="text" id="number" name="number" class="textbox-70" value="<?php echo isset($fieldsA['number']) ? $fieldsA['number'] : ''; ?>">
				<?php } else { ?>
						<label class="field" for="number">Number</label>
						<span class="input-value"><?php echo $fieldsA['number']; ?></span>
						<input type="hidden" name="editing" value="1" />
						<input type="hidden" name="number" value="<?php echo $fieldsA['number']; ?>" />
				<?php } ?>
			</p>
			<p>
				<label class="field" for="invoice_date">Invoice Date<?php echo in_array('invoice_date', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="invoice_date" name="invoice_date" class="date textbox-70" value="<?php echo isset($fieldsA['invoice_date']) ? $fieldsA['invoice_date'] : ''; ?>">
			</p>
			<p>
				<label class="field" for="invoice_amount">Amount<?php echo in_array('invoice_amount', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="invoice_amount" name="invoice_amount" class="textbox-70" value="<?php echo isset($fieldsA['invoice_amount']) ? $fieldsA['invoice_amount'] : ''; ?>"> <span class="units">&euro;</span>
			</p>
			<p>
				<label class="field" for="fk_employee_id">Employee<?php echo in_array('fk_employee_id', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="fk_employee_id" name="fk_employee_id">
					<option value="">---------------</option>
					<?php
						// išrenkame vartotojus
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
			</p>
			<p>
				<label class="field" for="fk_customer_id">Customer<?php echo in_array('fk_customer_id', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="fk_customer_id" name="fk_customer_id">
					<option value="">---------------</option>
					<?php
						// išrenkame klientus
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
			</p>
			<p>
				<label class="field" for="fk_subscription_id">Subscrioption<?php echo in_array('fk_subscription_id', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="fk_subscription_id" name="fk_subscription_id">
					<option value="">---------------</option>
					<?php
						// išrenkame būsenas
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
			</p>
		</fieldset>

		<fieldset>
			<legend>Payment information</legend>

			<div class="childRowContainer">
				<div class="labelLeft<?php if(empty($AccountPayment) || sizeof($AccountPayment) == 0) echo ' hidden'; ?>">Date</div>
				<div class="labelRight<?php if(empty($AccountPayment) || sizeof($AccountPayment) == 0) echo ' hidden'; ?>">Amount</div>
				<div class="float-clear"></div>
				<?php
					if(empty($AccountPayment) || sizeof($AccountPayment) == 0) {
				?>

					<div class="childRow hidden">
						<input type="text" name="NewPayment[payment_date][]" value="" class="textbox-100" disabled="disabled"/>
						<input type="text" name="NewPayment[payment_amount][]" value="" class="textbox-100" disabled="disabled" />
						<input type="hidden" class="isDisabledForEditing" name="neaktyvus[]" value="0" />

					</div>
					<div class="float-clear"></div>

				<?php
					} else {
						foreach($AccountPayment as $key => $val) {
				?>

                                    <div class="childRow">
                                        <input type="text" name="NewPayment[payment_date][]" value="<?php echo $val['payment_date']; ?>" class="textbox-100" disabled/>
                                        <input type="text" name="NewPayment[payment_amount][]" value="<?php echo $val['payment_amount']; ?>" class="textbox-100" disabled/>
                                    </div>
                                    <div class="float-clear"></div>
				<?php
						}
					}
				?>
			</div>
			<p id="newItemButtonContainer">
				<a href="#" title="" class="addChild">Add</a>
			</p>
		<p>
			<input type="submit" class="submit" name="submit" value="Save">
		</p>
	</form>
</div>
