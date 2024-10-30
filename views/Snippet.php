<script type="text/javascript">
(function(apiKey){
	var ind = document.createElement('script');
	ind.src = '//cdn.indicative.com/js/Indicative.min.js';
	ind.type = 'text/javascript';
	ind.async = 'true';
	var ind_init = false;
	ind.onload = ind.onreadystatechange = function() {
		var rs = this.readyState;
		if (ind_init || (rs && rs != 'complete' && rs != 'loaded'))
			return;
		ind_init = true;
		Indicative.initialize(apiKey, {
			recordSessions: <?= $pageData['track-sessions'] === 'yes' ? 'true' : 'false'; ?>,
			sessionsThreshold: <?= $pageData['session-timeout-mins']; ?>,
			cookiesOnMainDomain: false
		});

		<?PHP
			do_action('indicative_after_initialize');
			
			if (!empty($pageData['alias-id'])) {
				?>
				Indicative.setUniqueID(<?= $pageData['alias-id']; ?>, true);
				<?PHP
			}
			
			if (!empty($pageData['email'])) {
				?>
				Indicative.addProperty('email', '<?= $pageData['email']; ?>');
				<?PHP
			}
			
			do_action('indicative_before_page_view');
		?>
		
		Indicative.buildEvent('Page View');
	};
	var s = document.getElementsByTagName('script')[0];
	s.parentNode.insertBefore(ind, s);
})("<?= $pageData['key']; ?>");
</script>