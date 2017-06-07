<?php

	// creating Payment class object
	include 'libraries/payments.class.php';
	$paymentsObj = new payments();

	// creating paging class object
	include 'utils/paging.class.php';
	$paging = new paging(NUMBER_OF_ROWS_IN_PAGE);

	if(!empty($removeId)) {
		// deleting Invoice and payment
		$paymentsObj->deletePayment($removeId);
		$paymentsObj->deleteAccount($removeId);
		// redirecting to Invoice page
		header("Location: index.php?module={$module}");
		die();
	}
?>
<ul id="pagePath">
	<li><a href="index.php">Home Page</a></li>
	<li>Payments</li>
</ul>
<div id="actions">
	<a href="report.php?id=2" target="_blank">Invoices report</a>
	<a href='index.php?module=<?php echo $module; ?>&action=new'>Add invoice</a>
</div>
<div class="float-clear"></div>

<table>
	<tr>
		<th>ID</th>
		<th>Date</th>
		<th>Amount</th>
		<th>Payed</th>
		<th>Employee</th>
		<th>Customer</th>
		<th>Subscription</th>
		<th></th>
	</tr>
	<?php
		// counting sum of records
		$elementCount = $paymentsObj->getPaymentListCount();

		// generating list pages
		$paging->process($elementCount, $pageId);

		// electing selected page Payment
		$data = $paymentsObj->getPaymentList($paging->size, $paging->first);

		// generating table
		foreach($data as $key => $val) {
			echo
				"<tr>"
					. "<td>{$val['number']}</td>"
					. "<td>{$val['invoice_date']}</td>"
					. "<td>{$val['invoice_amount']}</td>"
					. "<td>{$val['payment_amount']}</td>"
					. "<td>{$val['employee_name']} {$val['employee_surname']}</td>"
					. "<td>{$val['customer_name']} {$val['customer_surname']}</td>"
					. "<td>{$val['subscription']}</td>"
					. "<td>"
						. "<a href='#' onclick='showConfirmDialog(\"{$module}\", \"{$val['number']}\"); return false;' title=''>delete</a>&nbsp;"
						. "<a href='index.php?module={$module}&id={$val['number']}' title=''>edit</a>"
					. "</td>"
				. "</tr>";
		}
	?>
</table>
<?php
//including pages template
	include 'controls/paging.php';
?>
