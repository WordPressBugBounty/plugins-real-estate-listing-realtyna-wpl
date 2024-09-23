<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$js_function = $params['js_function'] ?? 'wpl_save_params';
$element_class = $params['element_class'] ?? '';
$element_id = $params['element_id'] ?? 'wpl_params_cnt' . rand(1, 100);
$html_path_message = $params['html_path_message'] ?? '.wpl_params_activity .wpl_show_message';
$html_ajax_loader = $params['html_ajax_loader'] ?? 'wpl_params_ajax_loader';
$img_ajax_loader = $params['img_ajax_loader'] ?? 'ajax-loader3.gif';
$close_lightbox = $params['close_fancybox'] ?? 0;

$db_table = $params['table'] ?? '';
$record_id = $params['id'] ?? '';
$params_array = (isset($params['params']) and is_array($params['params'])) ? $params['params'] : wpl_global::get_params($db_table, $record_id);

if (!$params_array) $params_array = array('' => '');
?>
<div class="<?php wpl_esc::attr($element_class); ?> fanc-params-wp fanc-content size-width-1" id="<?php wpl_esc::html($element_id); ?>">
    <h2><?php wpl_esc::html_t('Parameters'); ?></h2>
    <div class="fanc-body">
        <div class="fanc-row fanc-button-row-2">
            <span class="ajax-inline-save" id="<?php wpl_esc::html($html_ajax_loader); ?>"></span>
            <input class="wpl-button button-1" type="button" value="<?php wpl_esc::attr_t('Save'); ?>" onclick="<?php wpl_esc::e($js_function); ?>();" />
        </div>
        <div class="fanc-row fanc-button-add">
            <span class="action-btn icon-plus" onclick="wpl_add_param();"><?php wpl_esc::html_t('Add'); ?></span>
        </div>
        <?php $i = 1; foreach($params_array as $key=>$value): ?>
        <div class="fanc-row" id="wpl_param_row<?php wpl_esc::e($i); ?>">
            <input type="text" name="wpl_params[keys][]" placeholder="<?php wpl_esc::attr_t('Key'); ?>" value="<?php wpl_esc::html($key); ?>" />
            <input type="text" name="wpl_params[values][]" placeholder="<?php wpl_esc::attr_t('Value'); ?>" value="<?php wpl_esc::html($value); ?>" />
            <span class="action-btn icon-recycle" onclick="wpl_remove_param(<?php wpl_esc::e($i); ?>);"></span>
        </div>
		<?php $i++; endforeach; ?>
    </div>
    <div class="wpl_show_message wpl_hidden"></div>
</div>
<script type="text/javascript">
var wpl_params_i = <?php wpl_esc::e($i); ?>;

function wpl_add_param()
{
	html = '<div class="fanc-row" id="wpl_param_row' + wpl_params_i + '">'+
			'<input type="text" name="wpl_params[keys][]" placeholder="<?php wpl_esc::attr_t('Key'); ?>" /> '+
			'<input type="text" name="wpl_params[values][]" placeholder="<?php wpl_esc::attr_t('Value'); ?>" /> '+
			'<span class="action-btn icon-recycle" onclick="wpl_remove_param(' + wpl_params_i + ');"></span></div>';
	wplj(".fanc-body").append(html);

	wpl_params_i++;
}

function wpl_remove_param(element_id)
{
	wplj(".fanc-body #wpl_param_row" + element_id).remove();
}

function <?php wpl_esc::e($js_function); ?>()
{
	request_str = '';
	wplj("#<?php wpl_esc::attr($element_id); ?> input").each(function(ind, element)
	{
		if (element.type != 'text') return;
		request_str += "&" + element.name + "=" + wplj(element).val();
	});

	ajax_loader_element = '#<?php wpl_esc::attr($html_ajax_loader); ?>';
	url = '<?php wpl_esc::current_url(); ?>';

	wpl_remove_message('<?php wpl_esc::html($html_path_message); ?>');
	wplj(ajax_loader_element).html(`<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/' . $img_ajax_loader)); ?>" />`);

	request_str = 'wpl_format=a:params:main&wpl_function=save_params&table=<?php wpl_esc::attr($db_table); ?>&id=<?php wpl_esc::attr($record_id); ?>' + request_str + '&_wpnonce=<?php wpl_esc::attr($this->nonce); ?>';
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: url,
		data: request_str,
		success: function (data) {
			if (data.success == 1)
			{
				wpl_show_messages(data.message, '<?php wpl_esc::html($html_path_message); ?>', 'wpl_green_msg');
				wplj(ajax_loader_element).html('');

				<?php if ($close_lightbox): ?>
				wplj._realtyna.lightbox.close();
				<?php endif; ?>
			}
			else if (data.success != 1)
			{
				wpl_show_messages(data.message, '<?php wpl_esc::html($html_path_message); ?>', 'wpl_red_msg');
				wplj(ajax_loader_element).html('');
			}
		}
	});
}
</script>