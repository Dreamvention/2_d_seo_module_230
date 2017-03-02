<div id="<?php echo $codename; ?>" class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-signal"></i> <?php echo $heading_title; ?></h3>
	</div>
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<td class="text-center"><?php echo $column_route; ?></td>
						<?php foreach ($languages as $language) { ?>
						<td class="text-center"><?php echo $column_target_keyword . '('.$language['code'].')'; ?></td>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
				<?php if ($targets) { ?>
				<?php $target_row = 0; ?>
				<?php foreach ($targets as $target) { ?>
					<tr>
						<td class="target-cell text-left">
							<div id="target_route" class="target-field">
								<?php if (isset($target['link'])) { ?>
								<a href="<?php echo $target['link']; ?>" target="_blank"><span class="text-field"><?php echo $target['route']; ?></span></a>
								<?php } else { ?>
								<span class="text-field"><?php echo $target['route']; ?></span>
								<?php } ?>
							</div>
						</td>
						<?php foreach ($languages as $language) { ?>
						<td class="target-cell text-left">
							<div id="target_<?php echo $target_row; ?>_target_keyword_<?php echo $language['language_id']; ?>" class="target-field" route="<?php echo $target['route']; ?>" language_id="<?php echo $language['language_id']; ?>">
								<span class="text-field"><?php if (isset($target['target_keyword'][$language['language_id']])) { ?><?php foreach ($target['target_keyword'][$language['language_id']] as $sort_order => $keyword) { ?><?php if (isset($target['target_keyword_duplicate'][$language['language_id']][$sort_order])) { ?><span class="text-danger">[<?php echo $keyword; ?>]</span><?php } else { ?><span>[<?php echo $keyword; ?>]</span><?php } ?><?php } ?><?php } ?></span>
								<textarea class="input-field form-control hide"><?php if (isset($target['target_keyword'][$language['language_id']])) { ?><?php foreach ($target['target_keyword'][$language['language_id']] as $sort_order => $keyword) { ?>[<?php echo $keyword; ?>]<?php } ?><?php } ?></textarea>
							</div>
						</td>	
						<?php } ?>									
					</tr>
				<?php $target_row++; ?>
				<?php } ?>
				<?php } else { ?>
					<tr>
						<td class="text-center" colspan="<?php echo count($languages)+1; ?>"><?php echo $text_no_results; ?></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>	
</div>
<script type="text/javascript">

function <?php echo $codename; ?>_refresh() {
	var url = 'index.php?route=<?php echo $route; ?>/refresh&token=<?php echo $token; ?>';
					
	$('#<?php echo $codename; ?>').load(url + ' #<?php echo $codename; ?> >');
}

function <?php echo $codename; ?>_showAlert(json) {
	$('#<?php echo $codename; ?> .alert').remove();
	$('#<?php echo $codename; ?> .form-group').removeClass('has-error');
						
	if (json['error']) {
		if (json['error']['warning']) {
			$('#<?php echo $codename; ?> .panel-body').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
		}				
				
		for (i in json['error']) {
			var element = $('#<?php echo $codename; ?> #input_' + i);
					
			if (element.parent().hasClass('input-group')) {
                $(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
			} else {
				$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
			}
		}				
				
		$('#<?php echo $codename; ?> .text-danger').parents('.form-group').addClass('has-error');
	}
			
	if (json['success']) {
		$('#<?php echo $codename; ?> .panel-body').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '  <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
	}
}
</script>
<script type="text/javascript">

$('#<?php echo $codename; ?>').on('click', '.target-cell', function() {
	var target = $(this).children('.target-field');
		
	if ($(target).children('.input-field').hasClass('hide') && $(target).children('.input-field').attr('type')!='hidden') {
		$('.popover').popover('hide', function() {
			$('.popover').remove();
		});
	
		$('.text-field').removeClass('hide');
		$('.input-field').addClass('hide');

		$(target).popover({
			html: true,
			placement: 'top',
			trigger: 'manual',
			content: function() {
				return '<button type="button" id="button_save" class="btn btn-primary" target_field_id="'+target.attr('id')+'"><i class="fa fa-save"></i></button> <button type="button" id="button_cancel" class="btn btn-danger" target_field_id="'+target.attr('id')+'"><i class="fa fa-remove"></i></button>';
			}
		});

		$(target).popover('show');
	
		$(target).children('.text-field').addClass('hide');
		$(target).children('.input-field').removeClass('hide');
	}
});

$('#<?php echo $codename; ?>').on('click', '#button_save', function() {
	var target = $('#' + $(this).attr('target_field_id'));
	var route = $(target).attr('route');
	var language_id = $(target).attr('language_id');
	var target_keyword = $(target).children('.input-field').val();
		
	$.ajax({
		url: 'index.php?route=<?php echo $route; ?>/editTarget&token=<?php echo $token; ?>',
		type: 'post',
		data: 'route='+route+'&language_id='+language_id+'&target_keyword='+encodeURIComponent(target_keyword),
		dataType: 'json',
		success: function(json) {
			<?php echo $codename; ?>_showAlert(json);
						
			$(target).popover('hide', function() {
				$('.popover').remove();
			});
			
			setTimeout(function() {
				if (json['error'].length == 0) {
					$(target).children('.text-field').text($(target).children('.input-field').val());
					<?php echo $codename; ?>_refresh();
				} else {
					$(target).children('.input-field').val($(target).children('.text-field').text().replace(/<.*?>/g, ''));
				}
				$(target).children('.text-field').removeClass('hide');
				$(target).children('.input-field').addClass('hide');
			}, 200);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
		
$('#<?php echo $codename; ?>').on('click', '#button_cancel', function() {
	var target = $('#' + $(this).attr('target_field_id'));
		
	$(target).popover('hide', function() {
		$('.popover').remove();
	});
			
	setTimeout(function() {
		$(target).children('.input-field').val($(target).children('.text-field').text().replace(/<.*?>/g, ''));
		$(target).children('.text-field').removeClass('hide');
		$(target).children('.input-field').addClass('hide');
	}, 200);
});

</script>