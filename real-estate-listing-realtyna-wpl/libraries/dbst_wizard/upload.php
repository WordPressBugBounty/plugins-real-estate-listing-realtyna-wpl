<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'upload' and !$done_this)
{
    $upload_params = $options['params'];
    $upload_params['html_element_id'] = 'wpl_c_' . $field->id;
    $upload_params['html_ajax_loader'] = '#wpl_upload_saved_span_' . $field->id;

    $upload_params['request_str'] = str_replace('[html_element_id]', $upload_params['html_element_id'], $upload_params['request_str']);
    $upload_params['request_str'] = str_replace('[item_id]', $item_id, $upload_params['request_str']);
	if(!empty($options['ext_file'])) {
		$upload_params['valid_extensions'] = explode(',', $options['ext_file']);
	}

    // Add nonce to the request URL
    if(isset($nonce)) $upload_params['request_str'] = wpl_global::add_qs_var('_wpnonce', $nonce, $upload_params['request_str']);
    $activity_layout = isset($options['layout']) ? $options['layout'] : 'default';
?>
<label for="wpl_c_<?php wpl_esc::numeric($field->id); ?>"><?php wpl_esc::html_t($label); ?><?php if (in_array($mandatory, array(1, 2))): ?><span class="wpl_red_star">*</span><?php endif; ?></label>
<?php wpl_global::import_activity('ajax_file_upload:' . $activity_layout, '', $upload_params); ?>
<span id="wpl_upload_saved_span_<?php wpl_esc::numeric($field->id); ?>" class="wpl_listing_saved_span"></span>
<?php if($options['preview'] and trim($value ?? '') != ''): ?>
<div class="upload-preview-wp preview_upload" id="preview_upload<?php wpl_esc::numeric($field->id); ?>">
    <div class="upload-preview">
		<?php if(in_array(wpl_file::getExt($value), ['png', 'jpg', 'jpeg', 'gif'])): ?>
        	<img src="<?php wpl_esc::url(wpl_items::get_folder($item_id, $field->kind) . $value); ?>?c=<?php wpl_esc::e(rand(1000, 9999)); ?>" />
		<?php else: ?>
			<a target="_blank" href="<?php wpl_esc::url(wpl_items::get_folder($item_id, $field->kind) . $value);
			?>"><?php wpl_esc::html($value); ?></a>
		<?php endif; ?>
        <div class="preview-remove-button">
            <span class="action-btn icon-recycle" onclick="wpl_remove_upload<?php wpl_esc::numeric($field->id); ?>();"></span>
        </div>
    </div>
</div>
<script type="text/javascript">
function wpl_remove_upload<?php wpl_esc::numeric($field->id); ?>()
{
    var request_str = '<?php wpl_esc::e(str_replace('[item_id]', $item_id, $options['remove_str'])); ?>&field_id=<?php wpl_esc::numeric($field->id); ?>&_wpnonce=<?php wpl_esc::e($nonce ?? ''); ?>';

    /** run ajax query **/
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (data) {
			if(data.success == 1)
			{
				wplj("#preview_upload<?php wpl_esc::numeric($field->id); ?>").remove();
			}
		},
	});
}
</script>
<?php endif; ?>
<?php
    $done_this = true;
}