<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path . '.scripts.css');
$this->_wpl_import($this->tpl_path . '.scripts.js');
$this->finds = array();
?>
<div class="wrap wpl-wp pwizard-wp wpl_view_container wpl-pwizard-<?php wpl_esc::attr($this->layout); ?>">
    <header>
        <div id="icon-pwizard" class="icon48"></div>
        <h2><?php wpl_esc::e(sprintf(wpl_esc::return_html_t('Add/Edit %s'), wpl_esc::return_html_t(ucfirst($this->kind_label)))); ?></h2>
    </header>

    <div class="wpl_listing_list"><div class="wpl_show_message"></div></div>

    <div class="finilize-message <?php wpl_esc::attr($this->finalized ? 'hide' : ''); ?>" id="wpl_listing_remember_to_finalize" title="<?php wpl_esc::html_t('Click to finalize property ...'); ?>" onclick="wplj('#wpl_slide_label_id10000').trigger('click');">
        <i class="icon-warning"></i>
        <span><?php wpl_esc::html_t('Remember to finalize!'); ?></span>
    </div>
    
    <div class="sidebar-wp">
        <div class="side-2 side-tabs-wp">
            <ul>
                <?php if($this->layout == 'vertical'): ?>
                <?php if($this->mode == 'add'): ?>
                    <li class="wpl-listing-discard-btn">
                        <a href="#" id="wpl_listing_discard" title="<?php wpl_esc::html_t('Click to discard property'); ?>" onclick="wpl_discard('<?php wpl_esc::attr($this->property_id); ?>', 0);">
                            <span id="wpl_listing_discard_loading"><?php wpl_esc::html_t('Discard'); ?></span>
                            <i class="fa fa-times"></i>
                        </a>
                    </li>
                <?php endif; ?>

                <li class="finilized">
                    <a href="#10000" class="tab-finalize wpl_slide_label_id10000" id="wpl_slide_label_id10000" onclick="wpl_finalize(10000, '<?php wpl_esc::attr($this->property_id); ?>');">
                        <span><?php wpl_esc::html_t('Finalize'); ?></span>
                        <i class="icon-finalize"></i>
                    </a>        
                </li>
                <?php endif; ?>
                <?php
                $category_listing_specific_array = array();
                $category_property_type_specific_array = array();
                $i=0;
                foreach($this->field_categories as $category)
				{
                    $display = '';
                    $i++;
                    if(trim($category->listing_specific ?? '') != '')
					{
                        $category_listing_specific_array[$category->id] = array();
                        
                        if(substr($category->listing_specific, 0, 5) == 'type=')
                        {
                            $specified_listings = wpl_global::get_listing_types_by_parent(substr($category->listing_specific, 5));
                            foreach($specified_listings as $listing_type)
                                $category_listing_specific_array[$category->id][] = $listing_type["id"];
                        }
                        else
                        {
                            $specified_listings = explode(',', trim($category->listing_specific ?? '', ', '));
                            $category_listing_specific_array[$category->id] = $specified_listings;
                        }
                        
                        if(!in_array($this->values['listing'], $category_listing_specific_array[$category->id]))
                            $display = "display:none;";
                    }
                    elseif(trim($category->property_type_specific ?? '') != '')
					{
                        $category_property_type_specific_array[$category->id] = array();
                        
                        if(substr($category->property_type_specific, 0, 5) == 'type=')
                        {
                            $specified_property_types = wpl_global::get_property_types_by_parent(substr($category->property_type_specific,5));
                            foreach($specified_property_types as $property_type) 
                                $category_property_type_specific_array[$category->id][] = $property_type["id"];
                        }
                        else
                        {
                            $specified_property_types = explode(',', trim($category->property_type_specific ?? '', ', '));
                            $category_property_type_specific_array[$category->id] = $specified_property_types;
                        }
                        
                        if(!in_array($this->values['property_type'], $category_property_type_specific_array[$category->id])) 
                            $display = "display:none;";
                    }
                    ?>
                    <li>
                        <a style="<?php wpl_esc::attr($display); ?>" href="#<?php wpl_esc::attr($category->id); ?>" class="wpl_slide_label wpl_slide_label_prefix_<?php wpl_esc::attr($category->prefix); ?>" id="wpl_slide_label_id<?php wpl_esc::attr($category->id); ?>" onclick="wpl_wizard('<?php wpl_esc::attr($category->id); ?>', '.side-tabs-wp', '.wpl_slide_container', 'currentTab','Tab');" >
                            <?php if($this->layout == 'horizontal'): ?>
                                <span class="wpl-pwizard-tab-number"><?php wpl_esc::attr($i); ?></span>
                            <?php endif; ?>
                            <?php wpl_esc::html_t($category->name); ?>
                        </a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <div class="side-12 side-content-wp">
            <?php 
            foreach($this->field_categories as $category)
            {
                $display = true;
                
                if(trim($category->listing_specific ?? '') != '' && !in_array($this->values['listing'], $category_listing_specific_array[$category->id])) 
                {
                    $display = "display:none;";
                }
                elseif(trim($category->property_type_specific ?? '') != '' && !in_array($this->values['property_type'], $category_property_type_specific_array[$category->id]))
                {
                    $display = "display:none;";
                }
                ?>
                <div class="pwizard-panel wpl_slide_container wpl_slide_container<?php wpl_esc::attr($category->id); ?>" id="wpl_slide_container_id<?php wpl_esc::attr($category->id); ?>" style="<?php wpl_esc::attr($display); ?>">
                    <?php $this->generate_slide($category); ?>
                </div>
                <?php 
            } 
            ?>
            <div class="wpl_slide_container wpl_slide_container10000" id="wpl_slide_container_id10000">
                <div id="wpl_slide_container_id10000_befor_save" class="hide"><img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" /></div>
                <div id="wpl_slide_container_id10000_after_save" class="hide">
                    <div class="after-finilize-wp">
                        <div class="finilize-icon"></div>
                        <div class="message-wp">
                            <span>
                                <?php wpl_esc::html_t('Your property successfully finalized!'); ?>
                            </span>
                            <div class="finilize-btn-wp">
                                <?php
                                    $listing_target_page = wpl_global::get_client() == 1 ? wpl_global::get_setting('backend_listing_target_page') : NULL;
                                    
                                    $property_link = wpl_property::get_property_link('', $this->property_id, $listing_target_page);
                                    $new_link = wpl_global::remove_qs_var('pid', wpl_global::get_full_url());
                                    if($this->kind) $new_link = wpl_global::add_qs_var('kind', $this->kind, $new_link);
                                    
                                    if(wpl_global::get_client() == 1) $manager_link = wpl_global::add_qs_var('kind', $this->kind, wpl_global::get_wpl_admin_menu('wpl_admin_listings'));
                                    else $manager_link = wpl_global::add_qs_var('kind', $this->kind, wpl_global::remove_qs_var('wplmethod', wpl_global::remove_qs_var('pid')));
                                ?>
                                <a class="wpl-button button-2" target="_blank" href="<?php wpl_esc::url($property_link); ?>"><?php wpl_esc::html_t('View this listing'); ?></a>
                                <a class="wpl-button button-2" href="<?php wpl_esc::url($new_link); ?>"><?php wpl_esc::html_t('Add new listing'); ?></a>
                                <a class="wpl-button button-2" href="<?php wpl_esc::url($manager_link); ?>"><?php wpl_esc::e(sprintf(wpl_esc::return_html_t('%s Manager'), wpl_esc::return_html_t($this->kind_label))); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <?php if($this->layout == 'horizontal'): ?>
            <ul class="wpl-pwizard-btns clearfix">
                <li>
                   <span class="wpl-button button-1 wpl-save-btn wpl-pwizard-next" onclick="wpl_wizard('null', '.side-tabs-wp', '.wpl_slide_container', 'currentTab','Next');">
                       <?php wpl_esc::html_t('Next'); ?>
                       <span class="fa fa-arrow-right"></span>
                   </span>
                </li>
                <li>
                    <span class="wpl-button button-1 wpl-save-btn wpl-pwizard-prev" onclick="wpl_wizard('null', '.side-tabs-wp', '.wpl_slide_container', 'currentTab','Prev');">
                        <span class="fa fa-arrow-left"></span>
                        <?php wpl_esc::html_t('Prev'); ?>
                    </span>
                </li>
                <li class="finilized">
                    <a href="#10000" class="tab-finalize wpl_slide_label_id10000" id="wpl_slide_label_id10000" onclick="wpl_finalize(10000, '<?php wpl_esc::attr($this->property_id); ?>');">
                        <span><?php wpl_esc::html_t('Finalize'); ?></span>
                        <i class="icon-finalize"></i>
                    </a>
                </li>
                <?php if($this->mode == 'add'): ?>
                    <li class="wpl-listing-discard-btn">
                        <a href="#" id="wpl_listing_discard" title="<?php wpl_esc::html_t('Click to discard property'); ?>" onclick="wpl_discard('<?php wpl_esc::attr($this->property_id); ?>', 0);">
                            <span id="wpl_listing_discard_loading"><?php wpl_esc::html_t('Discard'); ?></span>
                            <i class="fa fa-times"></i>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>
    </div>
    <div id="wpl_listing_edit_div" class="wpl_lightbox fanc-box-wp wpl_hidden_element"></div>
    <footer>
        <div class="logo"></div>
    </footer>
</div>
<script type="text/javascript">
var finalized = <?php wpl_esc::e($this->finalized); ?>;
var categories_id = ["1"];


function wpl_wizard(cat_id,tab_class,tab_container_class,current_tab_class,clicked_button)
{
    <?php if($this->layout == 'horizontal'): ?>
    if(wplj(".side-tabs-wp li.active").next().children("a.wpl_slide_label_prefix_ad").is(":visible"))
	{
        if(typeof wpl_initialize == 'function') wpl_initialize();
    }

    if(wplj(".side-tabs-wp ul li.active").length !== 0)
    {
        var category_id = wplj(".side-tabs-wp ul li.active a").attr("href");
        category_id = category_id.split("#");
        category_id = category_id[1];
    }
	
	if(clicked_button=="Tab")
	{
		rta.internal.slides.open(cat_id,tab_class,tab_container_class,current_tab_class);
	}
	else if(clicked_button=="Next")
	{
		var next_tab_category_id = wplj(".side-tabs-wp li.active").next().children("a").attr("href");
		next_tab_category_id = next_tab_category_id.split("#");
		next_tab_category_id = next_tab_category_id[1];
		
		if(wpl_category_validation_check(true, category_id) && wplj(".side-tabs-wp li.active").next().children("a").is(":visible"))
		{
			if(wplj.inArray(next_tab_category_id,categories_id) == -1){ categories_id.push(next_tab_category_id);}
			rta.internal.slides.open(next_tab_category_id,tab_class,tab_container_class,current_tab_class);
			wplj("html, body").animate({ scrollTop: wplj(".wpl-pwizard-horizontal").offset().top }, "slow");
		}
	}
	else if(clicked_button=="Prev")
	{
		var prev_tab_category_id = wplj(".side-tabs-wp li.active").prev().children("a").attr("href");
		prev_tab_category_id = prev_tab_category_id.split("#");
		prev_tab_category_id = prev_tab_category_id[1];
		if(wplj(".side-tabs-wp li.active").prev().children("a").is(":visible"))
		{

			rta.internal.slides.open(prev_tab_category_id,tab_class,tab_container_class,current_tab_class);
			wplj("html, body").animate({scrollTop: wplj(".wpl-pwizard-horizontal").offset().top}, "slow");
		}
	}
    <?php else: ?>
    rta.internal.slides.open(cat_id,tab_class,tab_container_class,current_tab_class);
    <?php endif; ?>
}

function wpl_listing_changed(id)
{
    <?php
    /** Tabs **/
    foreach($category_listing_specific_array as $id => $cat_arr)
    {
        if(count($cat_arr)>0)
        {
            $cond = array();
            foreach ($cat_arr as $cati) $cond[] = 'id == ' . $cati;
            wpl_esc::e("if(" . implode('||', $cond) . ') wplj("#wpl_slide_label_id' . $id . '").slideDown(500); else wplj("#wpl_slide_label_id' . $id . '").slideUp(500);');
        }
    }

    /** Fields **/
    foreach(wpl_flex::$category_listing_specific_array as $id => $fld_arr)
    {
        if(count($fld_arr)>0)
        {
            $cond = array();
            foreach ($fld_arr as $fldi) $cond[] = 'id == ' . $fldi;
			wpl_esc::e("if(" . implode('||', $cond) . ') wplj("#wpl_listing_field_container' . $id . '").slideDown(500); else wplj("#wpl_listing_field_container' . $id . '").slideUp(500);');
        }
    }
    ?>
}

function wpl_property_type_changed(id)
{
    <?php
    /** Tabs **/
    foreach($category_property_type_specific_array as $id => $cat_arr)
    {
        if(count($cat_arr)>0)
        {
            $cond = array();
            foreach ($cat_arr as $cati)
            {
                if(empty($cati)) $cati = 0;
                $cond[] = 'id == ' . $cati;
            }
			wpl_esc::e("if(" . implode('||', $cond) . ') wplj("#wpl_slide_label_id' . $id . '").slideDown(500); else wplj("#wpl_slide_label_id' . $id . '").slideUp(500);');
        }
    }

    /** Fields **/
    foreach(wpl_flex::$category_property_type_specific_array as $id => $fld_arr)
    {
        if(count($fld_arr)>0)
        {
            $cond = array();
            foreach ($fld_arr as $fldi)
            {
                if(empty($fldi)) $fldi = 0;
                $cond[] = 'id == ' . $fldi;
            }
			wpl_esc::e("if(" . implode('||', $cond) . ') wplj("#wpl_listing_field_container' . $id . '").slideDown(500); else wplj("#wpl_listing_field_container' . $id . '").slideUp(500);');
        }
    }
    ?>
}

function wpl_field_specific_changed(field, visible)
{
    /** Fields **/
    field = '#wpl_c_'+field;
    if(!wplj(field).attr('data-specific')) return;

    var visible = (typeof visible == 'undefined') ? true : visible;
    var children = wplj(field).data('specific').split(',');
    var value = (wplj(field).is(":checkbox") || wplj(field).is(":radio")) ? wplj(field).is(":checked") : wplj(field).val();

    for (var i = 0; i < children.length; i++) 
    {
        var split = children[i].split(':');
        var child = '#wpl_listing_field_container'+split[0];
        var child_visible = false;
        
        if(!visible || split[1] != value)
        {
            child_visible = false;
            wplj(child).slideUp(500);
        }
        else
        {
            child_visible = true;
            wplj(child).slideDown(500);
        }
        
        wpl_field_specific_changed(split[0], child_visible)
    }
}

function wpl_get_tinymce_content(html_element_id)
{
	if(wplj("#wp-"+html_element_id+"-wrap").hasClass("tmce-active"))
	{
        return tinyMCE.get(html_element_id).getContent();
	}
	else
	{
		return wplj("#"+html_element_id).val();
	}
}

function wpl_finalize(slide_id, item_id)
{
    /** validate form **/
    if(!wpl_validation_check()) return;

    /** Hide Discard Button **/
    wplj(".wpl-listing-discard-btn").hide();
    
    rta.internal.slides.open(slide_id, '.side-tabs-wp', '.wpl_slide_container', 'currentTab');

    wplj("#wpl_slide_container_id10000_befor_save").show();
    wplj("#wpl_slide_container_id10000_after_save").hide();

    request_str = 'wpl_format=b:listing:ajax&wpl_function=finalize&item_id=' + item_id + '&mode=<?php wpl_esc::attr($this->mode); ?>&_wpnonce=<?php wpl_esc::attr($this->nonce); ?>';

    /** run ajax query **/
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (data) {
			if (data.success == 1)
			{
				wplj("#wpl_slide_container_id10000_befor_save").hide();
				wplj("#wpl_slide_container_id10000_after_save").show();

				finalized = 1;
				wplj("#wpl_listing_remember_to_finalize").hide();
			}
			else if (data.success != 1)
			{
				wplj("#wpl_slide_container_id10000_befor_save").hide();
				wplj("#wpl_slide_container_id10000_after_save").show();
			}
		}
	});
}

