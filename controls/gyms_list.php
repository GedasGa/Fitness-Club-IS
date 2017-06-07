<?php

	// creating Fitness Club class object
	include 'libraries/gyms.class.php';
	$gymsObj = new gyms();

	// creating paging class object
	include 'utils/paging.class.php';
	$paging = new paging(NUMBER_OF_ROWS_IN_PAGE);

	if(!empty($removeId)) {
		// checking, if Fitness Club which is going to be deleted is not assigned to any employee
		$count = $gymsObj->getEmployeesCountOfGym($removeId);

		$removeErrorParameter = '';
		if($count == 0) {
			// deleting Fitness Club
			$gymsObj->deleteGym($removeId);
			$gymsObj->deleteAddress($removeId);

		} else {
			// cannot delete Fitness Club, error notification
			$removeErrorParameter = '&remove_error=1';
		}
		// redirecting to Fitness Clubs page
		header("Location: index.php?module={$module}{$removeErrorParameter}");
		die();
	}
?>
<ul id="pagePath">
	<li><a href="index.php">Home Page</a></li>
	<li>Fitness Club</li>
</ul>
<div id="actions">
	<a href='index.php?module=<?php echo $module; ?>&action=new'>Add Fitness Club</a>
</div>
<div class="float-clear"></div>

<?php if(isset($_GET['remove_error'])) { ?>
	<div class="errorBox">
		Cannot delete Fitness Club, first delete all employees.
	</div>
<?php } ?>

<table>
	<tr>
		<th>ID</th>
		<th>Name</th>
		<th>Features</th>
		<th>Address</th>
		<th>City</th>
		<th>Post code</th>
		<th></th>
		<th></th>
	</tr>
	<?php
		// counting sum of records
		$elementCount = $gymsObj->getGymListCount();

		// generating list pages
		$paging->process($elementCount, $pageId);

		//  electing selected page Fitness Clubs
		$data = $gymsObj->getGymsList($paging->size, $paging->first);

		// generating table
		foreach($data as $key => $val) {
			echo
				"<tr>"
					. "<td>{$val['id_fitness_club']}</td>"
					. "<td>{$val['name']}</td>"
					. "<td>{$val['features']}</td>"
					. "<td>{$val['house_number']} {$val['street']}</td>"
					. "<td>{$val['city']}</td>"
					. "<td>{$val['post_code']}</td>"
					. "<td>"
						. "<a href='#' onclick='showConfirmDialog(\"{$module}\", \"{$val['id_fitness_club']}\"); return false;' title=''>delete</a>&nbsp;"
						. "<a href='index.php?module={$module}&id={$val['id_fitness_club']}' title=''>edit</a>"
					. "</td>"
				. "</tr>";
		}
	?>
</table>

<?php
	// including pages template
	include 'controls/paging.php';
?>
