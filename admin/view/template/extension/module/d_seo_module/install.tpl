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
				<h3 class="panel-title"><?php echo $text_install; ?></h3>
			</div>
			<div class="panel-body text-center">
				<?php echo $help_install; ?>
				<a action="<?php echo $install; ?>" id="button_install" class="btn btn-success btn-lg"><?php echo $button_install; ?></a>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

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

$('body').on('click', '#button_install', function(){
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
});

</script>
<?php echo $footer; ?>
