<?php

/**
 * page list creation class
 *
 * @author ISK
 */

class paging {
	private $pageRange;

	public $first;
	public $size;

	public $totalRecords;
	public $totalPages;

	public $data = array();

	/**
	* @desc Constructor
	* @param int how many records will be showed in page
	*/
	public function __construct($rowsPerPage) {
		$this->size = $rowsPerPage;
		$this->pageRange = 5;
	}

	/**
	* @desc Pages formation
	* @param int all records in the list
	* @param int selected page
	*/
	public function process($total, $currentPage) {
		// counting pages
		$pageCount = ceil($total / $this->size);

		// creating statistics
		$this->totalRecords = (int) $total;
		$this->totalPages = (int) ($pageCount) ? $pageCount : 1;
		$this->first = ($currentPage - 1) * $this->size;

		// Pages formation
		for($i = 1; $i <= $pageCount; $i++) {
			$row['isActive'] = ($i == $currentPage) ? 1 : 0;
			$row['page'] = $i;

			$this->data[] = $row;
		}
	}
}

?>
