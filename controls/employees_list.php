<?php

	// sukuriame darbuotojų klasės objektą
	include 'libraries/employees.class.php';
	$employeesObj = new employees();

	// sukuriame puslapiavimo klasės objektą
	include 'utils/paging.class.php';
	$paging = new paging(NUMBER_OF_ROWS_IN_PAGE);

	if(!empty($removeId)) {
		// patikriname, ar darbuotojas neturi sudarytų sutarčių
		$count = $employeesObj->getAccountsCountOfEmployee($removeId);

		$removeErrorParameter = '';
		if($count == 0) {
			// šaliname darbuotoją
			$employeesObj->deleteEmployee($removeId);
		} else {
			// nepašalinome, nes darbuotojas sudaręs bent vieną sutartį, rodome klaidos pranešimą
			$removeErrorParameter = '&remove_error=1';
		}

		// nukreipiame į darbuotojų puslapį
		header("Location: index.php?module={$module}{$removeErrorParameter}");
		die();
	}
?>
<ul id="pagePath">
	<li><a href="index.php">Home Page</a></li>
	<li>Employees</li>
</ul>
<div id="actions">
	<a href='index.php?module=<?php echo $module; ?>&action=new'>Add employee</a>
</div>
<div class="float-clear"></div>

<?php if(isset($_GET['remove_error'])) { ?>
	<div class="errorBox">
		Darbuotojas nebuvo pašalintas, nes yra sudaręs sąskaitą.
	</div>
<?php } ?>

<table>
	<tr>
		<th>Personal ID</th>
		<th>Name</th>
		<th>Surname</th>
		<th>Phone number</th>
		<th>Email</th>
		<th>Recruitment date</th>
		<th>Position</th>
		<th>Fitness Club</th>
		<th></th>
	</tr>
	<?php
		// suskaičiuojame bendrą įrašų kiekį
		$elementCount = $employeesObj->getEmployeesListCount();

		// suformuojame sąrašo puslapius
		$paging->process($elementCount, $pageId);

		// išrenkame nurodyto puslapio darbuotojus
		$data = $employeesObj->getEmployeesList($paging->size, $paging->first);

		// suformuojame lentelę
		foreach($data as $key => $val) {
			echo
				"<tr>"
					. "<td>{$val['personal_id']}</td>"
					. "<td>{$val['name']}</td>"
					. "<td>{$val['surname']}</td>"
					. "<td>{$val['phone_number']}</td>"
					. "<td>{$val['email']}</td>"
					. "<td>{$val['recruitment_date']}</td>"
					. "<td>{$val['position']}</td>"
					. "<td>{$val['fitness_club']}</td>"
					. "<td>"
						. "<a href='#' onclick='showConfirmDialog(\"{$module}\", \"{$val['personal_id']}\"); return false;' title=''>delete</a>&nbsp;"
						. "<a href='index.php?module={$module}&id={$val['personal_id']}' title=''>edit</a>"
					. "</td>"
				. "</tr>";
		}
	?>
</table>

<?php
	// įtraukiame puslapių šabloną
	include 'controls/paging.php';
?>
