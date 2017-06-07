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
<ul id="pagePath">
	<li><a href="index.php">Home Page</a></li>
	<li>Subscriptions</li>
</ul>
<div id="actions">
	<a href="report.php?id=3" target="_blank">Subscriptions report</a>
	<a href='index.php?module=<?php echo $module; ?>&action=new'>Add subscriptions</a>
</div>
<div class="float-clear"></div>

<?php if(isset($_GET['remove_error'])) { ?>
	<div class="errorBox">
		Cannot delete subscription, because it has issued invoice. First delete invoice. 
	</div>
<?php } ?>

<table>
	<tr>
		<th>ID</th>
		<th>Valid from</th>
		<th>till</th>
		<th>Price</th>
		<th>Type</th>
		<th>Customer</th>
		<th></th>
	</tr>
	<?php
		// suskaičiuojame bendrą įrašų kiekį
		$elementCount = $subscrioptionsObj->getSubscriptionListCount();

		// suformuojame sąrašo puslapius
		$paging->process($elementCount, $pageId);

		// išrenkame nurodyto puslapio sutartis
		$data = $subscrioptionsObj->getSubscriptionList($paging->size, $paging->first);

		// suformuojame lentelę
		foreach($data as $key => $val) {
			echo
				"<tr>"
					. "<td>{$val['id_subscription']}</td>"
					. "<td>{$val['valid_from']}</td>"
					. "<td>{$val['valid_till']}</td>"
					. "<td>{$val['price']}</td>"
					. "<td>{$val['type']}</td>"
					. "<td>{$val['name']} {$val['surname']}</td>"
					. "<td>"
						. "<a href='#' onclick='showConfirmDialog(\"{$module}\", \"{$val['id_subscription']}\"); return false;' title=''>delete</a>&nbsp;"
						. "<a href='index.php?module={$module}&id={$val['id_subscription']}' title=''>edit</a>"
					. "</td>"
				. "</tr>";
		}
	?>
</table>

<?php include 'controls/paging.php'; ?>
