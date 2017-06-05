<?php

/**
 * Sąskaitų redagavimo klasė
 *
 * @author Gedas_Gardauskas
 */

class payments {

	public function __construct() {

	}

	/**
	 * Sąskaitos išrinkimas
	 * @param type $id
	 * @return type
	 */
	public function getPayment($id) {
		$query = "  SELECT *
					FROM `PAYMENT`
					WHERE `PAYMENT`.`id_payment`='{$id}' GROUP BY `PAYMENT`.`id_payment`";
		$data = mysql::select($query);

		return $data[0];
	}

	/**
	 * Sąskaitų sąrašo išrinkimas
	 * @param type $limit
	 * @param type $offset
	 * @return type
	 */
	public function getPaymentList($limit = null, $offset = null) {
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
		}
		if(isset($offset)) {
			$limitOffsetString .= " OFFSET {$offset}";
		}

		$query = "  SELECT `PAYMENT`.`id_payment`,
						   `PAYMENT`.`payment_date`,
						   `PAYMENT`.`amount`,
						   `CUSTOMER`.`name` AS `customer_name`,
						   `CUSTOMER`.`surname` AS `customer_surname`,
						   `EMPLOYEE`.`name` AS `employee_name`,
						   `EMPLOYEE`.`surname` AS `employee_surname`,
						   `INVOICE`.`number`,
						   `INVOICE`.`invoice_date`,
						   `INVOICE`.`invoice_amount`,
					   	 `INVOICE`.`fk_subscription_id` AS `subscription`
					FROM `PAYMENT`
						LEFT JOIN `CUSTOMER`
							ON `PAYMENT`.`fk_customer_id`=`CUSTOMER`.`personal_id`
						LEFT JOIN `INVOICE`
							ON `PAYMENT`.`fk_invoice_id`=`INVOICE`.`number`
						LEFT JOIN `EMPLOYEE`
							ON `INVOICE`.`fk_employee_id`=`EMPLOYEE`.`personal_id`" . $limitOffsetString;
		$data = mysql::select($query);

