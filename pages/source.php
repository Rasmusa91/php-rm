<div class = "darkWrapper2 formStyle">
	<div class = "wrapperTitle">Visa källkod för den här sidan</div>
	<div class = "darkWrapper">
		<?php
			$source = new CSource(array('secure_dir' => '..', 'base_dir' => '..'));
			echo $source->View();
		?>
	</div>
</div>