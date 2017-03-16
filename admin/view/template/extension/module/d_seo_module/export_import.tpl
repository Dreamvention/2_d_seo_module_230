<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
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
					<li>
						<a href="<?php echo $href_custom_page; ?>"><span class="fa fa-user"></span> <?php echo $text_custom_pages; ?></a>
					</li>
					<li class="active">
						<a href="<?php echo $href_export_import; ?>"><span class="fa fa-file-o"></span> <?php echo $text_export_import; ?></a>
					</li>
					<li>
						<a href="<?php echo $href_instruction; ?>"><span class="fa fa-graduation-cap"></span> <?php echo $text_instructions; ?></a>
					</li>
				</ul>
				
				<div class="row">
					<div class="col-sm-2">
						<ul class="nav nav-pills nav-stacked">
							<li class="active">
								<a href="#vtab_export" data-toggle="tab"><span><?php echo $text_export; ?></span></a>
							</li>
							<li>
								<a href="#vtab_import" data-toggle="tab"><span><?php echo $text_import; ?></span></a>
							</li>
						</ul>
					</div>
					<div class="col-sm-10">
						<div class="tab-content">
							<div id="vtab_export" class="tab-pane active">
								<div class="page-header">
									<h3><span><?php echo $text_export; ?></span></h3>
								</div>
								<form action="<?php echo $export; ?>" method="post" enctype="multipart/form-data" id="form_export" class="form-horizontal">
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-sheet"><?php echo $entry_sheet; ?></label>
										<div class="col-sm-10">
											<input type="checkbox" name="sheet_codes[]" value="custom_page" data-label-text="<?php echo $text_custom_pages; ?>" checked="checked"/>
										</div>
									</div>	
									<div class="form-group">
										<label class="col-sm-2 control-label"><?php echo $entry_export; ?></label>
										<div class="col-sm-10">
											<a id="button_export" class="btn btn-primary"><?php echo $button_export; ?></a>
										</div>
									</div>	
								</form>
							</div>
							<div id="vtab_import" class="tab-pane">
								<div class="page-header">
									<h3><span><?php echo $text_import; ?></span></h3>
								</div>
								<form action="<?php echo $import; ?>" method="post" enctype="multipart/form-data" id="form_import" class="form-horizontal">
									<div class="form-group">
										<label class="col-sm-2 control-label label-top"><?php echo $entry_upload; ?></label>
										<div class="col-sm-10">
											<input type="file" name="upload" id="upload"/>
										</div>
									</div>	
									<div class="form-group">
										<label class="col-sm-2 control-label"><?php echo $entry_import; ?></label>
										<div class="col-sm-10">
											<a id="button_import" class="btn btn-primary"><?php echo $button_import; ?></a>
										</div>
									</div>	
								</form>	
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

$('[type=checkbox]').bootstrapSwitch({
    'onColor': 'success',
	'labelWidth': '90',
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

$('body').on('click', '#button_export', function() {
	$('#form_export').submit();
});

$('body').on('click', '#button_import', function() {
	$.ajax( {
		type: 'post',
		url: $('#form_import').attr('action'),
		data: new FormData($('#form_import')[0]),
		dataType: 'json',
		processData: false,
		contentType: false,
		success: function(json) {
			showAlert(json);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
    });
});

</script>
<?php echo $footer; ?>