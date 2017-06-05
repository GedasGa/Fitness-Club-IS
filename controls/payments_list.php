<?php

	// sukuriame sutarčių klasės objektą
	include 'libraries/payments.class.php';
	$paymentsObj = new payments();

	// sukuriame puslapiavimo klasės objektą
	include 'utils/paging.class.php';
	$paging = new paging(NUMBER_OF_ROWS_IN_PAGE);

	if(!empty($removeId)) {
		// šaliname sąskaitą ir mokėjimą
		$paymentsObj->deletePayment($removeId);
		$paymentsObj->deleteAccount($removeId);
		// nukreipiame į sutarčių puslapį
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
		// suskaičiuojame bendrą įrašų kiekį
		$elementCount = $paymentsObj->getPaymentListCount();

		// suformuojame sąrašo puslapius
		$paging->process($elementCount, $pageId);

		// išrenkame nurodyto puslapio sutartis
		$data = $paymentsObj->getPaymentList($paging->size, $paging->first);

		// suformuojame lentelę
		foreach($data as $key => $val) {
			echo
				"<tr>"
					. "<td>{$val['number']}</td>"
					. "<td>{$val['invoice_date']}</td>"
					. "<td>{$val['invoice_amount']}</td>"
					. "<td>{$val['amount']}</td>"
					. "<td>{$val['employee_name']} {$val['employee_surname']}</td>"
					. "<td>{$val['customer_name']} {$val['customer_surname']}</td>"
					. "<td>{$val['subscription']}</td>"
					. "<td>"
						. "<a href='#' onclick='showConfirmDialog(\"{$module}\", \"{$val['number']}\"); return false;' title=''>delete</a>&nbsp;"
						. "<a href='index.php?module={$module}&id={$val['id_payment']}' title=''>edit</a>"
					. "</td>"
				. "</tr>";
		}
	?>
</table>

<?php include 'controls/paging.php'; ?>
