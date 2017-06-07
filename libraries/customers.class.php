<?php

/**
 * Customers editing class
 *
 * @author Gedas_Gardauskas
 */

class customers {

	public function __construct() {

	}

	/**
	 * Finding Customer by id
	 * @param type $id
	 * @return type
	 */
	public function getCustomer($id) {
		$query = "  SELECT *
					FROM `CUSTOMER`
					WHERE `CUSTOMER`.`personal_id`='{$id}'";
		$data = mysql::select($query);

		return $data[0];
	}

	/**
	 * Finding Customers list
	 * @param type $limit
	 * @param type $offset
	 * @return type
	 */
	public function getCustomersList($limit = null, $offset = null) {
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
		}
		if(isset($offset)) {
			$limitOffsetString .= " OFFSET {$offset}";
		}

		$query = "  SELECT `CUSTOMER`.`personal_id`,
						   `CUSTOMER`.`name`,
						   `CUSTOMER`.`surname`,
						   `CUSTOMER`.`phone_number`,
						   `CUSTOMER`.`email`,
							 `CUSTOMER`.`foto`,
						   `CUSTOMER`.`first_registration`,
						   `social_statuses`.`name` AS `social_status`
					FROM `CUSTOMER`
						LEFT JOIN `social_statuses`
							ON `CUSTOMER`.`social_status`=`social_statuses`.`id_social_status`" . $limitOffsetString;
		$data = mysql::select($query);

		return $data;
	}

	/**
	 * Finding Customers list count
	 * @return type
	 */
	public function getCustomersListCount() {
		$query = "  SELECT COUNT(`personal_id`) as `count`
					FROM `CUSTOMER`
						LEFT JOIN `social_statuses`
							ON `CUSTOMER`.`social_status`=`social_statuses`.`id_social_status`";
		$data = mysql::select($query);

		return $data[0]['count'];
	}

	/**
	 * Deleting Customer by id
	 * @param type $id
	 */
	public function deleteCustomer($id) {
		$query = "  DELETE FROM `CUSTOMER`
					WHERE `personal_id`='{$id}'";
		mysql::query($query);
	}

	/**
	 * Updating Customer data
	 * @param type $data
	 */
	public function updateCustomer($data) {
		$query = "  UPDATE `CUSTOMER`
					SET  `name`='{$data['name']}',
						   `surname`='{$data['surname']}',
						   `phone_number`='{$data['phone_number']}',
						   `email`='{$data['email']}',
						   `foto`='{$data['foto']}',
						   `first_registration`='{$data['first_registration']}',
						   `social_status`='{$data['social_status']}'
					WHERE `personal_id`='{$data['personal_id']}'";
		mysql::query($query);
	}

	/**
	 * Inserting new Customer
	 * @param type $data
	 */
	public function insertCustomer($data) {
		$query = "  INSERT INTO `CUSTOMER`
								(
									`personal_id`,
									`name`,
									`surname`,
									`phone_number`,
									`email`,
									`foto`,
									`first_registration`,
									`social_status`
								)
								VALUES
								(
									'{$data['personal_id']}',
									'{$data['name']}',
									'{$data['surname']}',
									'{$data['phone_number']}',
									'{$data['email']}',
									'{$data['foto']}',
									'{$data['first_registration']}',
									'{$data['social_status']}'
								)";
		mysql::query($query);
	}

	/**
	 * Finding Social Statutes list
	 * @return type
	 */
	public function getSocialStatusesList() {
		$query = "  SELECT *
					FROM `social_statuses`";
		$data = mysql::select($query);

		return $data;
	}

	/**
	 * Finding Cusomer subscription by Customer id
	 * @param type $id
	 * @return type
	 */
	public function getSubscriptionsCountOfCustomer($id) {
		$query = "  SELECT COUNT(`SUBSCRIPTION`.`id_subscription`) AS `count`
					FROM `CUSTOMER`
						INNER JOIN `SUBSCRIPTION`
							ON `CUSTOMER`.`personal_id`=`SUBSCRIPTION`.`fk_customer_id`
					WHERE `CUSTOMER`.`personal_id`='{$id}'";
		$data = mysql::select($query);

		return $data[0]['count'];
	}
}
