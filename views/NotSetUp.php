<?php
use Dragon\Url;
?>
<div class="updated" id="fancy_setup_prompt">
	<form name="fancy_activate" action="<?= Url::getAdminMenuLink('indicative-settings'); ?>" method="POST">
		<div class="fancy_activate">
			<!-- <div class="fancy_letter">A</div> -->
			<div class="fancy_button_container">
				<div class="fancy_button_border">
					<input type="submit" class="fancy_button" value="Setup Indicative">
				</div>
			</div>
			<div class="fancy_description"><strong>Almost done</strong> - configure Indicative and automatically send data to your Indicative account.</div>
		</div>
	</form>
</div>
