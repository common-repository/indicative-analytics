<?php

if (array_key_exists('notice', $pageData)) {
	echo $pageData['notice'];
}
?>

<div class="card card-full-width">
<h1><img src="<?= dragonAssets('img/indicative-logo.png'); ?>" /></h1>
	<form method="POST">
		<input name="indicative_nonce" value="<?php echo wp_create_nonce('indicative_nonce') ?>" type="hidden" />
		<table class="form-table">
			<tr>
				<td colspan="2">
					<h2><?= get_admin_page_title(); ?></h2>
				</td>
			</tr>
			<tr>
				<td>
					<input name="indicative_clear_log" type="submit" value="Clear Log" class="button button-primary" />
				</td>
			</tr>
			<tr>
				<td>
					<textarea cols="150" rows="40"><?= $pageData['log']; ?></textarea>
				</td>
			</tr>
			<tr>
				<td>
					<input name="indicative_clear_log" type="submit" value="Clear Log" class="button button-primary" />
				</td>
			</tr>
		</table>
	</form>
</div>