<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'seo_patterns' and !$done_this)
{
    $values = json_decode($setting_record->setting_value ?? '', true);
    if(!$values) $values = array();
    
    $kinds = wpl_flex::get_kinds('wpl_properties');
    $property_types = wpl_global::get_property_types();
    $patterns = array(
        'property_alias_pattern'=>wpl_esc::return_html_t('Listing Link Pattern'),
        'property_title_pattern'=>wpl_esc::return_html_t('Listing Title'),
        'property_page_title_pattern'=>wpl_esc::return_html_t('Listing Page Title'),
        'meta_description_pattern'=>wpl_esc::return_html_t('Meta Description'),
        'meta_keywords_pattern'=>wpl_esc::return_html_t('Meta Keywords')
    );
?>
<div class="prow wpl_setting_form_container wpl_st_type<?php wpl_esc::attr($setting_record->type); ?> wpl_st_<?php wpl_esc::attr($setting_record->setting_name); ?>" id="wpl_st_<?php wpl_esc::attr($setting_record->id); ?>">
    <div class="wpl-seo-patterns-wp" id="seo_patterns_wp_<?php wpl_esc::attr($setting_record->id); ?>">
        <div class="wpl-js-tab-system">
            <div class="wpl-seo-patterns-tab wpl-gen-tab-wp">
                <ul class="wpl-tabs">
                    <?php foreach($kinds as $kind): ?>
                        <li>
                            <a href="#wpl-seo-tab-content-<?php wpl_esc::attr($kind['id']); ?>" class="" data-for="wpl_seo_patterns_kind<?php wpl_esc::attr($kind['id']); ?>"><?php wpl_esc::html($kind['name']); ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="wpl-seo-patterns-tab-content">
                <div class="wpl-btns-wp">
                    <div class="clearfix">
                        <button onclick="wpl_seo_patterns_save();" class="wpl-button button-1">
                            <?php wpl_esc::html('Save'); ?>
                            <span class="ajax-inline-save" id="wpl_ajax_loader_<?php wpl_esc::attr($setting_record->id); ?>"></span>
                        </button>
                    </div>
                    <div id="wpl_seo_patterns_show_message" class="wpl-notify-msg"></div>
                </div>
                <div class="wpl-gen-tab-contents-wp">
                    <?php foreach($kinds as $kind): ?>
                        <div class="wpl-gen-tab-content wpl-seo-patterns-kind-wp" id="wpl-seo-tab-content-<?php wpl_esc::attr($kind['id']); ?>">
                            <?php if(in_array($kind['id'], array('1', '0'))): ?>
                                <div class="wpl-gen-tab-content" id="wpl_seo_patterns_kind<?php wpl_esc::attr($kind['id']); ?>">
                                    <?php foreach($property_types as $property_type): ?>
                                        <div class="wpl-gen-accordion">
                                            <h4 class="wpl-gen-accordion-title" data-for="wpl_seo_patterns_ptype<?php wpl_esc::attr($kind['id']); ?>_<?php wpl_esc::attr($property_type['id']); ?>">
                                                <span><?php wpl_esc::html($property_type['name']); ?></span>
                                            </h4>
                                            <div class="wpl-gen-accordion-cnt" id="wpl_seo_patterns_ptype<?php wpl_esc::attr($kind['id']); ?>_<?php wpl_esc::attr($property_type['id']); ?>">
                                                <?php foreach($patterns as $key=>$pattern): ?>
                                                    <div class="prow">
                                                        <div class="text-wp">
                                                            <label for="wpl_seo_patterns_ptype<?php wpl_esc::attr($kind['id']); ?>_<?php wpl_esc::attr($property_type['id']); ?>_<?php wpl_esc::attr($key); ?>"><?php wpl_esc::html($pattern); ?></label>
                                                            <textarea class="long" name="seo_patterns[<?php wpl_esc::attr($kind['id']); ?>][<?php wpl_esc::attr($property_type['id']); ?>][<?php wpl_esc::attr($key); ?>]" id="wpl_seo_patterns_ptype<?php wpl_esc::attr($kind['id']); ?>_<?php wpl_esc::attr($property_type['id']); ?>_<?php wpl_esc::attr($key); ?>"><?php wpl_esc::html((isset($values[$kind['id']]) and isset($values[$kind['id']][$property_type['id']]) and isset($values[$kind['id']][$property_type['id']][$key])) ? $values[$kind['id']][$property_type['id']][$key] : ''); ?></textarea>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div id="wpl_seo_patterns_kind<?php wpl_esc::attr($kind['id']); ?>">
                                    <?php foreach($patterns as $key=>$pattern): ?>
                                        <div class="prow">
                                            <div class="text-wp">
                                                <label for="wpl_seo_patterns_ptype<?php wpl_esc::attr($kind['id']); ?>_<?php wpl_esc::attr($key); ?>"><?php wpl_esc::html($pattern); ?></label>
                                                <textarea class="long" name="seo_patterns[<?php wpl_esc::attr($kind['id']); ?>][<?php wpl_esc::attr($key); ?>]" id="wpl_seo_patterns_ptype<?php wpl_esc::attr($kind['id']); ?>_<?php wpl_esc::attr($key); ?>"><?php wpl_esc::html((isset($values[$kind['id']]) and isset($values[$kind['id']][$key])) ? $values[$kind['id']][$key] : ''); ?></textarea>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
function wpl_seo_patterns_save()
{
    wpl_remove_message();
    
    var ajax_loader_element = "#wpl_ajax_loader_<?php wpl_esc::attr($setting_record->id); ?>";
	wplj(ajax_loader_element).html('<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />');
    
    var wpl_patterns = '';
    var request_str = '';
    
    /** general options **/
	wplj("#seo_patterns_wp_<?php wpl_esc::attr($setting_record->id); ?> textarea").each(function(index, element)
	{
		wpl_patterns += element.name+"="+wplj(element).val()+"&";
	});
    
    request_str = 'wpl_format=b:settings:ajax&wpl_function=save_seo_patterns&'+wpl_patterns+'&_wpnonce=<?php wpl_esc::js($nonce); ?>';
	
	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
        dataType: 'json',
		success: function(data)
		{
            if(data.success)
            {
                wpl_show_messages(data.message, '#wpl_seo_patterns_show_message', 'wpl_green_msg');
                wplj(ajax_loader_element).html('');
            }
            else
            {
                wpl_show_messages(data.message, '#wpl_seo_patterns_show_message', 'wpl_red_msg');
                wplj(ajax_loader_element).html('');
            }
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
            wpl_show_messages('<?php wpl_esc::js_t('Error Occured.'); ?>', '#wpl_seo_patterns_show_message', 'wpl_red_msg');
			wplj(ajax_loader_element).html('');
		}
	});
}
</script>
<?php
    $done_this = true;
}