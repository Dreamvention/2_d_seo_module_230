<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button id="button_create_default_custom_page" data-toggle="tooltip" title="<?php echo $button_create_default_custom_page; ?>" class="btn btn-success"><i class="fa fa-cogs"></i></button>
				<button id="button_add" type="button" data-toggle="tooltip" title="<?php echo $button_add_custom_page; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></button>
				<button id="button_delete" type="button" data-toggle="tooltip" title="<?php echo $button_delete_custom_page; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
			<h1><?php echo $heading_title; ?> <?php echo $version; ?></h1>
			<ul class="breadcrumb">
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
			<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<?php if (!empty($error['warning'])) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error['warning']; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<?php if (!empty($success)) { ?>
		<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
			</div>
			<div class="panel-body">
				<ul class="nav nav-tabs">
					<li>
						<a href="<?php echo $href_setting; ?>"><span class="fa fa-cog"></span> <?php echo $text_settings; ?></a>
					</li>
					<li class="active">
						<a href="<?php echo $href_custom_page; ?>"><span class="fa fa-user"></span> <?php echo $text_custom_pages; ?></a>
					</li>
					<li>
						<a href="<?php echo $href_export_import; ?>"><span class="fa fa-file-o"></span> <?php echo $text_export_import; ?></a>
					</li>
					<li>
						<a href="<?php echo $href_instruction; ?>"><span class="fa fa-graduation-cap"></span> <?php echo $text_instructions; ?></a>
					</li>
				</ul>
								
				<form method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked).trigger('change');" /></td>
									<td class="text-center"><?php echo $column_route; ?></td>
									<?php foreach ($languages as $language) { ?>
									<td class="text-center"><?php echo $column_target_keyword . '('.$language['code'].')'; ?></td>
									<?php } ?>
								<tr>
							</thead>
							<tbody>
							<?php if ($custom_pages) { ?>
							<?php $custom_page_row = 0; ?>
							<?php foreach ($custom_pages as $custom_page) { ?>
								<tr>
									<td class="custom-page-cell text-left">
									<?php if (in_array($custom_page['route'], $selected)) { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $custom_page['route']; ?>" checked="checked" />
									<?php } else { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $custom_page['route']; ?>" />
									<?php } ?>
									</td>
									<td class="custom-page-cell text-left">
										<div id="custom_page_route" class="custom-page-field">
											<span class="text-field"><?php echo $custom_page['route']; ?></span>
										</div>
									</td>
									<?php foreach ($languages as $language) { ?>
									<td class="custom-page-cell text-left">
										<div id="custom_page_<?php echo $custom_page_row; ?>_target_keyword_<?php echo $language['language_id']; ?>" class="custom-page-field" route="<?php echo $custom_page['route']; ?>" language_id="<?php echo $language['language_id']; ?>">
											<span class="text-field"><?php if (isset($custom_page['target_keyword'][$language['language_id']])) { ?><?php foreach ($custom_page['target_keyword'][$language['language_id']] as $sort_order => $keyword) { ?><?php if (isset($custom_page['target_keyword_duplicate'][$language['language_id']][$sort_order])) { ?><span class="text-danger">[<?php echo $keyword; ?>]</span><?php } else { ?><span>[<?php echo $keyword; ?>]</span><?php } ?><?php } ?><?php } ?></span>
											<textarea class="input-field form-control hide"><?php if (isset($custom_page['target_keyword'][$language['language_id']])) { ?><?php foreach ($custom_page['target_keyword'][$language['language_id']] as $sort_order => $keyword) { ?>[<?php echo $keyword; ?>]<?php } ?><?php } ?></textarea>
										</div>
									</td>	
									<?php } ?>									
								</tr>
							<?php $custom_page_row++; ?>
							<?php } ?>
							<?php } else { ?>
								<tr>
									<td class="text-center" colspan="<?php echo count($languages)+2; ?>">
										<span class="btn btn-success" onclick="$('#button_create_default_custom_page').trigger('click');"><i class="fa fa-cogs"></i> <?php echo $button_create_default_custom_page; ?></span>
									</td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
						<div class="col-sm-6 text-right"><?php echo $results; ?></div>
					</div>	
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

update();

function update() {
	$('[name^=\'selected\']').on('change', function() {
		$('#button_delete').prop('disabled', true);
	
		var selected = $('[name^=\'selected\']:checked');
		
		if (selected.length) {
			$('#button_delete').prop('disabled', false);
		}
	});

	$('[name^=\'selected\']:first').trigger('change');
}

function refreshPage() {
	var url = 'index.php?route=<?php echo $route; ?>/custom_page&token=<?php echo $token; ?>';
	
	var page = '<?php echo $page; ?>';	
	
	if (page) {
		url += '&page=' + encodeURIComponent(page);
	}
				
	$("#form").load(url + ' #form >', function() {
		update();
	});
}

function showAlert(json) {
	$('.alert, .text-danger').remove();
	$('.form-group').removeClass('has-error');
						
	if (json['error']) {
		if (json['error']['warning']) {
			if ($('#modal-dialog').length > 0) {
				$('#modal-dialog .modal-body').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			} else {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
		}				
				
		for (i in json['error']) {
			var element = $('#input_' + i);
					
			if (element.parent().hasClass('input-group')) {
                $(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
			} else {
				$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
			}
		}				
				
		$('.text-danger').parents('.form-group').addClass('has-error');
	}
			
	if (json['success']) {
		$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '  <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
	}
}
</script> 
<script type="text/javascript">

$('body').on('click', '.custom-page-cell', function() {
	var custom_page = $(this).children('.custom-page-field');
		
	if ($(custom_page).children('.input-field').hasClass('hide') && $(custom_page).children('.input-field').attr('type')!='hidden') {
		$('.popover').popover('hide', function() {
			$('.popover').remove();
		});
	
		$('.text-field').removeClass('hide');
		$('.input-field').addClass('hide');

		$(custom_page).popover({
			html: true,
			placement: 'top',
			trigger: 'manual',
			content: function() {
				return '<button type="button" id="button_save" class="btn btn-primary" custom_page_field_id="' + custom_page.attr('id') + '"><i class="fa fa-save"></i></button> <button type="button" id="button_cancel" class="btn btn-danger" custom_page_field_id="' + custom_page.attr('id') + '"><i class="fa fa-remove"></i></button>';
			}
		});

		$(custom_page).popover('show');
	
		$(custom_page).children('.text-field').addClass('hide');
		$(custom_page).children('.input-field').removeClass('hide');
	}
});

$('body').on('click', '#button_save', function() {
	var custom_page = $('#' + $(this).attr('custom_page_field_id'));
	var route = $(custom_page).attr('route');
	var language_id = $(custom_page).attr('language_id');
	var target_keyword = $(custom_page).children('.input-field').val();
		
	$.ajax({
		url: 'index.php?route=<?php echo $route; ?>/editCustomPage&token=<?php echo $token; ?>',
		type: 'post',
		data: 'route=' + route + '&language_id=' + language_id + '&target_keyword=' + encodeURIComponent(target_keyword),
		dataType: 'json',
		success: function(json) {
			showAlert(json);
			
			$(custom_page).popover('hide', function() {
				$('.popover').remove();
			});
			
			setTimeout(function() {
				if (json['error'].length == 0) {
					$(custom_page).children('.text-field').text($(custom_page).children('.input-field').val());
				} else {
					$(custom_page).children('.input-field').val($(custom_page).children('.text-field').text());
				}
				$(custom_page).children('.text-field').removeClass('hide');
				$(custom_page).children('.input-field').addClass('hide');
				refreshPage();
			}, 200);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
		
$('body').on('click', '#button_cancel', function() {
	var custom_page = $('#' + $(this).attr('custom_page_field_id'));
		
	$(custom_page).popover('hide', function() {
		$('.popover').remove();
	});
			
	setTimeout(function() {
		$(custom_page).children('.input-field').val($(custom_page).children('.text-field').text());
		$(custom_page).children('.text-field').removeClass('hide');
		$(custom_page).children('.input-field').addClass('hide');
	}, 200);
});

$('body').on('click', '#button_create_default_custom_page', function() {
	if (confirm("<?php echo $text_create_default_custom_pages_confirm; ?>")) {	
		$.ajax({
			url: 'index.php?route=<?php echo $route; ?>/createDefaultCustomPage&token=<?php echo $token; ?>',
			type: 'post',
			data: '',
			dataType: 'json',
			success: function(json) {
				showAlert(json);
				if (json['success']) {
					$('#modal-dialog').modal('hide');
					refreshPage();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
});

$('body').on('click', '#button_add', function() {
	modal_html  = '<div id="modal-dialog" class="modal"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button><h4 class="modal-title"><?php echo $text_add_custom_page; ?></h4></div>';
	modal_html += '<div class="modal-body"><div class="form-horizontal">';
	modal_html += '<div class="row"><label class="col-sm-3 control-label" for="input-custom-page-route"><?php echo $entry_route; ?></label><div class="col-sm-9"><input type="text" name="custom_page[route]" value="" id="input_custom_page_route" class="form-control"/></div></div><br/>';
	modal_html += '<div class="row"><label class="col-sm-3 control-label" for="input-custom-page-seo-keyword"><?php echo $entry_target_keyword; ?></label><div class="col-sm-9">';
	<?php foreach ($languages as $language) { ?>
	modal_html += '<div class="input-group"><span class="input-group-addon"><img src="<?php echo $language['flag']; ?>" title="<?php echo $language['name']; ?>"></span><textarea name="custom_page[target_keyword][<?php echo $language['language_id']; ?>]" id="input_custom_page_target_keyword_<?php echo $language['language_id']; ?>" class="form-control"></textarea></div>';
	<?php } ?>
	modal_html += '</div></div>';		
	modal_html += '</div></div>';		
	modal_html += '<div class="modal-footer"><button type="button" data-toggle="tooltip" title="<?php echo $button_add_custom_page; ?>"  id="button_add_custom_page" class="btn btn-primary"><?php echo $button_add_custom_page; ?></button></div>';		
	modal_html += '</div></div></div>';
		
	$('#modal-dialog').remove();
		
	$('body').append(modal_html);
		
	$('#modal-dialog').modal('show');
});

$('body').on('click', '#button_add_custom_page', function() {
	$.ajax({
		type: 'post',
		url: 'index.php?route=<?php echo $route; ?>/addCustomPage&token=<?php echo $token; ?>',
		data: $('[name^="custom_page"]'),
		dataType: 'json',
		success: function(json) {
			showAlert(json);
			if (json['success']) {
				$('#modal-dialog').modal('hide');
				refreshPage();
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});  
});

$('body').on('click', '#button_delete', function() {
	if (confirm('<?php echo $text_delete_custom_pages_confirm; ?>')) {
		$.ajax({
			type: 'post',
			url: 'index.php?route=<?php echo $route; ?>/deleteCustomPage&token=<?php echo $token; ?>',
			data: $('#form').serialize(),
			dataType: 'json',
			success: function(json) {
				showAlert(json);
				if (json['success']) {
					refreshPage();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});  
	}
});

</script>
<?php echo $footer; ?>