		return $data;
	}

	/**
	 * Sąskaitų kiekio radimas
	 * @return type
	 */
	public function getPaymentListCount() {
		$query = "  SELECT COUNT(`id_payment`) AS `count`
					FROM `PAYMENT`
						LEFT JOIN `CUSTOMER`
							ON `PAYMENT`.`fk_customer_id`=`CUSTOMER`.`personal_id`
						LEFT JOIN `INVOICE`
							ON `PAYMENT`.`fk_invoice_id`=`INVOICE`.`number`
						LEFT JOIN `EMPLOYEE`
							ON `INVOICE`.`fk_employee_id`=`EMPLOYEE`.`personal_id`";
		$data = mysql::select($query);

		return $data[0]['count'];
	}

	/**
	 * Sąskaitos šalinimas
	 * @param type $id
	 */
	public function deletePayment($id) {
		$query = "  DELETE FROM `PAYMENT`
					WHERE `id_payment`='{$id}'";
		mysql::query($query);
	}


	/**
	 * Sąskaitos atnaujinimas
	 * @param type $data
	*/
	public function updatePayment($data) {
		$query = "  UPDATE `PAYMENT`
					SET    `payment_date`='{$data['payment_date']}',
						   `amount`='{$data['amount']}',
						   `fk_customer_id`='{$data['fk_customer_id']}'
					WHERE `id_payment`='{$data['id_payment']}'";
		mysql::query($query);
	}

	/**
	 * Sąskaitos įrašymas
	 * @param type $data
	 */
	public function insertPayment($data) {
		$query = "  INSERT INTO `PAYMENT`
								(
									`id_payment`,
									`payment_date`,
									`amount`,
									`fk_customer_id`,
									`fk_invoice_id`
								)
								VALUES
								(
									'{$data['id_payment']}',
									'{$data['payment_date']}',
									'{$data['amount']}',
									'{$data['fk_customer_id']}',
									'{$data['number']}'
								)";
		mysql::query($query);
	}


	/**
	 * Didžiausios mokejimo idreikšmės radimas
	 * @return type
	 */
	public function getMaxIdOfPayment() {
		$query = "  SELECT MAX(`id_payment`) AS `latestId`
					FROM `PAYMENT`";
		$data = mysql::select($query);

		return $data[0]['latestId'];
	}


	/**
	 * Sąskaitos išrinkimas
	 * @param type $id
	 * @return type
	 */
	public function getAccountPayment($id) {
		$query = "  SELECT *
					FROM `PAYMENT`
					WHERE `fk_invoice_id`='{$id}'";
		$data = mysql::select($query);

		return $data[0];
	}

		public function deleteAccountPayment($id) {
		$query = "  DELETE FROM `PAYMENT`
					WHERE `id_payment`='{$id}'";
		mysql::query($query);
	}

	/**
	 *
	 * @param type $data
	 */
	public function insertAccountPayment($data, $ID) {
		$nextHId = $this->getMaxIdOfPayment() + 1;
		$query = "  INSERT INTO `PAYMENT`
								(
									`id_payment`,
									`payment_date`,
									`amount`,
									`fk_customer_id`,
									`fk_invoice_id`
								)
								VALUES
								(
									'{$nextHId}',
									'{$data['payment_date']}',
									'{$data['amount']}',
									'{$data['fk_customer_id']}',
									'{$ID}'
								)";
		$result = mysql::query($query);
		var_dump(mysql::error());
	}

	///////////////////////////////////////////////////////////
	/**
	 * Saskaitos išrinkimas
	 * @param type $id
	 * @return type
	 */
	public function getAccount($id) {
		$query = "  SELECT `INVOICE`.`number`,
						   `INVOICE`.`invoice_date`,
						   `INVOICE`.`invoice_amount`,
					   	 `INVOICE`.`fk_subscription_id`,
					   	 `INVOICE`.`fk_employee_id`,
						   `CUSTOMER`.`name` AS `customer_name`,
						   `CUSTOMER`.`surname` AS `customer_surname`,
						   `EMPLOYEE`.`name` AS `employee_name`,
						   `EMPLOYEE`.`surname` AS `employee_surname`,
						   `PAYMENT`.`payment_date`,
						   `PAYMENT`.`amount`,
						   `PAYMENT`.`fk_customer_id`,
						   `PAYMENT`.`fk_invoice_id`
					FROM `INVOICE`
						LEFT JOIN `PAYMENT`
								ON `INVOICE`.`number`=`PAYMENT`.`fk_invoice_id`
							LEFT JOIN `CUSTOMER`
								ON `PAYMENT`.`fk_customer_id`=`CUSTOMER`.`personal_id`
							LEFT JOIN `EMPLOYEE`
								ON `INVOICE`.`fk_employee_id`=`EMPLOYEE`.`personal_id`
					WHERE `INVOICE`.`number`='{$id}'";
		$data = mysql::select($query);

		return $data[0];
	}

	/**
	 * Saskaitos sąrašo išrinkimas
	 * @param type $id
	 * @return type
	 */
	public function getAccountList($limit = null, $offset = null) {
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
		}
		if(isset($offset)) {
			$limitOffsetString .= " OFFSET {$offset}";
		}

		$query = "  SELECT `INVOICE`.`number`,
						   `INVOICE`.`invoice_date`,
						   `INVOICE`.`invoice_amount`,
					   	 `INVOICE`.`fk_subscription_id` AS `subscription`,
						   `CUSTOMER`.`name` AS `customer_name`,
						   `CUSTOMER`.`surname` AS `customer_surname`,
						   `EMPLOYEE`.`name` AS `employee_name`,
						   `EMPLOYEE`.`surname` AS `employee_surname`,
					   	 `PAYMENT`.`id_payment`,
						   `PAYMENT`.`payment_date`,
						   `PAYMENT`.`amount`
					FROM `INVOICE`
						LEFT JOIN `PAYMENT`
							ON `INVOICE`.`number`=`PAYMENT`.`fk_invoice_id`
						LEFT JOIN `CUSTOMER`
							ON `PAYMENT`.`fk_customer_id`=`CUSTOMER`.`personal_id`
						LEFT JOIN `EMPLOYEE`
							ON `INVOICE`.`fk_employee_id`=`EMPLOYEE`.`personal_id`" . $limitOffsetString;
		$data = mysql::select($query);

		return $data;
	}


	/**
	 * Sąskaitų kiekio radimas
	 * @return type
	 */
	public function getAccountListCount() {
		$query = "  SELECT COUNT(`number`) AS `count`
					FROM `INVOICE`
						LEFT JOIN `PAYMENT`
							ON `INVOICE`.`number`=`PAYMENT`.`fk_invoice_id`
						LEFT JOIN `CUSTOMER`
							ON `PAYMENT`.`fk_customer_id`=`CUSTOMER`.`personal_id`
						LEFT JOIN `EMPLOYEE`
							ON `INVOICE`.`fk_employee_id`=`EMPLOYEE`.`personal_id`";
		$data = mysql::select($query);

		return $data[0]['count'];
	}

	/**
	 * Sąskaitų šalinimas
	 * @param type $id
	 */
	public function deleteAccount($id) {
		$query = "  DELETE FROM `INVOICE`
					WHERE `number`='{$id}'";
		mysql::query($query);
	}


	/**
	 * Sąskaitų atnaujinimas
	 * @param type $data
	*/
	public function updateAccount($data) {
		$query = "  UPDATE `INVOICE`
					SET  `invoice_date`='{$data['invoice_date']}',
 						   `invoice_amount`='{$data['invoice_amount']}',
						   `fk_subscription_id`='{$data['fk_subscription_id']}',
						   `fk_employee_id`='{$data['fk_employee_id']}'
					WHERE `number`='{$data['number']}'";
		mysql::query($query);
	}

	/**
	 * Sąskaitų įrašymas
	 * @param type $data
	 */
	public function insertAccount($data) {
		$query = "  INSERT INTO `INVOICE`
								(
									`number`,
									`invoice_date`,
									`invoice_amount`,
									`fk_subscription_id`,
									`fk_employee_id`
								)
								VALUES
								(
									'{$data['number']}',
									'{$data['invoice_date']}',
									'{$data['invoice_amount']}',
									'{$data['fk_subscription_id']}',
									'{$data['fk_employee_id']}'
								)";
		mysql::query($query);
	}

	public function getAccountReport($dateFrom, $dateTo) {
		$whereClauseString = "";
		if(!empty($dateFrom)) {
			$whereClauseString .= " WHERE `INVOICE`.`invoice_date`>='{$dateFrom}'";
			if(!empty($dateTo)) {
				$whereClauseString .= " AND `INVOICE`.`invoice_date`<='{$dateTo}'";
			}
		} else {
			if(!empty($dateTo)) {
				$whereClauseString .= " WHERE `INVOICE`.`invoice_date`<='{$dateTo}'";
			}
		}

		$query = "  SELECT `INVOICE`.`number`,
						   `INVOICE`.`invoice_date`,
						   `INVOICE`.`invoice_amount`,
					   	 `INVOICE`.`fk_subscription_id` AS `subscription`,
						   `CUSTOMER`.`name` AS `customer_name`,
						   `CUSTOMER`.`surname` AS `customer_surname`,
						   `EMPLOYEE`.`name` AS `employee_name`,
						   `EMPLOYEE`.`surname` AS `employee_surname`,
					   	 `PAYMENT`.`id_payment`,
						   `PAYMENT`.`payment_date`,
						   `PAYMENT`.`amount`,
						   IF(`amount`=`invoice_amount`, 'payed', (`amount`-`invoice_amount`)) AS `payments`
					FROM `INVOICE`
						LEFT JOIN `PAYMENT`
							ON `INVOICE`.`number`=`PAYMENT`.`fk_invoice_id`
						LEFT JOIN `CUSTOMER`
							ON `PAYMENT`.`fk_customer_id`=`CUSTOMER`.`personal_id`
						LEFT JOIN `EMPLOYEE`
							ON `INVOICE`.`fk_employee_id`=`EMPLOYEE`.`personal_id`
					{$whereClauseString}
					GROUP BY `INVOICE`.`number`
					Order BY `INVOICE`.`number` ASC";
		$data = mysql::select($query);

		return $data;
	}

	public function getAccountSum($dateom, $dateTo) {
		$whereClauseString = "";
		if(!empty($dateFrom)) {
			$whereClauseString .= " WHERE `INVOICE`.`invoice_date`>='{$dateFrom}'";
			if(!empty($dateTo)) {
				$whereClauseString .= " AND `INVOICE`.`invoice_date`<='{$dateTo}'";
			}
		} else {
			if(!empty($dateTo)) {
				$whereClauseString .= " WHERE `INVOICE`.`invoice_date`<='{$dateTo}'";
			}
		}

		$query = "  SELECT  SUM(`INVOICE`.`invoice_amount`) as `invoices_amount`,
							SUM(`PAYMENT`.`amount`) as `payments_amount`
					FROM `INVOICE`
						LEFT JOIN `PAYMENT`
							ON `INVOICE`.`number`=`PAYMENT`.`fk_invoice_id`
						LEFT JOIN `CUSTOMER`
							ON `PAYMENT`.`fk_customer_id`=`CUSTOMER`.`personal_id`
						LEFT JOIN `EMPLOYEE`
							ON `INVOICE`.`fk_employee_id`=`EMPLOYEE`.`personal_id`
					{$whereClauseString}";
		$data = mysql::select($query);

		return $data;
	}


}
