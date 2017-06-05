<?php

	// sukuriame klientų klasės objektą
	include 'libraries/customers.class.php';
	$customersObj = new customers();

	// sukuriame puslapiavimo klasės objektą
	include 'utils/paging.class.php';
	$paging = new paging(NUMBER_OF_ROWS_IN_PAGE);

	if(!empty($removeId)) {
		// patikriname, ar klientas neturi abonemento
		$count = $customersObj->getSubscriptionsCountOfCustomer($removeId);

		$removeErrorParameter = '';
		if($count == 0) {
			// šaliname klientą
			$customersObj->deleteCustomer($removeId);
		} else {
			// nepašalinome, nes klientas sudaręs bent vieną sutartį, rodome klaidos pranešimą
			$removeErrorParameter = '&remove_error=1';
		}

		// nukreipiame į klientų puslapį
		header("Location: index.php?module={$module}{$removeErrorParameter}");
		die();
	}
?>
<ul id="pagePath">
	<li><a href="index.php">Home Page</a></li>
	<li>Customers</li>
</ul>
<div id="actions">
	<a href='index.php?module=<?php echo $module; ?>&action=new'>Add custormer</a>
</div>
<div class="float-clear"></div>

<?php if(isset($_GET['remove_error'])) { ?>
	<div class="errorBox">
		Klientas nebuvo pašalintas, nes turi abonementą.
	</div>
<?php } ?>

<table>
	<tr>
		<th>Foto</th>
		<th>Personal ID</th>
		<th>Name</th>
		<th>Surname</th>
		<th>Phone number</th>
		<th>Email</th>
		<th>First registration</th>
		<th>Social Status</th>
		<th></th>
	</tr>
	<?php
		// suskaičiuojame bendrą įrašų kiekį
		$elementCount = $customersObj->getCustomersListCount();

		// suformuojame sąrašo puslapius
		$paging->process($elementCount, $pageId);

		// išrenkame nurodyto puslapio klientus
		$data = $customersObj->getCustomersList($paging->size, $paging->first);

		// suformuojame lentelę
		foreach($data as $key => $val) {
			echo
				"<tr>"
					. "<td>{$val['foto']}</td>"
					. "<td>{$val['personal_id']}</td>"
					. "<td>{$val['name']}</td>"
					. "<td>{$val['surname']}</td>"
					. "<td>{$val['phone_number']}</td>"
					. "<td>{$val['email']}</td>"
					. "<td>{$val['first_registration']}</td>"
					. "<td>{$val['social_status']}</td>"
					. "<td>"
						. "<a href='#' onclick='showConfirmDialog(\"{$module}\", \"{$val['personal_id']}\"); return false;' title=''>delete</a>&nbsp;"
						. "<a href='index.php?module={$module}&id={$val['personal_id']}' title=''>edit</a>"
					. "</td>"
				. "</tr>";
		}
	?>
</table>

<?php
	// įtraukiame puslapių šabloną
	include 'controls/paging.php';
?>
