<?php

	// creating Employee class object
	include 'libraries/employees.class.php';
	$employeesObj = new employees();

	// creating paging class object
	include 'utils/paging.class.php';
	$paging = new paging(NUMBER_OF_ROWS_IN_PAGE);

	if(!empty($removeId)) {
		// checking if employee which is going to be deleted is not assigned to any subscriptions
		$count = $employeesObj->getAccountsCountOfEmployee($removeId);

		$removeErrorParameter = '';
		if($count == 0) {
			// deleting employee
			$employeesObj->deleteEmployee($removeId);
		} else {
			// nepašalinome, nes darbuotojas sudaręs bent vieną sutartį, rodome klaidos pranešimą
			$removeErrorParameter = '&remove_error=1';
		}

		// redirecting to Employees page
		header("Location: index.php?module={$module}{$removeErrorParameter}");
		die();
	}
?>
<div class="row">
	<ul class="list-inline">
		<li class="list-inline-item"><i class="fa fa-home" aria-hidden="true"></i><a href="index.php"> Home Page</a></li>
		<li class="list-inline-item"><i class="fa fa-angle-right" aria-hidden="true"></i></li>
		<li class="list-inline-item">Employees</li>
	</ul>
</div>
<ul class="list-inline text-right">
	<li class="list-inline-item"><a class="btn btn-success btn-sm" href='index.php?module=<?php echo $module; ?>&action=new'><i class="fa fa-plus-circle" aria-hidden="true"></i> Add employee</a></li>
</ul>
<div class="float-clear"></div>

<?php if(isset($_GET['remove_error'])) { ?>
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<strong>Cannot delete Employee. First delete his issued invoices.</strong>
	</div>
<?php } ?>

<div class="container-fluid">
	<table class="table table-bordered table-striped table-hover">
		<thead class="thead-inverse">
			<tr>
				<th>Personal ID</th>
				<th>Name</th>
				<th>Surname</th>
				<th>Phone number</th>
				<th>Email</th>
				<th>Requitment date</th>
				<th>Position</th>
				<!--<th>Fitness Club</th>-->
				<th style="text-align: center;">Delete/Edit</th>
			</tr>
		</thead>
		<?php
			// counting sum of records
			$elementCount = $employeesObj->getEmployeesListCount();

			// generating list pages
			$paging->process($elementCount, $pageId);

			// electing selected page Employees
			$data = $employeesObj->getEmployeesList($paging->size, $paging->first);

			// generating table
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
						//. "<td>{$val['fitness_club']}</td>"
						. "<td style='text-align: center;'>"
							. "<a class='btn btn-danger btn-sm' href='#' onclick='showConfirmDialog(\"{$module}\", \"{$val['personal_id']}\"); return false;' title=''><i class='fa fa-trash' aria-hidden='true'></i></a>&nbsp;"
							. "<a class='btn btn-warning btn-sm' href='index.php?module={$module}&id={$val['personal_id']}' title=''><i class='fa fa-pencil' aria-hidden='true'></i></a>"
						. "</td>"
					. "</tr>";
			}
		?>
	</table>
</div>

<?php
	// including pages template
	include 'controls/paging.php';
?>
