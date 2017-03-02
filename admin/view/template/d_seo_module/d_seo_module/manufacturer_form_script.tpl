<script type="text/javascript" src="view/javascript/shopunity/tinysort/jquery.tinysort.min.js"></script>
<script type="text/javascript" src="view/javascript/shopunity/bootstrap-sortable.js"></script>
<script type="text/javascript">
	$('.sort-item[data-sort-order]').tsort({attr: 'data-sort-order'});
	
	$('.btn-target-keyword-add').on('click', function(event) {
		var element = $(this).parents('.form-group').find('.input-target-keyword');
		var language_id = $(element).attr('language_id');
		var keyword = $(element).val();
		var is_keyword = 0;
		
		$('[name^="target_keyword[' + language_id + ']"]').each(function(index) {
			if (keyword==$(this).val()) is_keyword = 1;
		});
		
		if (keyword && !is_keyword) {
			$.ajax({
				url: 'index.php?route=extension/module/d_seo_module/getTargetKeywords&token=<?php echo $token; ?>',
				type: 'post',
				data: 'keyword=' + keyword,
				dataType: 'json',
				success: function(json) {					
					html  = '<div class="target-keyword target-keyword-' + keyword + ' sort-item">';
					
					if (json['target_keywords'].length > 0) {
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
	
		$('#target_keywords_' + language_id).sortable({
			containerSelector: '#target_keywords_' + language_id,
			itemPath: '',
			itemSelector: '.sort-item',
			handle: '.icon-drag',
			pullPlaceholder: false,
			placeholder: '<div class="placeholder"></div>',
			nested: false,
			distance: '5',
			onDragStart: function (item, container, _super) {
				var offset = item.offset(),
				pointer = container.rootGroup.pointer

				adjustment = {
					left: pointer.left - offset.left,
					top: pointer.top - offset.top
				}

				_super(item, container)
			},
			onDrag: function (item, position) {
			item.css({
				left: position.left - adjustment.left,
				top: position.top - adjustment.top
			})
			},
			onDrop: function  (item, container, _super) {
				_super(item)
			}
		});
	});
</script>
