<?php

	// sukuriame sporto klubų klasės objektą
	include 'libraries/gyms.class.php';
	$gymsObj = new gyms();

	// sukuriame puslapiavimo klasės objektą
	include 'utils/paging.class.php';
	$paging = new paging(NUMBER_OF_ROWS_IN_PAGE);

	if(!empty($removeId)) {
		// patikriname, ar šalinama markė nepriskirta modeliui
		$count = $gymsObj->getEmployeesCountOfGym($removeId);

		$removeErrorParameter = '';
		if($count == 0) {
			// šaliname sporto klubą
			$gymsObj->deleteGym($removeId);
			$gymsObj->deleteAddress($removeId);

		} else {
			// nepašalinome, nes markė priskirta modeliui, rodome klaidos pranešimą
			$removeErrorParameter = '&remove_error=1';
		}
		// nukreipiame į sporto klubų puslapį
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
		Sporto klubas nebuvo pašalintas. Pirmiausia pašalinkite darbuotojus.
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
		// suskaičiuojame bendrą įrašų kiekį
		$elementCount = $gymsObj->getGymListCount();

		// suformuojame sąrašo puslapius
		$paging->process($elementCount, $pageId);

		// išrenkame nurodyto puslapio sporto klubus
		$data = $gymsObj->getGymsList($paging->size, $paging->first);

		// suformuojame lentelę
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
	// įtraukiame puslapių šabloną
	include 'controls/paging.php';
?>
