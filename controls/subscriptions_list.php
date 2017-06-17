<?php

	// creating Subscription class object
	include 'libraries/subscriptions.class.php';
	$subscrioptionsObj = new subscriptions();

	// creating paging class object
	include 'utils/paging.class.php';
	$paging = new paging(NUMBER_OF_ROWS_IN_PAGE);

	if(!empty($removeId)) {
		$count = $subscrioptionsObj->getAccountsCountOfSubscription($removeId);

		$removeErrorParameter = '';
		if($count == 0) {
			$subscrioptionsObj->deleteSubscription($removeId);

		} else {
			// cannot delete Fitness Club, error notification
			$removeErrorParameter = '&remove_error=1';
		}
		// redirecting to Subscriptions list page
		header("Location: index.php?module={$module}{$removeErrorParameter}");
		die();
	}
?>
<div class="row">
	<ul class="list-inline">
		<li class="list-inline-item"><i class="fa fa-home" aria-hidden="true"></i><a href="index.php"> Home Page</a></li>
		<li class="list-inline-item"><i class="fa fa-angle-right" aria-hidden="true"></i></li>
		<li class="list-inline-item">Subscriptions</li>
	</ul>
</div>
<ul class="list-inline text-right">
	<li class="list-inline-item"><a class="btn btn-info btn-sm" href="report.php?id=1" target="_blank"><i class="fa fa-list-alt" aria-hidden="true"></i> Subscriptions report</a></li>
	<li class="list-inline-item"><a class="btn btn-success btn-sm" href='index.php?module=<?php echo $module; ?>&action=new'><i class="fa fa-plus-circle" aria-hidden="true"></i> Add subscription</a></li>
</ul>
<div class="float-clear"></div>

<?php if(isset($_GET['remove_error'])) { ?>
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<strong>Cannot delete subscription, because it has issued invoice. First delete invoice.</strong>
	</div>
<?php } ?>
<div class="container-fluid">
	<table class="table table-bordered table-striped table-hover">
		<thead class="thead-inverse">
			<tr>
				<th>ID</th>
				<th>Valid from</th>
				<th>Valid till</th>
				<th>Price</th>
				<th>Type</th>
				<th>Customer</th>
				<th style="text-align: center;">Delete/Edit</th>
			</tr>
		</thead>
		<?php
			// counting sum of records
			$elementCount = $subscrioptionsObj->getSubscriptionListCount();

			// generating list pages
			$paging->process($elementCount, $pageId);

			// electing selected page Subscriptions
			$data = $subscrioptionsObj->getSubscriptionList($paging->size, $paging->first);

			// generating table
			foreach($data as $key => $val) {
				echo
					"<tr>"
						. "<td>{$val['id_subscription']}</td>"
						. "<td>{$val['valid_from']}</td>"
						. "<td>{$val['valid_till']}</td>"
						. "<td>{$val['price']}</td>"
						. "<td>{$val['type']}</td>"
						. "<td>{$val['name']} {$val['surname']}</td>"
						. "<td style='text-align: center;'>"
							. "<a class='btn btn-danger btn-sm' href='#' onclick='showConfirmDialog(\"{$module}\", \"{$val['id_subscription']}\"); return false;' title=''><i class='fa fa-trash' aria-hidden='true'></i></a>&nbsp;"
							. "<a class='btn btn-warning btn-sm' href='index.php?module={$module}&id={$val['id_subscription']}' title=''><i class='fa fa-pencil' aria-hidden='true'></i></a>"
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
