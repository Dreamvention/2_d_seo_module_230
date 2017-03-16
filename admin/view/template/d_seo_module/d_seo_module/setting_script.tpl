<script type="text/javascript" src="view/javascript/d_tinysort/tinysort.min.js"></script>
<script type="text/javascript" src="view/javascript/d_tinysort/jquery.tinysort.min.js"></script>
<script type="text/javascript">
	$('#tab-general > #language > li > a').each(function(index) {
		tinysort($(this).attr('href') + ' > .sort-item', {attr: 'data-sort-order'});
	});
</script>
