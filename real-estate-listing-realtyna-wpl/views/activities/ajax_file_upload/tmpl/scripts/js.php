<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
function <?php wpl_esc::js($js_function); ?>()
{
	var filename = wplj("#<?php wpl_esc::js($html_element_id); ?>").val();

	var ext = filename.split('.').pop();
	ext = ext.toLowerCase();
    
    if(ext == filename) ext = '';
    
	if(<?php $i = 1; $count = count($valid_extensions); foreach($valid_extensions as $valid_extension){ wpl_esc::e("ext != '".$valid_extension."'".($i < $count ? ' && ' : '')); $i++; } ?>)
	{
		wpl_show_messages('<?php wpl_esc::js_t('File extension does not match.'); ?>', '<?php wpl_esc::e($html_path_message); ?>', 'wpl_red_msg');
		return false;
	}

	var ajax_loader_element = '<?php wpl_esc::js($html_ajax_loader); ?>';
	wplj(ajax_loader_element).html(`<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/'.$img_ajax_loader)); ?>" />`);

	var request_str = '<?php wpl_esc::e($request_str); ?>';

    wplj.ajaxFileUpload(
    {
        url: request_str,
        secureuri: false,
        fileElementId: '<?php wpl_esc::js($html_element_id); ?>',
        dataType: 'json',
        success: function(data, status)
        {
            wplj(ajax_loader_element).html('');
            data = wplj.parseJSON(data);

            if(data.error != '' && typeof data.error != "undefined")
            {
                <?php if(trim($html_path_message) != ''): ?>
                wpl_show_messages(data.error, '<?php wpl_esc::js($html_path_message); ?>', 'wpl_red_msg');
                <?php else: ?>
                wpl_alert(data.error);
                <?php endif; ?>
            }
            else
            {
                <?php if($js_callback): wpl_esc::e($js_callback); ?>
                <?php else: ?>
                window.location.reload();
                <?php endif; ?>
            }

            /** reset the value **/
            wplj("#<?php wpl_esc::js($html_element_id); ?>").val('');
        },
        error: function(data, status, e)
        {
            <?php if(trim($html_path_message) != ''): ?>
            wpl_show_messages(e, '<?php wpl_esc::e($html_path_message); ?>', 'wpl_red_msg');
            <?php else: ?>
            wpl_alert(e);
            <?php endif; ?>

            /** reset the value **/
            wplj("#<?php wpl_esc::js($html_element_id); ?>").val('');
        }
    });
}
</script>