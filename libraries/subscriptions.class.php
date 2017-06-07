<?php

/**
 * Subscription editing class
 *
 * @author Gedas_Gardauskas
 */

class subscriptions {

	public function __construct() {

	}

	/**
	 * Finding Subscription by id
	 * @param type $id
	 * @return type
	 */
	public function getSubscription($id) {
		$query = "  SELECT *
					FROM `SUBSCRIPTION`
					WHERE `SUBSCRIPTION`.`id_subscription`='{$id}' GROUP BY `SUBSCRIPTION`.`id_subscription`";
		$data = mysql::select($query);

		return $data[0];
	}

	/**
	 * Finding Subscription list
	 * @param type $limit
	 * @param type $offset
	 * @return type
	 */
	public function getSubscriptionList($limit = null, $offset = null) {
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
		}
		if(isset($offset)) {
			$limitOffsetString .= " OFFSET {$offset}";
		}

		$query = "  SELECT `SUBSCRIPTION`.`id_subscription`,
						   `SUBSCRIPTION`.`valid_from`,
						   `SUBSCRIPTION`.`valid_till`,
						   `SUBSCRIPTION`.`price`,
						   `types`.`name` AS `type`,
						   `CUSTOMER`.`name` AS `name`,
						   `CUSTOMER`.`surname`AS `surname`
					FROM `SUBSCRIPTION`
						LEFT JOIN `types`
							ON `SUBSCRIPTION`.`type`=`types`.`id_type`
						LEFT JOIN `CUSTOMER`
							ON `SUBSCRIPTION`.`fk_customer_id`=`CUSTOMER`.`personal_id`
					ORDER BY `SUBSCRIPTION`.`id_subscription`" . $limitOffsetString;
		$data = mysql::select($query);

