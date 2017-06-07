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
<ul id="pagePath">
	<li><a href="index.php">Home Page</a></li>
	<li>Visits</li>
</ul>
<div id="actions">
	<a href="report.php?id=1" target="_blank">Visits report</a>
	<a href='index.php?module=<?php echo $module; ?>&action=new'>Add visit</a>
</div>
<div class="float-clear"></div>

<table>
	<tr>
		<th>ID</th>
		<th>Date</th>
		<th>Time</th>
		<th>Customer</th>
		<th>Fitness Club</th>
		<th></th>
	</tr>
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
					. "<td>"
						. "<a href='#' onclick='showConfirmDialog(\"{$module}\", \"{$val['id_visit']}\"); return false;' title=''>delete</a>&nbsp;"
						. "<a href='index.php?module={$module}&id={$val['id_visit']}' title=''>edit</a>"
					. "</td>"
				. "</tr>";
		}
	?>
</table>
<?php
//including pages template
	include 'controls/paging.php';
?>
