<?php

/**
 * Visits editing class
 *
 * @author Gedas_Gardauskas
 */

class visits {

	public function __construct() {

	}

	/**
	 * Finding Visit by id
	 * @param type $id
	 * @return type
	 */
	public function getVisit($id) {
		$query = "  SELECT *
					FROM `VISIT`
					WHERE `VISIT`.`id_visit`='{$id}' GROUP BY `VISIT`.`id_visit`";
		$data = mysql::select($query);

		return $data[0];
	}

	/**
	 * Finding Visits list
	 * @param type $limit
	 * @param type $offset
	 * @return type
	 */
	public function getVisitList($limit = null, $offset = null) {
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
		}
		if(isset($offset)) {
			$limitOffsetString .= " OFFSET {$offset}";
		}

		$query = "  SELECT `VISIT`.`id_visit`,
						   `VISIT`.`visit_date`,
						   `VISIT`.`time`,
						   `CUSTOMER`.`name` AS `name`,
						   `CUSTOMER`.`surname` AS `surname`,
						   `FITNESS_CLUB`.`name` AS `fitness_club`
					FROM `VISIT`
						LEFT JOIN `CUSTOMER`
							ON `VISIT`.`fk_customer_id`=`CUSTOMER`.`personal_id`
						LEFT JOIN `FITNESS_CLUB`
							ON `VISIT`.`fk_fitness_club_id`=`FITNESS_CLUB`.`id_fitness_club`
					ORDER BY `VISIT`.`id_visit`" . $limitOffsetString;
		$data = mysql::select($query);

		return $data;
	}

	/**
	 * Finding Visits list count
	 * @return type
	 */
	public function getVisitListCount() {
		$query = "  SELECT COUNT(`id_visit`) AS `count`
					FROM `VISIT`
						LEFT JOIN `CUSTOMER`
							ON `VISIT`.`fk_customer_id`=`CUSTOMER`.`personal_id`
						LEFT JOIN `FITNESS_CLUB`
							ON `VISIT`.`fk_fitness_club_id`=`FITNESS_CLUB`.`id_fitness_club`";
		$data = mysql::select($query);

		return $data[0]['count'];
	}

	/**
	 * Deleting Visit by id
	 * @param type $id
	 */
	public function deleteVisit($id) {
		$query = "  DELETE FROM `VISIT`
					WHERE `id_visit`='{$id}'";
		mysql::query($query);
	}


	/**
	 * Updaating Visit
	 * @param type $data
	*/
	public function updateVisit($data) {
		$query = "  UPDATE `VISIT`
					SET  `visit_date`='{$data['visit_date']}',
						   `time`='{$data['time']}',
						   `fk_customer_id`='{$data['fk_customer_id']}',
						   `fk_fitness_club_id`='{$data['fk_fitness_club_id']}'
					WHERE `id_visit`='{$data['id_visit']}'";
		mysql::query($query);
	}

	/**
	 * Insterting new Visit
	 * @param type $data
	 */
	public function insertVisit($data) {
		$query = "  INSERT INTO `VISIT`
								(
									`id_visit`,
									`visit_date`,
									`time`,
									`fk_customer_id`,
									`fk_fitness_club_id`
								)
								VALUES
								(
									'{$data['id_visit']}',
									'{$data['visit_date']}',
									'{$data['time']}',
									'{$data['fk_customer_id']}',
									'{$data['fk_fitness_club_id']}'
								)";
		mysql::query($query);
	}

	/**
	 * Finding Visit max id value
	 * @return type
	 */
	public function getMaxIdOfVisit() {
		$query = "  SELECT MAX(`id_visit`) AS `latestId`
					FROM `VISIT`";
		$data = mysql::select($query);

		return $data[0]['latestId'];
	}

	public function getVisitsReport($dateFrom, $dateTo) {
		$whereClauseString = "";
		if(!empty($dateFrom)) {
			$whereClauseString .= " WHERE `VISIT`.`visit_date`>='{$dateFrom}'";
			if(!empty($dateTo)) {
				$whereClauseString .= " AND `VISIT`.`visit_date`<='{$dateTo}'";
			}
		} else {
			if(!empty($dateTo)) {
				$whereClauseString .= " WHERE `VISIT`.`visit_date`<='{$dateTo}'";
			}
		}

		$query = "  SELECT `VISIT`.`id_visit`,
						   `VISIT`.`visit_date`,
						   `VISIT`.`time`,
						   `CUSTOMER`.`name` AS `name`,
						   `CUSTOMER`.`surname` AS `surname`,
						   `FITNESS_CLUB`.`name` AS `fitness_club`
					FROM `VISIT`
						INNER JOIN `CUSTOMER`
							ON `VISIT`.`fk_customer_id`=`CUSTOMER`.`personal_id`
							INNER JOIN `FITNESS_CLUB`
								ON `VISIT`.`fk_fitness_club_id`=`FITNESS_CLUB`.`id_fitness_club`
					{$whereClauseString}
					ORDER BY `VISIT`.`visit_date` ASC,
							 `VISIT`.`time` ASC";
		$data = mysql::select($query);

		return $data;
	}

	public function getCountOfVisitsReport($dateFrom, $dateTo) {
		$whereClauseString = "";
		if(!empty($dateFrom)) {
			$whereClauseString .= " WHERE `VISIT`.`visit_date`>='{$dateFrom}'";
			if(!empty($dateTo)) {
				$whereClauseString .= " AND `VISIT`.`visit_date`<='{$dateTo}'";
			}
		} else {
			if(!empty($dateTo)) {
				$whereClauseString .= " WHERE `VISIT`.`visit_date`<='{$dateTo}'";
			}
		}

		$query = "  SELECT COUNT(`id_visit`) AS `count`
					FROM `VISIT`
						INNER JOIN `CUSTOMER`
							ON `VISIT`.`fk_customer_id`=`CUSTOMER`.`personal_id`
							INNER JOIN `FITNESS_CLUB`
								ON `VISIT`.`fk_fitness_club_id`=`FITNESS_CLUB`.`id_fitness_club`
					{$whereClauseString}";
		$data = mysql::select($query);

		return $data;
	}

}
