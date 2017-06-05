<?php

/**
 * Darbuotojų redagavimo klasė
 *
 * @author Gedas_Gardauskas
 */

class employees {

	public function __construct() {

	}

	/**
	 * Darbuotojo išrinkimas
	 * @param type $id
	 * @return type
	 */
	public function getEmployee($id) {
		$query = "  SELECT *
					FROM `EMPLOYEE`
					WHERE `EMPLOYEE`.`personal_id`='{$id}'";
		$data = mysql::select($query);

		return $data[0];
	}

	/**
	 * Darbuotojų sąrašo išrinkimas
	 * @param type $limit
	 * @param type $offset
	 * @return type
	 */
	public function getEmployeesList($limit = null, $offset = null) {
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
		}
		if(isset($offset)) {
			$limitOffsetString .= " OFFSET {$offset}";
		}

		$query = "  SELECT `EMPLOYEE`.`personal_id`,
						   `EMPLOYEE`.`name`,
						   `EMPLOYEE`.`surname`,
						   `EMPLOYEE`.`phone_number`,
						   `EMPLOYEE`.`email`,
						   `EMPLOYEE`.`recruitment_date`,
						   `position`.`name` AS `position`,
						   `FITNESS_CLUB`.`name` as `fitness_club`
					FROM `EMPLOYEE`
						LEFT JOIN `position`
							ON `EMPLOYEE`.`position`=`position`.`id_position`
						LEFT JOIN `FITNESS_CLUB`
							ON `EMPLOYEE`.`fk_fitness_club_id`=`FITNESS_CLUB`.`id_fitness_club`" . $limitOffsetString;
		$data = mysql::select($query);

		return $data;
	}

	/**
	 * Darbuotojų kiekio radimas
	 * @return type
	 */
	public function getEmployeesListCount() {
		$query = "  SELECT COUNT(`personal_id`) as `count`
					FROM `EMPLOYEE`";
		$data = mysql::select($query);

		return $data[0]['count'];
	}

	/**
	 * Darbuotojo šalinimas
	 * @param type $id
	 */
	public function deleteEmployee($id) {
		$query = "  DELETE FROM `EMPLOYEE`
					WHERE `personal_id`='{$id}'";
		mysql::query($query);
	}

	/**
	 * Darbuotojo atnaujinimas
	 * @param type $data
	*/
	public function updateEmployee($data) {
		$query = "  UPDATE `EMPLOYEE`
					SET  `name`='{$data['name']}',
						   `surname`='{$data['surname']}',
						   `phone_number`='{$data['phone_number']}',
						   `email`='{$data['email']}',
						   `recruitment_date`='{$data['recruitment_date']}',
						   `position`='{$data['position']}',
						   `fk_fitness_club_id`='{$data['fk_fitness_club_id']}'
					WHERE `personal_id`='{$data['personal_id']}'";
		mysql::query($query);
	}

	/**
	 * Darbuotojo įrašymas
	 * @param type $data
	*/
	public function insertEmployee($data) {
		$query = "  INSERT INTO `EMPLOYEE`
								(
									`personal_id`,
									`name`,
									`surname`,
								  `phone_number`,
								  `email`,
								  `recruitment_date`,
								  `position`,
								  `fk_fitness_club_id`
								)
								VALUES
								(
									'{$data['personal_id']}',
									'{$data['name']}',
									'{$data['surname']}',
									'{$data['phone_number']}',
									'{$data['email']}',
								  '{$data['recruitment_date']}',
									'{$data['position']}',
									'{$data['fk_fitness_club_id']}'
								)";
		mysql::query($query);
	}

	/**
	 * Pareigų sąrašo išrinkimas
	 * @return type
	 */
	public function getPositionList() {
		$query = "  SELECT *
					FROM `position`";
		$data = mysql::select($query);

		return $data;
	}

	/**
	 * Darbuotojų sąskaitų kiekio radimas
	 * @param type $id
	 * @return type
	 */
	public function getAccountsCountOfEmployee($id) {
		$query = "  SELECT COUNT(`PAYMENT`.`nr`) AS `count`
					FROM `EMPLOYEE`
						INNER JOIN `PAYMENT`
							ON `EMPLOYEE`.`personal_id`=`PAYMENT`.`fk_EMPLOYEE_ID`
					WHERE `EMPLOYEE`.`personal_id`='{$id}'";
		$data = mysql::select($query);

		return $data[0]['count'];
	}

}
