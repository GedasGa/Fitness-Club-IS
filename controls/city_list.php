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

		//redirecting to Fitness Club page
		header("Location: index.php?module={$module}{$removeErrorParameter}");
		die();
	}
?>
<div class="row">
	<ul class="list-inline">
		<li class="list-inline-item"><i class="fa fa-home" aria-hidden="true"></i><a href="index.php"> Home Page</a></li>
		<li class="list-inline-item"><i class="fa fa-angle-right" aria-hidden="true"></i></li>
		<li class="list-inline-item">Cities</li>
	</ul>
</div>
<ul class="list-inline text-right">
	<li class="list-inline-item"><a class="btn btn-success btn-sm" href='index.php?module=<?php echo $module; ?>&action=new'><i class="fa fa-plus-circle" aria-hidden="true"></i> Add city</a></li>
</ul>
<div class="float-clear"></div>

<?php if(isset($_GET['remove_error'])) { ?>
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<strong>Cannot delete city. First delete Fitness Clubs in the city.</strong>
	</div>
<?php } ?>

<div class="container-fluid">
	<table class="table table-bordered table-striped table-hover">
		<thead class="thead-inverse">
			<tr>
				<th>ID</th>
				<th>City</th>
				<th style="text-align: center;">Delete/Edit</th>
			</tr>
		</thead>
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
						. "<td style='text-align: center;' >"
							. "<a class='btn btn-danger btn-sm' href='#' onclick='showConfirmDialog(\"{$module}\", \"{$val['id_city']}\"); return false;' title=''><i class='fa fa-trash' aria-hidden='true'></i></a>&nbsp;"
							. "<a class='btn btn-warning btn-sm' href='index.php?module={$module}&id={$val['id_city']}' title=''><i class='fa fa-pencil' aria-hidden='true'></i></a>"
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
