<?PHP

use Dragon\DragonException;
use Dragon\DropDown;

global $pmpro_levels;

if (array_key_exists('notice', $pageData)) {
	echo $pageData['notice'];
}
?>

<div class="card card-full-width">
	<div class="indicative-intro">
		<img class="indicative-logo" src="<?= dragonAssets('img/indicative-logo.png'); ?>" /> 		
		<h1><?= __('Indicative Settings', 'indicative') ?></h1>			
	</div>
	<p>
			<?= __('Indicative is a customer analytics platform designed for marketing and product teams 
			looking to optimize customer conversion, engagement, and retention through actionable insights. 
			Indicative integrates all sources of customer data together (e.g. websites, mobile apps, marketing 
			automation, CRM, help desk, etc.), and enables complex data analysis without any SQL or coding knowledge, 
			making it easy to use. Interested in full analytics access and 1 billion user actions per month, for free.', 'indicative') ?>
		<a href="https://www.indicative.com/?utm_source=partners&utm_medium=integration&utm_campaign=wordpressplugin" target="_blank">
				<?= __('Learn More!', 'indicative') ?>
		</a>
	</p>

	<form method="POST">
		<input name="indicative_nonce" value="<?php echo wp_create_nonce('indicative_nonce') ?>" type="hidden" />
		<table class="form-table">
			
			<tr class="form-field form-required">
				<th scope="row">
					<label for="indicative_api_key">
						<?= __('Indicative API Key', 'indicative') ?> <span class="description">(<?= __('required', 'indicative') ?>)</span>
					</label>
				</th>
				<td>
					<?php
						$apiKey = get_option('indicative_api_key', null);
						try {$key = dragonDecrypt($apiKey);} catch (DragonException $e) {$key = $apiKey;}
						$key = empty($key) ? $apiKey : $key;
					?>
					<input name="indicative_api_key" type="text" value="<?php echo $key; ?>" />
					<p class="description">
						<a href="https://app.indicative.com/#/onboarding/wordpress" target="_blank">
							<?= __('Get your API key.', 'indicative') ?>
						</a>
					</p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row">
					<label for="indicative_code_snippet_placement">
						<?= __('Code Snippet Placement', 'indicative') ?>
					</label>
				</th>
				<td>
					<select name="indicative_code_snippet_placement">
						<?php
						
							$options = [
								'head'	=>'&lt;head&gt;',
								'body'	=> __('Before &lt;/body&gt; (Recommended)', 'indicative'),
							];
							
							$selected = get_option('indicative_code_snippet_placement', 'body');
							
							echo DropDown::create($options, $selected);
							
						?>
					</select>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row">
					<label for="indicative_track_email_addresses">
						<?= __('Add Email Addresses to Events?', 'indicative') ?>
					</label>
				</th>
				<td>
					<select name="indicative_track_email_addresses">
						<?php
						
							$options = [
								'no'	=> __('No', 'indicative'),
								'yes'	=> __('Yes', 'indicative'),
							];
							
							$selected = get_option('indicative_track_email_addresses', 'no');
							
							echo DropDown::create($options, $selected);
							
						?>
					</select>
					<p class="description"><?= __('Consider if the GDPR and/or California Consumer Privacy Act laws apply to your website.', 'indicative') ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row">
					<label for="indicative_track_link_clicks">
						<?= __('Track All Link Clicks?', 'indicative') ?>
					</label>
				</th>
				<td>
					<select name="indicative_track_link_clicks">
						<?php
						
							$options = [
								'no'	=> __('No', 'indicative'),
								'yes'	=> __('Yes', 'indicative'),
							];
							
							$selected = get_option('indicative_track_link_clicks', 'yes');
							
							echo DropDown::create($options, $selected);
							
						?>
					</select>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row">
					<label for="indicative_record_sessions">
						<?= __('Track Web Sessions?', 'indicative') ?>
					</label>
				</th>
				<td>
					<select name="indicative_record_sessions">
						<?php
						
							$options = [
								'no'	=> __('No', 'indicative'),
								'yes'	=> __('Yes (recommended)', 'indicative'),
							];
							
							$selected = get_option('indicative_record_sessions', 'yes');
							
							echo DropDown::create($options, $selected);
							
						?>
					</select>
					<p class="description">
						<a
							href="https://support.indicative.com/hc/en-us/articles/360004186991-JavaScript"
							target="_blank"
						>
							<?= __('Learn more about session tracking.', 'indicative') ?>
						</a>
					</p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row">
					<label for="indicative_session_recording_timeout">
						<?= __('Session Recording Timeout', 'indicative') ?>
					</label>
				</th>
				<td>
					<?php
						$recordingTimeout = get_option('indicative_session_recording_timeout', 30);
					?>
					<input type="number" name="indicative_session_recording_timeout" value="<?= $recordingTimeout; ?>" />
					<p class="description"><?= __('The default is 30 minutes.', 'indicative') ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<td colspan="2">
					<?= __('Note: Want to track additional events? View our ', 'indicative') ?> 
					<a href="https://support.indicative.com/hc/en-us/articles/360004186991-JavaScript" target="_blank"><?= __('documentation', 'indicative') ?></a>
					<?= __('and', 'indicative') ?> 
					<a href="mailto:support@indicative.com?subject=WordPress%20Plugin%20Question" target="_blank"><?= __('contact us', 'indicative') ?></a>
					<?= __('with any questions.', 'indicative') ?> 	
				</td>
			</tr>
			<tr>
				<td>
					<input name="indicative_save_settings" type="submit" value="Save All" class="button button-primary" />
				</td>
			</tr>
		</table>
	</form>
</div>