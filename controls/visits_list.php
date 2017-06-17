<?php

	// creating Visit class object
	include 'libraries/visits.class.php';
	$visitsObj = new visits();

	// creating paging class object
	include 'utils/paging.class.php';
	$paging = new paging(NUMBER_OF_ROWS_IN_PAGE);

	if(!empty($removeId)) {

		// deleting Visit
		$visitsObj->deleteVisit($removeId);

		// redirecting to Visits page
		header("Location: index.php?module={$module}");
		die();
	}
?>
<div class="row">
	<ul class="list-inline">
		<li class="list-inline-item"><i class="fa fa-home" aria-hidden="true"></i><a href="index.php"> Home Page</a></li>
		<li class="list-inline-item"><i class="fa fa-angle-right" aria-hidden="true"></i></li>
		<li class="list-inline-item">Visits</li>
	</ul>
</div>
<ul class="list-inline text-right">
	<li class="list-inline-item"><a class="btn btn-info btn-sm" href="report.php?id=1" target="_blank"><i class="fa fa-list-alt" aria-hidden="true"></i> Visits report</a></li>
	<li class="list-inline-item"><a class="btn btn-success btn-sm" href='index.php?module=<?php echo $module; ?>&action=new'><i class="fa fa-plus-circle" aria-hidden="true"></i> Add visit</a></li>
</ul>
<div class="float-clear"></div>

<div class="container-fluid">
	<table class="table table-bordered table-striped table-hover">
		<thead class="thead-inverse">
			<tr>
				<th>ID</th>
				<th>Date</th>
				<th>Time</th>
				<th>Customer</th>
				<th>Fitness Club</th>
				<th style="text-align: center;">Delete/Edit</th>
			</tr>
		</thead>
		<?php
			// counting sum of records
			$elementCount = $visitsObj->getVisitListCount();

			// generating list pages
			$paging->process($elementCount, $pageId);

			// electing selected page Visists
			$data = $visitsObj->getVisitList($paging->size, $paging->first);

			// generating table
			foreach($data as $key => $val) {
				echo
					"<tr>"
						. "<td>{$val['id_visit']}</td>"
						. "<td>{$val['visit_date']}</td>"
						. "<td>{$val['time']}</td>"
						. "<td>{$val['name']} {$val['surname']}</td>"
						. "<td>{$val['fitness_club']}</td>"
						. "<td style='text-align: center;'>"
							. "<a class='btn btn-danger btn-sm' href='#' onclick='showConfirmDialog(\"{$module}\", \"{$val['id_visit']}\"); return false;' title=''><i class='fa fa-trash' aria-hidden='true'></i></a>&nbsp;"
							. "<a class='btn btn-warning btn-sm' href='index.php?module={$module}&id={$val['id_visit']}' title=''><i class='fa fa-pencil' aria-hidden='true'></i></a>"
						. "</td>"
					. "</tr>";
			}
		?>
	</table>
</div>
<?php
//including pages template
	include 'controls/paging.php';
?>
