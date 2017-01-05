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
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
						<div class="col-sm-8">
							<input type="hidden" name="dashboard_<?php echo $codename; ?>_status" value="0" />
							<input type="checkbox" name="dashboard_<?php echo $codename; ?>_status" <?php echo $status ? 'checked="checked"' : ''; ?> value="1"/>
						</div>												
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-width"><?php echo $entry_width; ?></label>
						<div class="col-sm-10">
							<select name="dashboard_<?php echo $codename; ?>_width" class="form-control">
							<?php foreach ($columns as $column) { ?>
							<?php if ($column == $width) { ?>
								<option value="<?php echo $column; ?>" selected="selected"><?php echo $column; ?></option>
							<?php } else { ?>
								<option value="<?php echo $column; ?>"><?php echo $column; ?></option>
							<?php } ?>
							<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
						<div class="col-sm-10">
							<input type="text" name="dashboard_<?php echo $codename; ?>_sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-list-limit"><?php echo $entry_list_limit; ?></label>
						<div class="col-sm-10">
							<input type="text" name="dashboard_<?php echo $codename; ?>_setting[list_limit]" value="<?php echo $setting['list_limit']; ?>" placeholder="<?php echo $entry_list_limit; ?>" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-duplicate-status"><?php echo $entry_duplicate_status; ?></label>
						<div class="col-sm-8">
							<input type="hidden" name="dashboard_<?php echo $codename; ?>_setting[duplicate_status]" value="0" />
							<input type="checkbox" name="dashboard_<?php echo $codename; ?>_setting[duplicate_status]" <?php echo $setting['duplicate_status'] ? 'checked="checked"' : ''; ?> value="1"/>
						</div>												
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-empty-status"><?php echo $entry_empty_status; ?></label>
						<div class="col-sm-8">
							<input type="hidden" name="dashboard_<?php echo $codename; ?>_setting[empty_status]" value="0" />
							<input type="checkbox" name="dashboard_<?php echo $codename; ?>_setting[empty_status]" <?php echo $setting['empty_status'] ? 'checked="checked"' : ''; ?> value="1"/>
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

$('body').on('click', '#save_and_exit', function(){
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
