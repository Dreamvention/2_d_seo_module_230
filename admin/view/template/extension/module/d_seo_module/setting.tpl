<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button id="save_and_stay" data-toggle="tooltip" title="<?php echo $button_save_and_stay; ?>" class="btn btn-success"><i class="fa fa-save"></i></button>
				<button id="save_and_exit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
					<li class="active">
						<a href="<?php echo $href_setting; ?>"><span class="fa fa-cog"></span> <?php echo $text_settings; ?></a>
					</li>
					<li>
						<a href="<?php echo $href_custom_page; ?>"><span class="fa fa-user"></span> <?php echo $text_custom_pages; ?></a>
					</li>
					<li>
						<a href="<?php echo $href_export_import; ?>"><span class="fa fa-file-o"></span> <?php echo $text_export_import; ?></a>
					</li>
					<li>
						<a href="<?php echo $href_instruction; ?>"><span class="fa fa-graduation-cap"></span> <?php echo $text_instructions; ?></a>
					</li>
				</ul>
				
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
					<div class="row">
						<div class="col-sm-2">
							<ul class="nav nav-pills nav-stacked">
								<li class="active">
									<a href="#vtab_basic_setting" data-toggle="tab"><span><?php echo $text_basic_settings; ?></span></a>
								</li>
								<li>
									<a href="#vtab_htaccess" data-toggle="tab"><span><?php echo $text_htaccess; ?></span></a>
								</li>
								<li>
									<a href="#vtab_robots" data-toggle="tab"><span><?php echo $text_robots; ?></span></a>
								</li>
							</ul>
						</div>
						<div class="col-sm-10">
							<div class="tab-content">
								<div id="vtab_basic_setting" class="tab-pane active">
									<div class="page-header">
										<h3><span><?php echo $text_basic_settings; ?></span></h3>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
										<div class="col-sm-8">
											<input type="hidden" name="<?php echo $codename; ?>_status" value="0" />
											<input type="checkbox" name="<?php echo $codename; ?>_status" value="1" data-label-text="<?php echo $text_enabled; ?>" <?php echo $status ? 'checked="checked"' : ''; ?> />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-list-limit"><?php echo $entry_list_limit; ?></label>
										<div class="col-sm-10">
											<input type="text" name="<?php echo $codename; ?>_setting[list_limit]" value="<?php echo $setting['list_limit']; ?>" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-uninstall"><?php echo $entry_uninstall; ?></label>
										<div class="col-sm-8">
											<a action="<?php echo $uninstall; ?>" id="button_uninstall" class="btn btn-danger"><?php echo $button_uninstall; ?></a>
										</div>
									</div>	
								</div>
								<div id="vtab_htaccess" class="tab-pane">
									<div class="page-header">
										<h3><span><?php echo $text_htaccess; ?></span></h3>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
										<div class="col-sm-10">
											<input type="hidden" name="htaccess[status]" value="0" />
											<input type="checkbox" name="htaccess[status]" value="1" data-label-text="<?php echo $text_enabled; ?>" <?php echo $htaccess['status'] ? 'checked="checked"' : ''; ?> />
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-10 col-sm-offset-2">
											<p><a href="<?php echo $store_setting?>" class="btn btn-primary"><i class="fa fa-cog fw"></i> <?php echo $button_edit_store_setting; ?></a></p>
											<div class="bs-callout bs-callout-info"><?php echo $help_htaccess_setting; ?></div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-text"><?php echo $entry_text; ?></label>
										<div class="col-sm-10">
											<p><textarea name="htaccess[text]" class="form-control" rows="20"><?php echo $htaccess['text']; ?></textarea></p>
											<div class="bs-callout bs-callout-info"><?php echo $help_htaccess_subfolder; ?></div>
										</div>
									</div>
								</div>
								<div id="vtab_robots" class="tab-pane">
									<div class="page-header">
										<h3><span><?php echo $text_robots; ?></span></h3>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
										<div class="col-sm-10">
											<input type="hidden" name="robots[status]" value="0" />
											<input type="checkbox" name="robots[status]" value="1" data-label-text="<?php echo $text_enabled; ?>" <?php echo $robots['status'] ? 'checked="checked"' : ''; ?> />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-text"><?php echo $entry_text; ?></label>
										<div class="col-sm-10">
											<p><textarea name="robots[text]" class="form-control" rows="20"><?php echo $robots['text']; ?></textarea></p>
											<div class="bs-callout bs-callout-info"><?php echo $help_robots; ?></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

$('[type=checkbox]').bootstrapSwitch({
    'onColor': 'success',
	'labelWidth': '50',
    'onText': '<?php echo $text_yes; ?>',
    'offText': '<?php echo $text_no; ?>'
});

function showAlert(json) {
	$('.alert, .text-danger').remove();
	$('.form-group').removeClass('has-error');
						
	if (json['error']) {
		if (json['error']['warning']) {
			$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
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

$('body').on('click', '#button_uninstall', function(event) {
	if (confirm("<?php echo $text_uninstall_confirm; ?>")) {		
		$.ajax({
			type: 'post',
			url: $(this).attr('action'),
			data: '',
			dataType: 'json',
			beforeSend: function() {
				$('#content').fadeTo('slow', 0.5);
			},
			complete: function() {
				$('#content').fadeTo('slow', 1);   
			},
			success: function(json) {
				showAlert(json);
				if (json['success']) location = '<?php echo str_replace('&amp;', '&', $module_link); ?>';
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});  
	}
});

$('body').on('click', '#save_and_stay', function() {
    $.ajax({
		type: 'post',
		url: $('#form').attr('action'),
		data: $('#form').serialize(),
		dataType: 'json',
		beforeSend: function() {
			$('#content').fadeTo('slow', 0.5);
		},
		complete: function() {
			$('#content').fadeTo('slow', 1);   
		},
		success: function(json) {
			showAlert(json);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
    });  
});
	
$('body').on('click', '#save_and_exit', function() {
    $.ajax({
		type: 'post',
		url: $('#form').attr('action'),
		data: $('#form').serialize(),
		dataType: 'json',
		beforeSend: function() {
			$('#content').fadeTo('slow', 0.5);
		},
		complete: function() {
			$('#content').fadeTo('slow', 1);   
		},
		success: function(json) {
			showAlert(json);
			if (json['success']) location = '<?php echo str_replace('&amp;', '&', $cancel); ?>';
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
    });  
});

</script>
<?php echo $footer; ?>