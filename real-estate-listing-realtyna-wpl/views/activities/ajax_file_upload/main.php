<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** activity class **/
class wpl_activity_main_ajax_file_upload extends wpl_activity
{
    public $tpl_path = 'views.activities.ajax_file_upload.tmpl';
    
	public function start($layout, $params)
	{
        /** set params **/
        $html_element_id = $params['html_element_id'] ?? 'wpl_file';
        $js_function = $params['js_function'] ?? $html_element_id . '_upload';
        $element_class = $params['element_class'] ?? 'wpl-button button-1';
        $html_path_message = $params['html_path_message'] ?? '.wpl_show_message';
        $html_ajax_loader = $params['html_ajax_loader'] ?? '#wpl_file_ajax_loader';
        $img_ajax_loader = $params['img_ajax_loader'] ?? 'ajax-loader3.gif';
        $request_str = $params['request_str'] ?? '';
        $valid_extensions = (isset($params['valid_extensions']) and is_array($params['valid_extensions'])) ? $params['valid_extensions'] : array('jpg', 'gif', 'png');
        $footer_js = $params['footer_js'] ?? true;
        $js_callback = $params['js_callback'] ?? false;

        $this->setViewVars(compact(
            'html_element_id',
            'js_function',
            'element_class',
            'html_path_message',
            'html_ajax_loader',
            'img_ajax_loader',
            'request_str',
            'valid_extensions',
            'footer_js',
            'js_callback'
        ));
		/** include layout **/
		$layout_path = _wpl_import($layout, true, true);
		include $layout_path;
	}
}