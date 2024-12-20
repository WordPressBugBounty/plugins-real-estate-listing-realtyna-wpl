<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.flex');
_wpl_import('libraries.property');
_wpl_import('libraries.items');

class wpl_listing_controller extends wpl_controller
{
	public function display()
	{
		/** check permission **/
		wpl_global::min_access('agent');
		$function = wpl_request::getVar('wpl_function');
        
        // Check Nonce
        if(!wpl_security::verify_nonce(wpl_request::getVar('_wpnonce', ''), 'wpl_listing')) {
			$this->response(array('success'=>0, 'message'=>wpl_esc::return_html_t('The security nonce is not valid!')));
		}
		
		if($function == 'upload') $this->upload();
		elseif($function == 'title_update') wpl_items::update_file(wpl_request::getVar('attachment'), wpl_request::getVar('pid'), array('item_extra1'=>wpl_request::getVar('value')));
		elseif($function == 'desc_update') wpl_items::update_file(wpl_request::getVar('attachment'), wpl_request::getVar('pid'), array('item_extra2'=>wpl_request::getVar('value')));
		elseif($function == 'cat_update') wpl_items::update_file(wpl_request::getVar('attachment'), wpl_request::getVar('pid'), array('item_cat'=>wpl_request::getVar('value')));
		elseif($function == 'delete_attachment') wpl_items::delete_file(wpl_request::getVar('attachment'), wpl_request::getVar('pid'), wpl_request::getVar('kind'));
		elseif($function == 'sort_attachments') wpl_items::sort_items(wpl_request::getVar('pid'), wpl_request::getVar('order'));
		elseif($function == 'change_status') wpl_items::update_file(wpl_request::getVar('attachment'), wpl_request::getVar('pid'), array('enabled'=>wpl_request::getVar('enabled')));
        elseif($function == 'delete_all_attachments') $this->delete_all_attachments();
	}
	
	public function upload()
	{
		/** import upload library **/
		_wpl_import('assets.packages.ajax_uploader.UploadHandler');
		$kind = wpl_request::getVar('kind', 0);
		$property_id = wpl_request::getVar('pid');
        
        // Get blog ID of property
        $blog_id = wpl_property::get_blog_id($property_id);
        
		$params = array();
		$params['accept_ext'] = wpl_flex::get_field_options(301);

		$extensions = explode(',', $params['accept_ext']['ext_file']);
		$extensionsStr = str_replace(';', '', implode('|', wpl_global::filter_extensions($extensions)));
		
		$custom_op = array(
            'upload_dir' => wpl_global::get_upload_base_path($blog_id),
            'upload_url' => wpl_global::get_upload_base_url($blog_id),
            'accept_file_types' => '/\.('.$extensionsStr.')$/i',
            'max_file_size' => $params['accept_ext']['file_size']*1000,
            'min_file_size' => 1,
            'max_number_of_files' => null
		);

		$upload_handler = new wpl_UploadHandler($custom_op);
		$response = json_decode($upload_handler->json_response ?? '');
		
		if(isset($response->files[0]->error)) return;
		$attachment_categories = wpl_items::get_item_categories('attachment', $kind);
		
		// get item category with first index
		$item_cat = reset($attachment_categories)->category_name;
		$index = floatval(wpl_items::get_maximum_index($property_id, wpl_request::getVar('type'), $kind, $item_cat))+1.00;
		
		$item = array('parent_id'=>$property_id,'parent_kind'=>$kind,'item_type'=>wpl_request::getVar('type'),
				'item_cat'=>$item_cat,'item_name'=>$response->files[0]->name,'creation_date'=>date("Y-m-d H:i:s"),'index'=>$index);
		
		wpl_items::save($item);
	}
    
    public function delete_all_attachments()
    {
        $kind = wpl_request::getVar('kind', 0);
        $pid = wpl_request::getVar('pid');
        
        $attachments = wpl_items::get_items($pid, 'attachment');
        foreach($attachments as $attachment)
        {
            wpl_items::delete_file($attachment->item_name, $pid, $kind);
        }
        
        $this->response(array('success'=>1));
    }
}