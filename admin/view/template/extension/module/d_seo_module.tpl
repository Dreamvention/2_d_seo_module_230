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
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
					<ul  class="nav nav-tabs">
						<li class="active">
							<a href="#tab_setting" data-toggle="tab"><span class="fa fa-cog"></span> <?php echo $text_settings; ?></a>
						</li>
						<li>
							<a href="#tab_instruction" data-toggle="tab"><span class="fa fa-graduation-cap"></span> <?php echo $text_instructions; ?></a>
						</li>
					</ul>
		
					<div class="tab-content">
						<div class="tab-pane active" id="tab_setting">
							<div class="tab-body">
								<div class="row">
									<div class="col-sm-2">
										<ul class="nav nav-pills nav-stacked">
											<li class="active">
												<a href="#vtab_basic_settings" data-toggle="tab"><span><?php echo $text_basic_settings; ?></span></a>
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
											<div id="vtab_basic_settings" class="tab-pane active">
												<div class="page-header">
													<h3><span><?php echo $text_basic_settings; ?></span></h3>
												</div>
												<div class="form-group">
													<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
													<div class="col-sm-8">
														<input type="hidden" name="<?php echo $codename; ?>_status" value="0" />
														<input type="checkbox" name="<?php echo $codename; ?>_status" <?php echo $status ? 'checked="checked"' : ''; ?> value="1"/>
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
														<input type="checkbox" name="htaccess[status]" <?php echo $htaccess['status'] ? 'checked="checked"' : ''; ?> value="1"/>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-2 control-label" for="input-text"><?php echo $entry_text; ?></label>
													<div class="col-sm-10">
														<textarea name="htaccess[text]" class="form-control" rows="20"><?php echo $htaccess['text']; ?></textarea>
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
														<input type="checkbox" name="robots[status]" <?php echo $robots['status'] ? 'checked="checked"' : ''; ?> value="1"/>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-2 control-label" for="input-text"><?php echo $entry_text; ?></label>
													<div class="col-sm-10">
														<textarea name="robots[text]" class="form-control" rows="20"><?php echo $robots['text']; ?></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab_instruction">
							<div class="tab-body"><?php echo $text_instructions_full; ?></div>
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

$('body').on('click', '#save_and_stay', function(){
    $.ajax( {
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
	
$('body').on('click', '#save_and_exit', function(){
    $.ajax( {
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