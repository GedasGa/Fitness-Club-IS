<?php

/**
 * Fitness Club editing class
 *
 * @author Gedas_Gardauskas
 */

class gyms {

	public function __construct() {

	}
	/**
	 * Finding Fitness Club by id
	 * @param type $id
	 * @return type
	 */
	public function getGym($id) {
		$query = "  SELECT *
					FROM `FITNESS_CLUB`
					WHERE `FITNESS_CLUB`.`id_fitness_club`='{$id}'";
		$data = mysql::select($query);

		return $data[0];
	}

	/**
	 * Finding Fitness Clubs list
	 * @param type $limit
	 * @param type $offset
	 * @return type
	 */
	public function getGymsList($limit = null, $offset = null) {
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
		}
		if(isset($offset)) {
			$limitOffsetString .= " OFFSET {$offset}";
		}

		$query = "  SELECT `FITNESS_CLUB`.`id_fitness_club`,
						   `FITNESS_CLUB`.`name`,
						   `FITNESS_CLUB`.`features`,
						   `ADDRESS`.`street` AS `street`,
						   `ADDRESS`.`house_number` AS `house_number`,
						   `ADDRESS`.`post_code` AS `post_code`,
						   `CITY`.`city`
					FROM `FITNESS_CLUB`
						LEFT JOIN `ADDRESS`
							ON `FITNESS_CLUB`.`fk_address_id`=`ADDRESS`.`id_address`
						LEFT JOIN `CITY`
							ON `ADDRESS`.`fk_city_id`=`CITY`.`id_city`" . $limitOffsetString;
		$data = mysql::select($query);

		return $data;
	}

	/**
	 * Finding Fitness Clubs list count
	 * @return type
	 */
	public function getGymListCount() {
		$query = "  SELECT COUNT(`id_fitness_club`) as `count`
					FROM `FITNESS_CLUB`
						LEFT JOIN `ADDRESS`
							ON `FITNESS_CLUB`.`fk_address_id`=`ADDRESS`.`id_address`
						LEFT JOIN `CITY`
							ON `ADDRESS`.`fk_city_id`=`CITY`.`id_city`";
		$data = mysql::select($query);

		return $data[0]['count'];
	}

	/**
	 * Deleting Fitness Club by id
	 * @param type $id
	 */
	public function deleteGym($id) {
		$query = "  DELETE FROM `FITNESS_CLUB`
					WHERE `id_fitness_club`='{$id}'";
		mysql::query($query);
	}

	/**
	 * Updating Fitness Club data
	 * @param type $data
	*/
	public function updateGym($data) {
		$query = "  UPDATE `FITNESS_CLUB`
					SET     `name`='{$data['name']}',
							`features`='{$data['features']}'
					WHERE `id_fitness_club`='{$data['id_fitness_club']}'";
		mysql::query($query);
	}

	/**
	 * Inserting new Fitness Club
	 * @param type $data
	*/
	public function insertGym($data) {
		$query = "  INSERT INTO `FITNESS_CLUB`
								(
									`id_fitness_club`,
									`name`,
									`features`,
									`fk_address_id`
								)
								VALUES
								(
									'{$data['id_fitness_club']}',
									'{$data['name']}',
									'{$data['features']}',
									'{$data['fk_address_id']}'
								)";
		mysql::query($query);
	}

	/**
	 * Finding Fitness Club max id
	 * @return type
	 */
	public function getMaxIdOfGym() {
		$query = "  SELECT MAX(`id_fitness_club`) AS `latestId`
					FROM `FITNESS_CLUB`";
		$data = mysql::select($query);

		return $data[0]['latestId'];
	}

	/**
	 * Finding Fitness Clubs employees count
	 * @param type $id
	 * @return type
	 */
	public function getEmployeesCountOfGym($id) {
		$query = "  SELECT COUNT(`DARBUOTOJAS`.`asmens_kodas`) AS `count`
					FROM `FITNESS_CLUB`
						INNER JOIN `DARBUOTOJAS`
							ON `FITNESS_CLUB`.`id_fitness_club`=`DARBUOTOJAS`.`fk_fitness_club_id`
					WHERE `FITNESS_CLUB`.`id_fitness_club`='{$id}'";
		$data = mysql::select($query);

		return $data[0]['count'];
	}

	/////////////////////////////////////////////////////////////////
	/**
	 * Finding Address by id
	 * @param type $id
	 * @return type
	 */
	public function getAddress($id) {
		$query = "  SELECT *
					FROM `ADDRESS`
					WHERE `ADDRESS`.`id_address`='{$id}'";
		$data = mysql::select($query);

		return $data[0];
	}

	/**
	 * Deleting address
	 * @param type $id
	 */
	public function deleteAddress($id) {
		$query = "  DELETE FROM `ADDRESS`
					WHERE `id_address`='{$id}'";
		mysql::query($query);
	}

	/**
	 * Updating Address
	 * @param type $data
	*/
	public function updateAddress($data) {
		$query = "  UPDATE `ADDRESS`
					SET `street`='{$data['street']}',
							`house_number`='{$data['house_number']}',
							`post_code`='{$data['post_code']}',
							`fk_city_id`='{$data['fk_city_id']}'
					WHERE `id_address`='{$data['id_address']}'";
		mysql::query($query);
	}

	/**
	 * Inserting Address
	 * @param type $data
	*/
	public function insertAddress($data) {
		$query = "  INSERT INTO `ADDRESS`
								(
									`id_address`,
									`street`,
									`house_number`,
									`post_code`,
									`fk_city_id`
								)
								VALUES
								(
									'{$data['id_address']}',
									'{$data['street']}',
									'{$data['house_number']}',
									'{$data['post_code']}',
									'{$data['fk_city_id']}'
								)";
		mysql::query($query);
	}

	/**
	 * Finding Address latest value by id
	 * @return type
	 */
	public function getMaxIdOfAddress() {
		$query = "  SELECT MAX(`id_address`) AS `latestId`
					FROM `ADDRESS`";
		$data = mysql::select($query);

		return $data[0]['latestId'];
	}
	/////////////////////////////////////////////////////////////////
	/**
	 * -- Finding List
	 * @param type $serviceId
	 * @return type
	 */
	public function getScheduleHours($id) {
		$query = "  SELECT *
					FROM `WORKING_HOURS`
					WHERE `fk_fitness_club_id`='{$id}'";
		$data = mysql::select($query);

		return $data;
	}

	/**
	 * Deleting Working Hours by id
	 * @param type $id
	 */
	public function deleteScheduleHours($id) {
		$query = "  DELETE FROM `WORKING_HOURS`
					WHERE `id_working_hours`='{$id}'";
		mysql::query($query);
	}

	/**
	 *
	 * @param type $data
	 */
	public function insertScheduleHours($data, $ID) {
		$nextHId = $this->getMaxIdOfHours() + 1;
		$query = "  INSERT INTO `WORKING_HOURS`
								(
									`id_working_hours`,
									`weekday`,
									`from`,
									`till`,
									`fk_fitness_club_id`
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
	 * Finding Working Hours latest value by id
	 * @return type
	 */
	public function getMaxIdOfHours() {
		$query = "  SELECT MAX(`id_working_hours`) AS `latestId`
					FROM `WORKING_HOURS`";
		$data = mysql::select($query);

		return $data[0]['latestId'];
	}


}