<?php if($this->mode == 'add'): ?>
function wpl_discard(item_id, confirmed)
{
	var message_path = '.wpl_listing_list .wpl_show_message';
	if(!confirmed)
	{
		var message = "<?php wpl_esc::html_t('Are you sure you want to remove this listing?'); ?>";
		message += '<span class="wpl_actions" onclick="wpl_discard(\''+item_id+'\', 1);"><?php wpl_esc::html_t('Yes'); ?></span>&nbsp;<span class="wpl_actions" onclick="wpl_remove_message(\'' + message_path + '\');"><?php wpl_esc::html_t('No'); ?></span>';
		
		wpl_show_messages(message, message_path);
		return false;
	}
	else if(confirmed) wpl_remove_message(message_path);
	
	Realtyna.ajaxLoader.show("#wpl_listing_discard_loading", 'tiny', 'rightOut');
   	
	var request_str = "wpl_format=b:listings:ajax&wpl_function=purge_property&pid="+item_id+'&_wpnonce=<?php wpl_esc::attr(wpl_security::create_nonce('wpl_listings')); ?>';

	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (data) {
			if(data.success == 1)
			{
				window.location = "<?php wpl_esc::url($manager_link); ?>";
			}
			else if(data.success != 1)
			{
				wpl_show_messages(data.message, '.wpl_listing_list .wpl_show_message', 'wpl_red_msg');
			}
		}
	});
}
<?php endif; ?>

