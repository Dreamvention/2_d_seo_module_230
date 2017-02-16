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
					<li>
						<a href="<?php echo $href_export_import; ?>"><span class="fa fa-file-o"></span> <?php echo $text_export_import; ?></a>
					</li>
					<li class="active">
						<a href="<?php echo $href_instruction; ?>"><span class="fa fa-graduation-cap"></span> <?php echo $text_instructions; ?></a>
					</li>
				</ul>
				
				<?php echo $text_instructions_full; ?>
			</div>
		</div>
	</div>
</div>
<?php echo $footer; ?>