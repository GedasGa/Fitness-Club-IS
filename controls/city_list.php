<?php

	// creating City class object
	include 'libraries/city.class.php';
	$cityObj = new city();

	// creating paging class object
	include 'utils/paging.class.php';
	$paging = new paging(NUMBER_OF_ROWS_IN_PAGE);

	if(!empty($removeId)) {
		// checking if city which is going to be deleted is not assigned to any address
		$count = $cityObj->getAddressCountOfCity($removeId);

		$removeErrorParameter = '';
		if($count == 0) {
			// deleting city
			$cityObj->deleteCity($removeId);

		} else {
			// cannot delete because City is assigned to address, error notification
			$removeErrorParameter = '&remove_error=1';
		}

		// redirecting to Fitness Club page
		header("Location: index.php?module={$module}{$removeErrorParameter}");
		die();
	}
?>
<ul id="pagePath">
	<li><a href="index.php">Home Page</a></li>
	<li>Cities</li>
</ul>
<div id="actions">
	<a href='index.php?module=<?php echo $module; ?>&action=new'>Add city</a>
</div>
<div class="float-clear"></div>

<?php if(isset($_GET['remove_error'])) { ?>
	<div class="errorBox">
		Miestas nebuvo pašalintas. Pirmiausia pašalinkite sportus klubus.
	</div>
<?php } ?>

<table>
	<tr>
		<th>ID</th>
		<th>City</th>
		<th></th>
		<th></th>
	</tr>
	<?php
		// counting sum of records
		$elementCount = $cityObj->getCityListCount();

		// generating list pages
		$paging->process($elementCount, $pageId);

		// electing selected page Fitness Clubs
		$data = $cityObj->getCityList($paging->size, $paging->first);

		// generating table
		foreach($data as $key => $val) {
			echo
				"<tr>"
					. "<td>{$val['id_city']}</td>"
					. "<td>{$val['city']}</td>"
					. "<td>"
						. "<a href='#' onclick='showConfirmDialog(\"{$module}\", \"{$val['id_city']}\"); return false;' title=''>delete</a>&nbsp;"
						. "<a href='index.php?module={$module}&id={$val['id_city']}' title=''>edit</a>"
					. "</td>"
				. "</tr>";
		}
	?>
</table>

<?php
	// including pages template
	include 'controls/paging.php';
?>