function wpl_validation_check(go_to_error)
{
    if(typeof go_to_error === 'undefined') go_to_error = true;
    
    <?php
    foreach(wpl_flex::$wizard_js_validation as $js_validation)
    {
        if(trim($js_validation ?? '') == '') continue;
		wpl_esc::e($js_validation);
    }
    ?>
    return true;
}

function wpl_category_validation_check(go_to_error, category)
{
    if(typeof go_to_error === 'undefined') go_to_error = true;
    if(typeof category === 'undefined') category = 0;
    
    <?php
    foreach(wpl_flex::$wizard_js_validation as $dbst_id=>$js_validation)
    {
        if(trim($js_validation ?? '') == '') continue;
        
        wpl_esc::e('if(category == "'.wpl_flex::get_dbst_key('category', $dbst_id).'")
        {
        ');
		wpl_esc::e($js_validation);
		wpl_esc::e('
        }
        ');
    }
    ?>
    return true;
}

function wpl_notice_required_fields(fields, change_section)
{
    var scrolled = false, error_visibility = 5000, parent_offset, field_label;

    if(change_section)
    {
        rta.internal.slides.open(change_section, '.side-tabs-wp', '.wpl_slide_container', 'currentTab');
        wplj("#wpl_slide_label_id"+change_section).trigger('click');
    }
    
    if(!Array.isArray(fields)) fields = [fields];

    for(var i in fields)
    {
        if(wplj(fields[i]).length == 0) continue;

        if(!scrolled)
        {
            parent_offset = wplj(fields[i]).parent().offset();
            window.scrollTo(0, parent_offset.top - 100);
            scrolled = true;
        }

        wplj(fields[i]).focus();
        field_label = wplj(fields[i]).parent().prev('label').length ? wplj(fields[i]).parent().prev('label') : wplj(fields[i]).prev('label');

        if(field_label.length > 0)
        {
            wplj(field_label).addClass('error');
            window.setTimeout(function() {wplj(field_label).removeClass('error');}, error_visibility);
        }
    }
}
</script>