<div class="form-group sort-item" data-sort-order="10">
    <label class="col-sm-2 control-label" for="input-target-keyword"><span data-toggle="tooltip" title="<?php echo $help_target_keyword; ?>"><?php echo $entry_target_keyword; ?></span></label>
    <div class="col-sm-10">
		<div class="input-group">
			<input type="text" value="" placeholder="<?php echo $entry_target_keyword; ?>" id="input_target_keyword_<?php echo $language_id; ?>" class="input-target-keyword form-control" language_id="<?php echo $language_id; ?>"/>
			<span class="input-group-addon btn btn-target-keyword-add"><i class="fa fa-plus"></i></span>
        </div>
        <div id="target_keywords_<?php echo $language_id; ?>" class="target-keywords well well-sm" language_id="<?php echo $language_id; ?>">
			<?php $target_keyword_row = 0; ?>
			<?php foreach ($target_keywords as $target_keyword) { ?>
			<div class="target-keyword sort-item">
				<span class="target-keyword-title <?php if (isset($target_keyword['duplicate'])) { ?>text-danger<?php } ?>"><?php echo $target_keyword['keyword']; ?></span>
				<span class="icons"><i class="icon-delete fa fa-minus-circle" onclick="$(this).parents('.target-keyword').remove()"></i><i class="icon-drag fa fa-bars"></i></span>
				<input type="hidden" name="target_keyword[<?php echo $language_id; ?>][]" value="<?php echo $target_keyword['keyword']; ?>" />
			</div>
			<?php $target_keyword_row++; ?>
			<?php } ?>
        </div>
    </div>
</div>