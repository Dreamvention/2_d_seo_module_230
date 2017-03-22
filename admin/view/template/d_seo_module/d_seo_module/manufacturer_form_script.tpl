<script type="text/javascript" src="view/javascript/d_tinysort/tinysort.min.js"></script>
<script type="text/javascript" src="view/javascript/d_tinysort/jquery.tinysort.min.js"></script>
<script type="text/javascript" src="view/javascript/d_rubaxa_sortable/sortable.js"></script>
<script type="text/javascript">
	$('#tab-general > #language > li > a').each(function(index) {
		tinysort($(this).attr('href') + ' > .sort-item', {attr: 'data-sort-order'});
	});
	
	$('.btn-target-keyword-add').on('click', function(event) {
		var element = $(this).parents('.form-group').find('.input-target-keyword');
		var language_id = $(element).attr('language_id');
		var keyword = $(element).val();
		var is_keyword = 0;
		
		$('[name^="target_keyword[' + language_id + ']"]').each(function(index) {
			if (keyword == $(this).val()) is_keyword = 1;
		});
		
		if (keyword && !is_keyword) {
			$.ajax({
				url: 'index.php?route=extension/module/d_seo_module/getTargetKeywords&token=<?php echo $token; ?>',
				type: 'post',
				data: 'keyword=' + keyword,
				dataType: 'json',
				success: function(json) {
					html  = '<div class="target-keyword target-keyword-' + keyword + ' sort-item">';
					
					if (json['target_keywords'].length != 0) {
						html += '<span class="target-keyword-title text-danger">' + keyword + '</span>';
					} else {
						html += '<span class="target-keyword-title">' + keyword + '</span>';
					}
					
					html += '<span class="icons"><i class="icon-delete fa fa-minus-circle" onclick="$(this).parents(\'.target-keyword\').remove()"></i><i class="icon-drag fa fa-bars"></i></span>';
					html += '<input type="hidden" name="target_keyword[' + language_id + '][]" value="' + keyword + '" />';
					html += '</div>';
					
					$(element).val('');
					
					$('#target_keywords_' + language_id).append(html);
				},
				error: function(xhr, ajaxOptions, thrownError) {
					console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	});
	
	$('.target-keywords').each(function(index) {
		var language_id = $(this).attr('language_id');
		
		Sortable.create(document.getElementById('target_keywords_' + language_id), {
			group: "sorting",
			sort: true,
			animation: 150,
			handle: ".icon-drag"
		});
	});
</script>