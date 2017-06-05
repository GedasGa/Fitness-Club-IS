<?php

/**
 * City editing class
 *
 * @author Gedas_Gardauskas
 */

class city {

	public function __construct() {

	}

	/**
	 * Finding City by id
	 * @param type $id
	 * @return type
	 */
	public function getCity($id) {
		$query = "  SELECT *
					FROM `CITY`
					WHERE `id_city`='{$id}'";
		$data = mysql::select($query);

		return $data[0];
	}

	/**
	 * Finding City list
	 * @param type $limit
	 * @param type $offset
	 * @return type
	 */
	public function getCityList($limit = null, $offset = null) {
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
		}
		if(isset($offset)) {
			$limitOffsetString .= " OFFSET {$offset}";
		}

		$query = "  SELECT `CITY`.`id_city`,
						   `CITY`.`city`
					FROM `CITY`" . $limitOffsetString;
		$data = mysql::select($query);

		return $data;
	}

	/**
	 * Finding Cities list count
	 * @return type
	 */
	public function getCityListCount() {
		$query = "  SELECT COUNT(`id_city`) as `count`
					FROM `CITY`";
		$data = mysql::select($query);

		return $data[0]['count'];
	}

	/**
	 * Deleting City by id
	 * @param type $id
	 */
	public function deleteCity($id) {
		$query = "  DELETE FROM `CITY`
					WHERE `id_city`='{$id}'";
		mysql::query($query);
	}

	/**
	 * Updating city data
	 * @param type $data
	*/
	public function updateCity($data) {
		$query = "  UPDATE `CITY`
					SET     `city`='{$data['city']}'
					WHERE `id_city`='{$data['id_city']}'";
		mysql::query($query);
	}

	/**
	 * Inserting new City
	 * @param type $data
	*/
	public function insertCity($data) {
		$query = "  INSERT INTO `CITY`
								(
									`id_city`,
									`city`
								)
								VALUES
								(
									'{$data['id_city']}',
									'{$data['city']}'
								)";
		mysql::query($query);
	}

	/**
	 * Finding max id of the city
	 * @return type
	 */
	public function getMaxIdOfCity() {
		$query = "  SELECT MAX(`id_city`) AS `latestId`
					FROM `CITY`";
		$data = mysql::select($query);

		return $data[0]['latestId'];
	}

	/**
	 * Finding Cities adresses count
	 * @param type $id
	 * @return type
	 */
	public function getAddressCountOfCity($id) {
		$query = "  SELECT COUNT(`ADDRESS`.`id_address`) AS `count`
					FROM `CITY`
						INNER JOIN `ADRESAS`
							ON `CITY`.`id_city`=`ADDRESS`.`fk_city_id`
					WHERE `CITY`.`id_city`='{$id}'";
		$data = mysql::select($query);

		return $data[0]['count'];
	}

}
