<div class="row justify-content-center">

	<ul class="pagination">
		<?php
			foreach ($paging->data as $key => $value) {
					$lastPage = count($paging->data);
					$previousPage = $value['page'] - 1;

					//Representing going to firt page
					for($i = 1; $i <= $lastPage; $i++){
						$disabledClass = "";
						if($value['page'] == $i && $value['isActive'] == 1) {
							if($i == 1) {
								$disabledClass = "disabled";
							}
							echo "<li class='page-item $disabledClass'> <a class='page-link' href='index.php?module={$module}&page={$previousPage}'><span aria-hidden='true'>&laquo;</span><span class='sr-only'>Previous</span></a></li>";
						}
					}
				}

			foreach ($paging->data as $key => $value) {
				$activeClass = "";

				//Represent pages and selected page
				if($value['isActive'] == 1) {
					$activeClass = " bg-info text-white";
				}
				echo "<li class='page-item'><a class='page-link {$activeClass}' href='index.php?module={$module}&page={$value['page']}' title=''>{$value['page']}</a></li>";

			}

			foreach ($paging->data as $key => $value) {
				$activeClass = "";
				$lastPage = count($paging->data);
				$nextPage = $value['page'] + 1;

				//Representing going to the next page
				for($i = 1; $i <= $lastPage; $i++){
					$disabledClass = "";
					if($value['page'] == $i && $value['isActive'] == 1){
						if($i == $lastPage ){
							$disabledClass = "disabled";
						}
						echo "<li class='page-item $disabledClass'> <a class='page-link' href='index.php?module={$module}&page={$nextPage}'><span aria-hidden='true'>&raquo;</span><span class='sr-only'>Next</span></a></li>";
					}
				}
			} ?>
	</ul>
</div>