		return $data;
	}

	/**
	 * Finding Subscription list count
	 * @return type
	 */
	public function getSubscriptionListCount() {
		$query = "  SELECT COUNT(`id_subscription`) AS `count`
					FROM `SUBSCRIPTION`
						LEFT JOIN `types`
							ON `SUBSCRIPTION`.`type`=`types`.`id_type`
						LEFT JOIN `CUSTOMER`
							ON `SUBSCRIPTION`.`fk_customer_id`=`CUSTOMER`.`personal_id`";
		$data = mysql::select($query);

		return $data[0]['count'];
	}

	/**
	 * Deleting Subscription by id
	 * @param type $id
	 */
	public function deleteSubscription($id) {
		$query = "  DELETE FROM `SUBSCRIPTION`
					WHERE `id_subscription`='{$id}'";
		mysql::query($query);
	}


	/**
	 * Updating Subscription
	 * @param type $data
	*/
	public function updateSubscription($data) {
		$query = "  UPDATE `SUBSCRIPTION`
					SET  `valid_from`='{$data['valid_from']}',
						   `valid_till`='{$data['valid_till']}',
						   `price`='{$data['price']}',
						   `type`='{$data['type']}',
						   `fk_customer_id`='{$data['fk_customer_id']}'
					WHERE `id_subscription`='{$data['id_subscription']}'";
		mysql::query($query);
	}

	/**
	 * Inserting new Subscription
	 * @param type $data
	 */
	public function insertSubscription($data) {
		$query = "  INSERT INTO `SUBSCRIPTION`
								(
									`id_subscription`,
									`valid_from`,
									`valid_till`,
									`price`,
									`type`,
									`fk_customer_id`
								)
								VALUES
								(
									'{$data['id_subscription']}',
									'{$data['valid_from']}',
									'{$data['valid_till']}',
									'{$data['price']}',
									'{$data['type']}',
									'{$data['fk_customer_id']}'
								)";
		mysql::query($query);
	}

	/**
	 * Finding Type list
	 * @return type
	 */
	public function getTipasList() {
		$query = "  SELECT *
					FROM `types`";
		$data = mysql::select($query);

		return $data;
	}

	/**
	 * Finding Subscription max value id
	 * @return type
	 */
	public function getMaxIdOfSubscription() {
		$query = "  SELECT MAX(`id_subscription`) AS `latestId`
					FROM `SUBSCRIPTION`";
		$data = mysql::select($query);

		return $data[0]['latestId'];
	}

	/**
	 * Finding Subscription's invoice count
	 * @param type $id
	 * @return type
	 */
	public function getAccountsCountOfSubscription($id) {
		$query = "  SELECT COUNT(`INVOICE`.`number`) AS `count`
					FROM `SUBSCRIPTION`
						INNER JOIN `INVOICE`
							ON `SUBSCRIPTION`.`id_subscription`=`INVOICE`.`fk_subscription_id`
					WHERE `SUBSCRIPTION`.`id_subscription`='{$id}'";
		$data = mysql::select($query);

		return $data[0]['count'];
	}
	////////////////////////////////////////////////////
	/**
	 * -- Finding list
	 * @param type $serviceId
	 * @return type
	 */
	public function getScheduleHours($id) {
		$query = "  SELECT *
					FROM `ENTRANCE_TIME`
					WHERE `fk_subscription_id`='{$id}'";
		$data = mysql::select($query);

		return $data;
	}


	public function deleteScheduleHours($id) {
		$query = "  DELETE FROM `ENTRANCE_TIME`
					WHERE `id_entrance_time`='{$id}'";
		mysql::query($query);
	}

	/**
	 *
	 * @param type $data
	 */
	public function insertScheduleHours($data, $ID) {
		$nextHId = $this->getMaxIdOfHours() + 1;
		$query = "  INSERT INTO `ENTRANCE_TIME`
								(
									`id_entrance_time`,
									`weekday`,
									`from`,
									`till`,
									`fk_subscription_id`
								)
								VALUES
								(
									'{$nextHId}',
									'{$data['weekday']}',
									'{$data['from']}',
									'{$data['till']}',
									'{$ID}'
								)";
		$result = mysql::query($query);
		var_dump(mysql::error());
	}

	/**
	 * Finding Entrance Time max value id
	 * @return type
	 */
	public function getMaxIdOfHours() {
		$query = "  SELECT MAX(`id_entrance_time`) AS `latestId`
					FROM `ENTRANCE_TIME`";
		$data = mysql::select($query);

		return $data[0]['latestId'];
	}

	public function getCustomerSubscriptions($dateFrom, $dateTo) {
		$whereClauseString = "";
		if(!empty($dateFrom)) {
			$whereClauseString .= " WHERE `SUBSCRIPTION`.`valid_from`>='{$dateFrom}'";
			if(!empty($dateTo)) {
				$whereClauseString .= " AND `SUBSCRIPTION`.`valid_from`<='{$dateTo}'";
			}
		} else {
			if(!empty($dateTo)) {
				$whereClauseString .= " WHERE `SUBSCRIPTION`.`valid_from`<='{$dateTo}'";
			}
		}

		$query = "  SELECT  `SUBSCRIPTION`.`id_subscription`,
								`SUBSCRIPTION`.`valid_from`,
								`SUBSCRIPTION`.`valid_till`,
						    `SUBSCRIPTION`.`price`,
						    `types`.`name` AS `type`,
						    `CUSTOMER`.`personal_id`,
						    `CUSTOMER`.`name`,
						    `CUSTOMER`.`surname`,
						    `t`.`total_customer_subscriptions_price`
					FROM `SUBSCRIPTION`
						LEFT JOIN `types`
							ON `SUBSCRIPTION`.`type`=`types`.`id_type`
						LEFT JOIN `CUSTOMER`
							ON `SUBSCRIPTION`.`fk_customer_id`=`CUSTOMER`.`personal_id`
						LEFT JOIN (
							SELECT `personal_id`,
									sum(`SUBSCRIPTION`.`price`) AS `total_customer_subscriptions_price`
							FROM `SUBSCRIPTION`
								INNER JOIN `CUSTOMER`
									ON `SUBSCRIPTION`.`fk_customer_id`=`CUSTOMER`.`personal_id`
							{$whereClauseString}
							GROUP BY `personal_id`
						) `t` ON `t`.`personal_id`=`CUSTOMER`.`personal_id`
					{$whereClauseString}
					GROUP BY `SUBSCRIPTION`.`id_subscription` ORDER BY `CUSTOMER`.`surname` ASC";
		$data = mysql::select($query);

		return $data;
	}


	public function getSumPriceOfCustomerSubscriptions($dateFrom, $dateTo) {
		$whereClauseString = "";
		if(!empty($dateFrom)) {
			$whereClauseString .= " WHERE `SUBSCRIPTION`.`valid_from`>='{$dateFrom}'";
			if(!empty($dateTo)) {
				$whereClauseString .= " AND `SUBSCRIPTION`.`valid_from`<='{$dateTo}'";
			}
		} else {
			if(!empty($dateTo)) {
				$whereClauseString .= " WHERE `SUBSCRIPTION`.`valid_from`<='{$dateTo}'";
			}
		}

		$query = "  SELECT  SUM(`SUBSCRIPTION`.`price`) as `total_amount`
					FROM `SUBSCRIPTION`
						LEFT JOIN `types`
							ON `SUBSCRIPTION`.`type`=`types`.`id_type`
						LEFT JOIN `CUSTOMER`
							ON `SUBSCRIPTION`.`fk_customer_id`=`CUSTOMER`.`personal_id`
					{$whereClauseString}";
		$data = mysql::select($query);

		return $data;
	}

}
