<?php

	// sukuriame sporto klubų klasės objektą
	include 'libraries/city.class.php';
	$cityObj = new city();

	// sukuriame puslapiavimo klasės objektą
	include 'utils/paging.class.php';
	$paging = new paging(NUMBER_OF_ROWS_IN_PAGE);

	if(!empty($removeId)) {
		// patikriname, ar šalinama city nepriskirta adresui
		$count = $cityObj->getAddressCountOfCity($removeId);

		$removeErrorParameter = '';
		if($count == 0) {
			// šaliname miestą
			$cityObj->deleteCity($removeId);

		} else {
			// nepašalinome, nes city priskirtas adresui, rodome klaidos pranešimą
			$removeErrorParameter = '&remove_error=1';
		}

		// nukreipiame į sporto klubų puslapį
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
		// suskaičiuojame bendrą įrašų kiekį
		$elementCount = $cityObj->getCityListCount();

		// suformuojame sąrašo puslapius
		$paging->process($elementCount, $pageId);

		// išrenkame nurodyto puslapio sporto klubus
		$data = $cityObj->getCityList($paging->size, $paging->first);

		// suformuojame lentelę
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
	// įtraukiame puslapių šabloną
	include 'controls/paging.php';
?>
