<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'gallery' and !$done_this)
{
    $extentions = explode(',', $options['ext_file']);
	$ext_str = '';
    foreach($extentions as $extention) $ext_str .= $extention . '|';

    // remove last |
    $ext_str = substr($ext_str, 0, -1);
    $ext_str = rtrim($ext_str, ';');
    $max_size = $options['file_size'];
	if($max_size == '') {
		$max_size = 2000;
	}

    // Load Handlebars Templates
    wpl_esc::e(wpl_global::load_js_template('dbst-wizard-gallery'));
?>
<div class="video-tabs-wp" id="gallery-tabs-wp-container">
	<ul>
        <li id="wpl_gallery_uploader_tab" onclick="wpl_gallery_select_tab('wpl_gallery_uploader_tab', 'wpl_gallery_uploader'); return false;" class="active"><a href="#wpl_gallery_uploader"><?php wpl_esc::html_t('Image uploader'); ?></a></li>
		<li id="wpl_gallery_external_tab" onclick="wpl_gallery_select_tab('wpl_gallery_external_tab', 'wpl_gallery_external'); return false;"><a href="#wpl_gallery_external"><?php wpl_esc::html_t('External images'); ?></a></li>
	</ul>
</div>

<div class="gallary-btn-wp">
    <div id="wpl_gallery_uploader" class="wpl_gallery_method_container">
        <div class="wpl-button button-1 button-upload">
            <span><?php wpl_esc::html_t('Select files'); ?></span>
            <!-- The file input field used as target for the file upload widget -->
            <input id="fileupload" type="file" name="files[]" multiple="multiple" />
        </div>
        <div class="field-desc">
            <?php wpl_esc::html_t('To select images click on "Select files."'); ?>
        </div>
    </div>
    <div id="wpl_gallery_external" class="wpl_gallery_method_container" style="display: none;">
        <?php if(!wpl_global::check_addon('pro')): ?>
        <div class="field-desc">
            <?php wpl_esc::html_t('Pro addon must be installed for this!'); ?>
        </div>
        <?php else: ?>
        <button class="wpl-button button-1" onclick="add_external_image();"><?php wpl_esc::html_t('Add image') ?></button>
        <div class="field-desc">
            <?php wpl_esc::html_t('To insert images click on "Add image."'); ?>
        </div>
        <div id="wpl_gallery_external_cnt" style="margin-top: 10px; display: none;">
            <div class="gallery-external-wp" id="gallery-external-cnt">
                <div class="row">
                    <label for="gallery_external_link"><?php wpl_esc::html_t('Image links'); ?></label>
                    <textarea name="gallery_external_link${count}" rows="8" cols="50" id="gallery_external_link" placeholder="<?php wpl_esc::attr_t('Enter each image link in a new line'); ?>"></textarea>
                    <button class="wpl-button button-1" onclick="wpl_gallery_external_save();"><?php wpl_esc::html_t('Save'); ?></button>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- The global progress bar -->
<div id="progress_img">
    <div id="progress" class="progress progress-success progress-striped">
        <div class="bar"></div>
    </div>
</div>

<div class="error_uploaded_message" id="error_ajax_img">
</div>

<!-- The container for the uploaded files -->
<div id="files" class="gallary-images-wp wpl_files_container">
    <div class="wpl-mass-actions">
        <button class="wpl-button button-1" onclick="wpl_gallery_delete_all();"><?php wpl_esc::html_t('Delete all images'); ?></button>
    </div>
    <ul class="ui-sortable" id="ajax_gal_sortable">
        <?php
        // get uploaded images and show them
        $gall_items = wpl_items::get_items($item_id, 'gallery', $this->kind, '', '');
		
        // Get blog ID of property
        $blog_id = wpl_property::get_blog_id($item_id);
        
        // Media Confirm Status
        $media_confirm = wpl_global::get_setting('listing_media_confirm');
    
        $image_folder = wpl_items::get_folder($item_id, $this->kind, $blog_id);
        $image_path = wpl_items::get_path($item_id, $this->kind, $blog_id, false);
        $image_categories = wpl_items::get_item_categories('gallery', $this->kind);
        $max_img_index = 0;
		
        foreach($gall_items as $image)
		{
            $image->index = intval($image->index);
            if($max_img_index < $image->index) $max_img_index = $image->index;
			
			/** set resize method parameters **/
			$params = array();
			$params['image_name'] = $image->item_name;
			$params['image_parentid'] = $image->parent_id;
			$params['image_parentkind'] = $image->parent_kind;
			$params['image_source'] = $image_path.$image->item_name;
			
			$image_thumbnail_url = wpl_images::create_gallery_image(80, 60, $params, 0, 0);
            if($image->item_cat == 'external') $image_thumbnail_url = $image->item_extra3;
            ?>

            <li class="ui-state-default" id="ajax_gallery<?php wpl_esc::attr($image->index); ?>">
                <input type="hidden" class="gal_name" value="<?php wpl_esc::attr($image->item_name); ?>" />
                <div class="image-box-wp">
                    <div class="image-wp">
                        <img src="<?php wpl_esc::url($image_thumbnail_url); ?>" width="80" height="60" />
                    </div>
                    <div class="info-wp">
                        <div class="row">
                            <label for=""><?php wpl_esc::html_t('Image Title') ?>:</label>
                            <input type="text" class="gal_title" value="<?php wpl_esc::attr($image->item_extra1); ?>" onchange="ajax_gallery_title_update('<?php wpl_esc::js($image->item_name); ?>', this.value);" size="20" />
                        </div>
                        <div class="row">
                            <label for=""><?php wpl_esc::html_t('Image Description'); ?>:</label>
                            <input type="text" class="gal_desc" value="<?php wpl_esc::attr($image->item_extra2); ?>" onchange="ajax_gallery_desc_update('<?php wpl_esc::js($image->item_name); ?>', this.value);" size="50" />
                        </div>
                        <div class="row">
                            <label for=""><?php wpl_esc::html_t('Image Category'); ?>:</label>
                            <select name="img_cat" class="gal_cat" onchange="ajax_gallery_cat_update('<?php wpl_esc::js($image->item_name); ?>', this.value);">
							<?php
								foreach($image_categories as $img_cat)
								{
									wpl_esc::e('<option value="'. wpl_esc::return_attr($img_cat->category_name) .'"');
									if($image->item_cat == $img_cat->category_name) wpl_esc::e('selected="selected"');
									wpl_esc::e('>'. wpl_esc::return_html($img_cat->category_name).'</option>');
								}
                            ?>
                            </select>
                        </div>
                    </div>
                    <div class="actions-wp">
                        <div class="action-gal-btn">
                            <i class="action-btn icon-move"></i>
                        </div>
                        <div class="action-gal-btn ajax_gallery_middle_td" onclick="ajax_gallery_image_delete('<?php wpl_esc::js($image->item_name); ?>', 'ajax_gallery<?php wpl_esc::js($image->index); ?>');">
                            <i class="action-btn icon-recycle"></i>
                        </div>
                        <?php
                            if($image->enabled and $media_confirm) wpl_esc::e('<div class="action-gal-btn" id="active_image_tag_' . $image->index . '" onclick="wpl_image_enabled(\'' . $image->item_name . '\',' . $image->index . ');"><i class="action-btn icon-enabled" title="'.wpl_esc::return_html_t('Enabled').'"></i></div>');
                            elseif($media_confirm) wpl_esc::e('<div class="action-gal-btn" id="active_image_tag_' . $image->index . '" onclick="wpl_image_enabled(\'' . $image->item_name . '\',' . $image->index . ');"><i class="action-btn icon-disabled" title="'.wpl_esc::return_html_t('Disabled').'"></i></div>');
                        ?>
                        <input type="hidden" id="enabled_image_field_<?php wpl_esc::attr($image->index); ?>" value="<?php wpl_esc::attr($image->enabled); ?>"/>
                    </div>
                </div>
            </li>
            <?php
        }
        ?>
    </ul>
    <?php
		$image_categories_html = '';
		foreach($image_categories as $img_cat)
		{
			$image_categories_html .= ' <option value="' . $img_cat->category_name . '">' . wpl_esc::return_html_t($img_cat->category_name) . '</option>';
		}
    ?>
</div>

<script type="text/javascript">
jQuery(document).ready(function()
{
	wpl_gallery_sortable();
});

function wpl_gallery_sortable(destroy)
{
    if(typeof destroy == 'undefined') destroy = false;
    if(destroy) wplj("#ajax_gal_sortable").sortable('destroy');
    
    wplj("#ajax_gal_sortable").sortable(
	{
		placeholder: "wpl-sortable-placeholder",
        opacity: 0.7,
        forcePlaceholderSize: true,
        cursor: "move",
        axis: "y",
		stop: function(event, ui)
		{
			var sort_str = "";
			wplj("#ajax_gal_sortable .gal_name").each(function(ind, elm)
            {
				sort_str += elm.value + ",";
			});
	
			wplj.post("<?php wpl_esc::current_url(); ?>", "&wpl_format=b:listing:gallery&wpl_function=sort_images&pid=<?php wpl_esc::attr($item_id); ?>&order=" + encodeURIComponent(sort_str) + '&_wpnonce=<?php wpl_esc::attr($nonce); ?>', function(data) {
			});
		}
	});
}

var img_counter = parseInt(<?php wpl_esc::numeric($max_img_index); ?>) + 1;

jQuery(document).ready(function()
{
	var url = '<?php wpl_esc::current_url(); ?>&wpl_format=b:listing:gallery&wpl_function=upload&pid=<?php wpl_esc::attr($item_id); ?>&kind=<?php wpl_esc::attr($this->kind); ?>&type=gallery&_wpnonce=<?php wpl_esc::attr($nonce); ?>';

    wplj('#fileupload').fileupload(
    {
        url: url,
        acceptFileTypes: /(<?php wpl_esc::e($ext_str); ?>)$/i,
        dataType: 'json',
        maxFileSize: <?php wpl_esc::numeric($max_size * 1000); ?>,
        sequentialUploads: true,
        done: function(e, data)
        {
            wplj(data.result.files).each(function(index, file)
            {
                if (file.error !== undefined)
                {
                    wplj('<div class="row"/>').text(file.error).appendTo('#files');
                }
                else if (file.thumbnailUrl !== undefined) {

                    var hbSource   = wplj("#dbst-wizard-gallery").html();
                    var hbTemplate = Handlebars.compile(hbSource);
                    var hbHTML     = hbTemplate({
                        index: img_counter,
                        name: file.name,
                        enabled_title: "<?php wpl_esc::attr_t('Enabled'); ?>",
                        selectOptions: "<?php wpl_esc::escapeQuote($image_categories_html) ?>",
                        imageFolder: "<?php wpl_esc::attr($image_folder); ?>",
                        lblImageTitle: "<?php wpl_esc::attr_t('Image Title'); ?>",
                        lblImageDesc: "<?php wpl_esc::attr_t('Image Description'); ?>",
                        lblImageCat: "<?php wpl_esc::attr_t('Image Category'); ?>",
                        mediaConfirm: <?php wpl_esc::e($media_confirm ? 'true' : 'false'); ?>
                    });

                    wplj(hbHTML).hide().appendTo('#ajax_gal_sortable').slideDown();
                    img_counter++;
                }
                else
                    wplj('<div class="row"/>').text(file.name).appendTo('#files');
            }).promise().done(function()
            {
                wplj('#progress_img').hide();
                wpl_gallery_sortable(true);
            });
        },
        progressall: function(e, data)
        {
            wplj('#progress_img').show();
            var progress = parseInt(data.loaded / data.total * 100, 10);
            wplj('#progress_img #progress .bar').css('width', progress + '%');
            wplj("#error_ajax_img").html("");
        },
        processfail: function(e, data)
        {
            wplj('#progress_img').hide();
            wplj("#error_ajax_img").html("<span color='red'><?php wpl_esc::attr_t('Error occured'); ?> : " + data.files[data.index].name + " " + data.files[data.index].error + "</span>");
            wplj("#error_ajax_img").show('slow');
        }
    });
});

function ajax_gallery_title_update(image, value)
{
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: "wpl_format=b:listing:gallery&wpl_function=title_update&pid=<?php wpl_esc::attr($item_id); ?>&image="+image+"&value="+value+"&kind=<?php wpl_esc::attr($this->kind); ?>&_wpnonce=<?php wpl_esc::attr($nonce); ?>",
	});
}

function ajax_gallery_desc_update(image, value)
{
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: "wpl_format=b:listing:gallery&wpl_function=desc_update&pid=<?php wpl_esc::attr($item_id); ?>&image="+image+"&value="+value+"&kind=<?php wpl_esc::attr($this->kind); ?>&_wpnonce=<?php wpl_esc::attr($nonce); ?>",
	});
}

function ajax_gallery_cat_update(image, value)
{
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: "wpl_format=b:listing:gallery&wpl_function=cat_update&pid=<?php wpl_esc::attr($item_id); ?>&image="+image+"&value="+value+"&kind=<?php wpl_esc::attr($this->kind); ?>&_wpnonce=<?php wpl_esc::attr($nonce); ?>",
	});
}

function ajax_gallery_image_delete(image, id)
{
	if(!confirm("<?php wpl_esc::attr_t('Are you sure?'); ?>")) return;

	wplj.ajax({
		type: 'POST',
		dataType: 'HTML',
		url: '<?php wpl_esc::current_url(); ?>',
		data: "wpl_format=b:listing:gallery&wpl_function=delete_image&pid=<?php wpl_esc::attr($item_id); ?>&image="+encodeURIComponent(image)+"&kind=<?php wpl_esc::attr($this->kind); ?>&_wpnonce=<?php wpl_esc::attr($nonce); ?>",
		success: function (data) {
			wplj("#" + id).slideUp(400, function(){
				wplj(this).remove();
			});
		}
	});
}

function wpl_gallery_delete_all()
{
    if(!confirm("<?php wpl_esc::attr_t('Are you sure?'); ?>")) return;

	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: "wpl_format=b:listing:gallery&wpl_function=delete_all_images&pid=<?php wpl_esc::attr($item_id); ?>&kind=<?php wpl_esc::attr($this->kind); ?>&_wpnonce=<?php wpl_esc::attr($nonce); ?>",
		success: function (data) {
			wplj("#ajax_gal_sortable li").slideUp(400, function(){
				wplj(this).remove();
			});
		}
	});
}

function wpl_image_enabled(gallery, id)
{
	var status = Math.abs(wplj("#enabled_image_field_" + id).val() - 1);
	wplj("#enabled_image_field_" + id).val(status);

	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: "wpl_format=b:listing:gallery&wpl_function=change_status&pid=<?php wpl_esc::attr($item_id); ?>&image="+encodeURIComponent(gallery)+"&enabled="+status+"&kind=<?php wpl_esc::attr($this->kind); ?>&_wpnonce=<?php wpl_esc::attr($nonce); ?>",
		success: function (data) {
			if (status == 0)
				wplj("#active_image_tag_" + id).html('<i class="action-btn icon-disabled" title="<?php wpl_esc::attr_t('Disabled'); ?>"></i>');
			else
				wplj("#active_image_tag_" + id).html('<i class="action-btn icon-enabled" title="<?php wpl_esc::attr_t('Enabled'); ?>"></i>');
		}
	});
}

function wpl_gallery_select_tab(tab_id, container_id)
{
    wplj('#gallery-tabs-wp-container li').removeClass('active');
    wplj('#gallery-tabs-wp-container li#'+tab_id).addClass('active');
	
    wplj('.wpl_gallery_method_container').hide();
    wplj('#'+container_id).show();
}

function add_external_image()
{
    wplj('#wpl_gallery_external_cnt').show();
}

function wpl_gallery_external_save()
{
    var external_link = encodeURIComponent(wplj('#gallery_external_link').val());

	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: "wpl_format=b:listing:gallery&wpl_function=save_external_images&pid=<?php wpl_esc::attr($item_id); ?>&kind=<?php wpl_esc::attr($this->kind); ?>&links="+external_link+"&_wpnonce=<?php wpl_esc::attr($nonce); ?>",
		success: function (data) {
			var url = '<?php wpl_esc::url(wpl_global::add_qs_var('pid', $item_id, wpl_global::get_full_url())); ?>';
			window.location = url;
		}
	});
}
</script>
<?php
    $done_this = true;
}
elseif($type == 'attachments' and !$done_this)
{
    $extentions = explode(',', $options['ext_file']);
	$ext_str = '';
    foreach($extentions as $extention) $ext_str .= $extention . '|';

    // remove last |
    $ext_str = substr($ext_str, 0, -1);
    $ext_str = rtrim($ext_str, ';');
    $max_size = $options['file_size'];

    // Load Handlebars Templates
    wpl_esc::e(wpl_global::load_js_template('dbst-wizard-attachment'));
?>
<div class="attach-btn-wp">
	<div class="wpl-button button-1 button-upload">
		<span><?php wpl_esc::html_t('Select files'); ?></span>
		<!-- The file input field used as target for the file upload widget -->
		<input id="attachment_upload" type="file" name="files[]" multiple>
	</div>
	<div class="field-desc">
		<?php wpl_esc::html_t('To attach files click on the "Select files" button.'); ?>
	</div>
</div>

<!-- The global progress bar -->
<div id="progress_att">
	<div id="progress" class="progress progress-success progress-striped">
		<div class="bar"></div>
	</div>
</div>

<div class="error_uploaded_message" id="error_ajax_att">
</div>

<!-- The container for the uploaded files -->
<div id="attaches" class="attachment-wp wpl_files_container">
    <div class="wpl-mass-actions">
        <button class="wpl-button button-1" onclick="wpl_attachment_delete_all();"><?php wpl_esc::html_t('Delete all attachments'); ?></button>
    </div>
	<ul class="ui-sortable" id="ajax_att_sortable">
	<?php
    // get uploaded attachments and show them
    $att_items = wpl_items::get_items($item_id, 'attachment', $this->kind, '', '');
    
    // Get blog ID of property
    $blog_id = wpl_property::get_blog_id($item_id);
    
    // Media Confirm Status
    $media_confirm = wpl_global::get_setting('listing_media_confirm');
    
    $att_folder = wpl_items::get_folder($item_id, $this->kind, $blog_id);
    $attachment_categories = wpl_items::get_item_categories('attachment', $this->kind);
    $max_index_att = 0;
    
    foreach ($att_items as $attachment)
    {
        $attachment->index = intval($attachment->index);
        if($max_index_att < $attachment->index) $max_index_att = $attachment->index;
        ?>
        <li class="ui-state-default" id="ajax_attachment<?php wpl_esc::attr($attachment->index); ?>">
            <input type="hidden" class="att_name" value="<?php wpl_esc::attr($attachment->item_name); ?>"/>

            <div class="image-box-wp">
                <div class="icon-wp">
                    <div class="wpl-attach-icon wpl-att-<?php wpl_esc::attr(pathinfo($attachment->item_name, PATHINFO_EXTENSION)); ?>"></div>
                </div>
                <div class="info-wp">
                    <div class="row">
                        <label for=""><?php wpl_esc::html_t('Attachment Title') ?>:</label>
                        <input type="text" class="att_title" value="<?php wpl_esc::attr($attachment->item_extra1); ?>" onchange="ajax_attachment_title_update('<?php wpl_esc::js($attachment->item_name); ?>', this.value);" size="20" />
                    </div>
                    <div class="row">
                        <label for=""><?php wpl_esc::html_t('Attachment Description') ?>:</label>
                        <input type="text" class="att_desc" value="<?php wpl_esc::attr($attachment->item_extra2); ?>" onchange="ajax_attachment_desc_update('<?php wpl_esc::js($attachment->item_name); ?>', this.value);" size="50" />
                    </div>
                    <div class="row">
                        <label for=""><?php wpl_esc::html_t('Attachment Category') ?>:</label>
                        <select name="att_cat" class="att_cat" onchange="ajax_attachment_cat_update('<?php wpl_esc::js($attachment->item_name); ?>', this.value);">
                            <?php
                            foreach ($attachment_categories as $att_cat)
                            {
                                wpl_esc::e(' <option value="' . wpl_esc::return_attr($att_cat->category_name) . '"');
                                if($attachment->item_cat == $att_cat->category_name) wpl_esc::e('selected="selected"');
								wpl_esc::e('>' . wpl_esc::return_html($att_cat->category_name) . '</option>');
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="actions-wp">
                    <div class="action-gal-btn ajax_attachment_move_icon">
                        <i class="action-btn icon-move wpl_actions_btn"></i>
                    </div>
                    <div class="action-gal-btn ajax_gallery_middle_td " onclick="ajax_attachment_delete('<?php wpl_esc::js($attachment->item_name); ?>','ajax_attachment<?php wpl_esc::js($attachment->index); ?>');" >
                        <i class="action-btn icon-recycle"></i>
                    </div>

                    <?php
                        if($attachment->enabled and $media_confirm) wpl_esc::e('<div class="action-gal-btn" id="active_attachment_tag_' . $attachment->index . '" onclick="wpl_attachment_enabled(\'' . $attachment->item_name . '\',' . $attachment->index . ');"><i class="action-btn icon-enabled wpl_actions_btn wpl_show" title="'.wpl_esc::return_html_t('Enabled').'"></i></div>');
                        elseif($media_confirm) wpl_esc::e('<div class="action-gal-btn" id="active_attachment_tag_' . $attachment->index . '" onclick="wpl_attachment_enabled(\'' . $attachment->item_name . '\',' . $attachment->index . ');"><i class="action-btn icon-disabled wpl_actions_btn  wpl_show" title="'.wpl_esc::return_html_t('Disabled').'"></i></div>');
                    ?>

                    <input type="hidden" id="enabled_attachment_field_<?php wpl_esc::attr($attachment->index); ?>" value="<?php wpl_esc::attr($attachment->enabled); ?>"/>
                </div>
            </div>
        </li>
		<?php
    }
    ?>
	</ul>
	<?php
	$attachment_categories_html = '';
	foreach ($attachment_categories as $att_cat)
	{
		$attachment_categories_html .= ' <option value="' . $att_cat->category_name . '">' . wpl_esc::return_html_t($att_cat->category_name) . '</option>';
	}
	?>
</div>
<script type="text/javascript">
jQuery(document).ready(function()
{
	wpl_att_sortable();
});

function wpl_att_sortable(destroy)
{
    if(typeof destroy == 'undefined') destroy = false;
    if(destroy) wplj("#ajax_att_sortable").sortable('destroy');
    
    wplj("#ajax_att_sortable").sortable(
	{
        placeholder: "wpl-sortable-placeholder",
        opacity: 0.7,
        forcePlaceholderSize: true,
        cursor: "move",
        axis: "y",
		stop: function (event, ui)
		{
			sort_str = "";
			wplj("#ajax_att_sortable .att_name").each(function (ind, elm)
			{
				sort_str += elm.value + ",";
			});

			wplj.post("<?php wpl_esc::current_url(); ?>", "&wpl_format=b:listing:attachments&wpl_function=sort_attachments&pid=<?php wpl_esc::attr($item_id); ?>&order="+encodeURIComponent(sort_str)+"&kind=<?php wpl_esc::attr($this->kind); ?>"+"&_wpnonce=<?php wpl_esc::attr($nonce); ?>", function (data) {});
		}
	});
}

var att_counter = parseInt(<?php wpl_esc::numeric($max_index_att) ?>) + 1;
jQuery(document).ready(function()
{
	var url = '<?php wpl_esc::current_url(); ?>&wpl_format=b:listing:attachments&wpl_function=upload&pid=<?php wpl_esc::attr($item_id); ?>&kind=<?php wpl_esc::attr($this->kind); ?>&type=attachment&_wpnonce=<?php wpl_esc::attr($nonce); ?>';

    wplj('#attachment_upload').fileupload(
    {
        url: url,
        acceptFileTypes: /(<?php wpl_esc::e($ext_str); ?>)$/i,
        dataType: 'json',
        maxFileSize: <?php wpl_esc::numeric($max_size * 1000); ?>,
        sequentialUploads: true,
        done: function (e, data)
        {
            wplj(data.result.files).each(function (index, file)
            {
                if (file.error !== undefined)
                {
                    wplj('<p/>').text(file.error).appendTo('#attaches');
                }
                else
                {
                    var hbSource   = wplj("#dbst-wizard-attachment").html();
                    var hbTemplate = Handlebars.compile(hbSource);
                    var hbHTML     = hbTemplate({
                        att_counter: att_counter,
                        fileName: file.name,
                        enabled_title: "<?php wpl_esc::attr_t('Enabled'); ?>",
                        subFileName: file.name.substr((file.name.lastIndexOf('.') + 1)),
                        lblTitle: "<?php wpl_esc::attr_t('Attachment Title'); ?>",
                        lblDesc: "<?php wpl_esc::attr_t('Attachment Description'); ?>",
                        lblCat: "<?php wpl_esc::attr_t('Attachment Category'); ?>",
                        attachCat: "<?php wpl_esc::e(addslashes($attachment_categories_html)); ?>",
                        mediaConfirm: <?php wpl_esc::e($media_confirm ? 'true' : 'false'); ?>
                    });

                    wplj(hbHTML).hide().appendTo('#ajax_att_sortable').slideDown();

                    att_counter++;
                }

                rta.internal.initChosen();

            }).promise().done(function()
            {
                wplj('#progress_att').hide();
                wpl_att_sortable(true);
            });
        },
        progressall: function (e, data)
        {
            wplj("#progress_att").show('fast');
            var progress = parseInt(data.loaded / data.total * 100, 10);

            wplj('#progress_att #progress .bar').css('width', progress + '%');

            wplj("#error_ajax_att").html("");
            wplj("#error_ajax_att").hide('slow');
        },
        processfail: function (e, data)
        {
            wplj("#progress_att").hide('slow');
            wplj("#error_ajax_att").html("<span color='red'><?php wpl_esc::html_t('Error occured') ?> : " + data.files[data.index].name + " " + data.files[data.index].error + "</span>");
            wplj("#error_ajax_att").show('slow');
        }
    });
});

function ajax_attachment_title_update(attachment, value)
{
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: "wpl_format=b:listing:attachments&wpl_function=title_update&pid=<?php wpl_esc::attr($item_id); ?>&attachment="+encodeURIComponent(attachment)+"&value="+value+"&kind=<?php wpl_esc::attr($this->kind); ?>&_wpnonce=<?php wpl_esc::attr($nonce); ?>",
	});
}

function ajax_attachment_desc_update(attachment, value)
{
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: "wpl_format=b:listing:attachments&wpl_function=desc_update&pid=<?php wpl_esc::attr($item_id); ?>&attachment="+encodeURIComponent(attachment)+"&value="+value+"&kind=<?php wpl_esc::attr($this->kind); ?>&_wpnonce=<?php wpl_esc::attr($nonce); ?>",
	});
}

function ajax_attachment_cat_update(attachment, value)
{
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: "wpl_format=b:listing:attachments&wpl_function=cat_update&pid=<?php wpl_esc::attr($item_id); ?>&attachment="+encodeURIComponent(attachment)+"&value="+value+"&kind=<?php wpl_esc::attr($this->kind); ?>&_wpnonce=<?php wpl_esc::attr($nonce); ?>",
	});
}

function ajax_attachment_delete(attachment, id)
{
	if(confirm('<?php wpl_esc::js_t('Are you sure?') ?>'))
	{
		wplj.ajax({
			type: 'POST',
			dataType: 'HTML',
			url: '<?php wpl_esc::current_url(); ?>',
			data: "wpl_format=b:listing:attachments&wpl_function=delete_attachment&pid=<?php wpl_esc::attr($item_id); ?>&attachment="+encodeURIComponent(attachment)+"&kind=<?php wpl_esc::attr($this->kind); ?>&_wpnonce=<?php wpl_esc::attr($nonce); ?>",
			success: function (data) {
				wplj("#" + id).slideUp(function(){
					wplj(this).remove();
				});
			}
		});
	}
}

function wpl_attachment_delete_all()
{
    if(!confirm("<?php wpl_esc::attr_t('Are you sure?'); ?>")) return;

	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: "wpl_format=b:listing:attachments&wpl_function=delete_all_attachments&pid=<?php wpl_esc::attr($item_id); ?>&kind=<?php wpl_esc::attr($this->kind); ?>&_wpnonce=<?php wpl_esc::attr($nonce); ?>",
		success: function (data) {
			wplj("#ajax_att_sortable li").slideUp(400, function(){
				wplj(this).remove();
			});
		}
	});
}

function wpl_attachment_enabled(attachment, id)
{
	var status = Math.abs(wplj("#enabled_attachment_field_" + id).val() - 1);
	wplj("#enabled_attachment_field_" + id).val(status);
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: "wpl_format=b:listing:attachments&wpl_function=change_status&pid=<?php wpl_esc::attr($item_id); ?>&attachment="+encodeURIComponent(attachment)+"&enabled="+status+"&kind=<?php wpl_esc::attr($this->kind); ?>&_wpnonce=<?php wpl_esc::attr($nonce); ?>",
		success: function (data) {
			if (status == 0) wplj("#active_attachment_tag_" + id).html('<i class="action-btn icon-disabled wpl_actions_btn wpl_show" title="<?php wpl_esc::attr_t('Disabled'); ?>"></i>');
			else wplj("#active_attachment_tag_" + id).html('<i class="action-btn icon-enabled wpl_actions_btn wpl_show" title="<?php wpl_esc::attr_t('Enabled'); ?>"></i>');
		}
	});
}
</script>
<?php
    $done_this = true;
}
elseif($type == 'addon_video' and !$done_this)
{
    $ext_str = trim(str_replace(',', '|', $options['ext_file'] ?? ''), '|,; ');
    $max_size = $options['file_size'];

    $vid_embed_items = wpl_items::get_items($item_id, 'video', $this->kind, 'video_embed', 1);
    $vid_embed_count = 1;

	// Load Handlebars Templates
	wpl_esc::e(wpl_global::load_js_template('dbst-wizard-videos'));
?>

<div class="video-tabs-wp" id="video-tabs">
	<ul>
		<li class="active"><a id="embed-tab" href="#embed" onclick="video_select_tab(0); return false;"><?php wpl_esc::html_t('Embed code'); ?></a></li>
		<?php if(wpl_settings::get('video_uploader')): ?>
		<li><a id="uploader-tab" href="#uploader" onclick="video_select_tab(1); return false;"><?php wpl_esc::html_t('Video uploader'); ?></a></li>
		<?php endif; ?>
	</ul>
</div>

<div class="video-content-wp">

<div class="content-wp" id="embed">
	<button class="wpl-button button-1" onclick="add_embed_video();"><?php wpl_esc::html_t('Add video') ?></button>

	<?php foreach ($vid_embed_items as $vid_embed_item): ?>
    <div class="video-embed-wp" id="video-embed-<?php wpl_esc::numeric($vid_embed_count); ?>">
        <div class="row">
            <label id="title_label" for="embed_vid_title<?php wpl_esc::numeric($vid_embed_count); ?>"><?php wpl_esc::html_t('Video title'); ?></label>
            <input type="text" name="embed_vid_title<?php wpl_esc::numeric($vid_embed_count); ?>" id="embed_vid_title<?php wpl_esc::numeric($vid_embed_count); ?>" value="<?php wpl_esc::attr($vid_embed_item->item_name); ?>" onblur="video_embed_save(<?php wpl_esc::numeric($vid_embed_count); ?>);" />
        </div>
        <div class="row">
            <label id="desc_label" for="embed_vid_desc<?php wpl_esc::numeric($vid_embed_count); ?>"><?php wpl_esc::html_t('Video description'); ?></label>
            <input type="text" name="embed_vid_desc<?php wpl_esc::numeric($vid_embed_count); ?>" id="embed_vid_desc<?php wpl_esc::numeric($vid_embed_count); ?>" value="<?php wpl_esc::attr($vid_embed_item->item_extra1); ?>" onblur="video_embed_save(<?php wpl_esc::numeric($vid_embed_count); ?>);" />
        </div>
        <div class="row">
            <label id="thumb_label" for="embed_vid_thumb<?php wpl_esc::numeric($vid_embed_count); ?>"><?php wpl_esc::html_t('Url to Video Thumbnail'); ?></label>
            <input type="text" name="embed_vid_thumb<?php wpl_esc::numeric($vid_embed_count); ?>" id="embed_vid_thumb<?php wpl_esc::numeric($vid_embed_count); ?>" value="<?php wpl_esc::attr($vid_embed_item->item_extra3); ?>" onblur="video_embed_save(<?php wpl_esc::numeric($vid_embed_count); ?>);" />
        </div>
        <div class="row">
            <a class="button_help" href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/792/28/how-to-get-embed-code-from-youtube-website" target="_blank">
                <?php wpl_esc::html_t('Need help?') ?>
            </a>
            <label id="embed_label" for="embed_vid_code<?php wpl_esc::numeric($vid_embed_count); ?>"><?php wpl_esc::html_t('Video embed code'); ?></label>
            <textarea name="embed_vid_code<?php wpl_esc::numeric($vid_embed_count); ?>" rows="5" cols="50" id="embed_vid_code<?php wpl_esc::numeric($vid_embed_count); ?>" onblur="video_embed_save(<?php wpl_esc::numeric($vid_embed_count); ?>);" placeholder="<?php wpl_esc::attr(sprintf(wpl_esc::return_html_t('Sample: %s'), "<iframe width='560' height='350' src='http://youtube.com' frameborder='0' allowfullscreen></iframe>")); ?>"><?php wpl_esc::html($vid_embed_item->item_extra2); ?></textarea>
        </div>
        <div class="actions-wp"><a onclick="embed_video_delete('<?php wpl_esc::numeric($vid_embed_count); ?>');"><i class="action-btn icon-recycle"></i></a></div>
        <input type="hidden" id="vid_emb<?php wpl_esc::numeric($vid_embed_count); ?>" value="<?php wpl_esc::attr($vid_embed_item->id); ?>" />
    </div>
    <?php $vid_embed_count++; endforeach; ?>
</div>

<script type="text/javascript">
var vid_embed_count = <?php wpl_esc::numeric($vid_embed_count); ?>;
function add_embed_video()
{
	var embedVideo = rta.template.bind({
		count: vid_embed_count,
		title: "<?php wpl_esc::attr_t('Video title'); ?>",
		desc: "<?php wpl_esc::attr_t('Video description'); ?>",
		thumb: "<?php wpl_esc::attr_t('Url to Video Thumbnail'); ?>",
		embedCode: "<?php wpl_esc::attr_t('Video embed code'); ?>",
		item_name: '',
		item_extra1: '',
		item_extra2: '',
        item_extra3: '',
        help_link: "<?php wpl_esc::attr_t('Need help?') ?>",
        placeholder: "<?php wpl_esc::e(sprintf(wpl_esc::return_html_t('Sample: %s'), "<iframe width='560' height='350' src='http://youtube.com' frameborder='0' allowfullscreen></iframe>")); ?>",
		id: '-1'
	}, 'add-listing-video-embed');
    
	wplj(embedVideo).appendTo('#embed');
	vid_embed_count++;
}

function video_embed_save(id)
{
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: "wpl_format=b:listing:videos&wpl_function=embed_video&pid=<?php wpl_esc::attr($item_id); ?>&kind=<?php wpl_esc::attr($this->kind); ?>&item_id="+wplj("#vid_emb"+id).val()+"&title="+wplj("#embed_vid_title"+id).val()+"&thumbnail="+encodeURIComponent(wplj("#embed_vid_thumb"+id).val())+"&desc="+wplj("#embed_vid_desc"+id).val()+"&_wpnonce=<?php wpl_esc::attr($nonce); ?>&embedcode="+encodeURIComponent(wplj("#embed_vid_code"+id).val()),
		success: function (data) {
			if(wplj("#vid_emb" + id).val() == -1) wplj("#vid_emb" + id).val(data);
		}
	});
}

function embed_video_delete(id)
{
	if (confirm("<?php wpl_esc::attr_t('Are you sure?'); ?>"))
    {
		wplj.ajax({
			type: 'POST',
			dataType: 'HTML',
			url: '<?php wpl_esc::current_url(); ?>',
			data: "wpl_format=b:listing:videos&wpl_function=del_embed_video&pid=<?php wpl_esc::attr($item_id); ?>&kind=<?php wpl_esc::attr($this->kind); ?>&item_id="+wplj("#vid_emb"+id).val()+"&_wpnonce=<?php wpl_esc::attr($nonce); ?>",
			success: function (data) {
				wplj("#video-embed-" + id).fadeOut(500, function ()
				{
					wplj(this).remove();
				});
			}
		});
	}
}

function video_select_tab(id)
{
	wplj('#video-tabs').find('li').removeClass('active').eq(id).addClass('active');
	var _this = wplj('#video-tabs').find('li:eq(' + id + ') > a');
	wplj('.video-content-wp').find('> div').hide().filter(_this.attr('href')).fadeIn(600);
}
</script>

<?php
if(wpl_settings::get('video_uploader'))
{
?>
<div class="content-wp wpl-util-hidden" id="uploader">
	<div class="upload-btn-wp">
		<div class="wpl-button button-1 button-upload">
			<span><?php wpl_esc::html_t('Select Files'); ?></span>
			<input id="video_upload" type="file" name="files[]" multiple="multiple"/>
		</div>
		<div class="field-desc">
			<?php wpl_esc::html_t('Choose videos by clicking on "Select Files."'); ?>
		</div>
	</div>
	<!-- The global progress bar -->
	<div id="progress_vid">
		<div id="progress" class="progress progress-success progress-striped">
			<div class="bar"></div>
		</div>
	</div>
	<div class="error_uploaded_message" id="error_ajax_vid">
	</div>
	<!-- The container for the uploaded files -->
	<div id="video" class="video-list-wp wpl_files_container">
		<ul class="ui-sortable" id="ajax_vid_sortable">
			<?php
			// get uploaded videos and show them
			$vid_items = wpl_items::get_items($item_id, 'video', $this->kind, 'video', '');
            
            // Get blog ID of property
            $blog_id = wpl_property::get_blog_id($item_id);
            
            // Media Confirm Status
            $media_confirm = wpl_global::get_setting('listing_media_confirm');
        
			$vid_folder = wpl_items::get_folder($item_id, $this->kind, $blog_id);
			$video_categories = wpl_items::get_item_categories('addon_video', $this->kind);
			$max_index_vid = 0;

			foreach ($vid_items as $video)
			{
				$video->index = intval($video->index);
				if($max_index_vid < $video->index)
					$max_index_vid = $video->index;
				?>
				<li class="ui-state-default" id="ajax_video<?php wpl_esc::attr($video->index); ?>">
					<input type="hidden" class="vid_name" value="<?php wpl_esc::attr($video->item_name); ?>"/>

					<div class="image-box-wp">
						<div class="info-wp">
							<div class="row">
								<label for=""><?php wpl_esc::html_t('Video Title'); ?>:</label>
								<input type="text" class="vid_title" value="<?php wpl_esc::attr($video->item_extra1); ?>" onchange="ajax_video_title_update('<?php wpl_esc::js($video->item_name); ?>', this.value);" size="20" />
							</div>
							<div class="row">
								<label for=""><?php wpl_esc::html_t('Video Description'); ?>:</label>
								<input type="text" class="vid_desc" value="<?php wpl_esc::attr($video->item_extra2); ?>" onchange="ajax_video_desc_update('<?php wpl_esc::js($video->item_name); ?>', this.value);" size="50" />
							</div>
							<div class="row">
								<label for=""><?php wpl_esc::html_t('Video Category'); ?>:</label>
								<select name="vid_cat" class="vid_cat" onchange="ajax_video_cat_update('<?php wpl_esc::js($video->item_name); ?>', this.value);">
									<?php
									foreach ($video_categories as $vid_cat)
									{
										wpl_esc::e(' <option value="' . $vid_cat->category_name . '"');
										if($video->item_cat == $vid_cat->category_name)
											wpl_esc::e('selected="selected"');
										wpl_esc::e('>' . $vid_cat->category_name . '</option>');
									}
									?>
								</select>
							</div>
                            <div class="row">
                                <label for=""><?php wpl_esc::html_t('Url to Video Thumbnail'); ?></label>
                                <input type="text" class="vid_thumb" value="<?php wpl_esc::attr($video->item_extra3); ?>" onchange="ajax_video_thumb_update('<?php wpl_esc::js($video->item_name); ?>', this.value);" size="50" />
                            </div>
						</div>
						<div class="actions-wp">
							<div class="action-gal-btn">
								<i class="action-btn icon-move"></i>
							</div>
							<div class="action-gal-btn ajax_gallery_middle_td"
								 onclick="ajax_video_delete('<?php wpl_esc::js($video->item_name); ?>', 'ajax_video<?php wpl_esc::js($video->index); ?>');">
								<i class="action-btn icon-recycle"></i>
							</div>
							<?php
                                if($video->enabled and $media_confirm) wpl_esc::e('<div class="action-gal-btn" id="active_video_tag_' . $video->index . '" onclick="wpl_video_enabled(\'' . wpl_esc::return_js($video->item_name) . '\',' . $video->index . ');"><i class="action-btn icon-enabled"></i></div>');
                                elseif($media_confirm) wpl_esc::e('<div class="action-gal-btn" id="active_video_tag_' . $video->index . '" onclick="wpl_video_enabled(\'' . wpl_esc::return_js($video->item_name) . '\',' . $video->index . ');"><i class="action-btn icon-disabled"></i></div>');
							?>
							<input type="hidden" id="enabled_video_field_<?php wpl_esc::attr($video->index); ?>" value="<?php wpl_esc::attr($video->enabled); ?>" />
						</div>
					</div>
				</li>
			<?php
			}
			?>
		</ul>
		<?php
		$video_categories_html = '';
		foreach ($video_categories as $vid_cat)
		{
			$video_categories_html .= ' <option value="' . $vid_cat->category_name . '">' . wpl_esc::return_html_t($vid_cat->category_name) . '</option>';
		}
		?>
	</div>
</div>

<script type="text/javascript">
jQuery(document).ready(function()
{
    wpl_vid_sortable();
});

function wpl_vid_sortable(destroy)
{
    if(typeof destroy == 'undefined') destroy = false;
    if(destroy) wplj("#ajax_vid_sortable").sortable('destroy');
    
    wplj("#ajax_vid_sortable").sortable(
	{
		placeholder: "wpl-sortable-placeholder",
		opacity: 0.7,
		forcePlaceholderSize: true,
		cursor: "move",
		axis: "y",
		stop: function (event, ui)
		{
			sort_str = "";
			wplj("#ajax_vid_sortable .vid_name").each(function (ind, elm)
			{
				sort_str += elm.value + ",";
			});
	
			wplj.post("<?php wpl_esc::current_url(); ?>", "&wpl_format=b:listing:videos&wpl_function=sort_videos&pid=<?php wpl_esc::attr($item_id); ?>&order="+encodeURIComponent(sort_str)+"&kind=<?php wpl_esc::attr($this->kind); ?>&_wpnonce=<?php wpl_esc::attr($nonce); ?>", function (data)
			{
			});
		}
	});
}

var vid_counter = parseInt(<?php wpl_esc::numeric($max_index_vid); ?>) + 1;
jQuery(document).ready(function()
{
	var url = '<?php wpl_esc::current_url(); ?>&wpl_format=b:listing:videos&wpl_function=upload&pid=<?php wpl_esc::attr($item_id); ?>&kind=<?php wpl_esc::attr($this->kind); ?>&type=video&_wpnonce=<?php wpl_esc::attr($nonce); ?>';

    wplj('#video_upload').fileupload(
    {
        url: url,
        acceptFileTypes: /(<?php wpl_esc::e($ext_str); ?>)$/i,
        dataType: 'json',
        maxFileSize: <?php wpl_esc::numeric($max_size * 1000); ?>,
        sequentialUploads: true,
        done: function (e, data)
        {
            wplj(data.result.files).each(function (index, file)
            {
                if(file.error !== undefined)
                {
                    wplj('<p/>').text(file.error).appendTo('#video');
                }
                else
                {
					var hbSource   = wplj("#dbst-wizard-videos").html();
					var hbTemplate = Handlebars.compile(hbSource);
					var hbHTML     = hbTemplate({
						vid_counter: vid_counter,
						lblTitle: "<?php wpl_esc::attr_t('Video Title'); ?>",
						lblDesc: "<?php wpl_esc::attr_t('Video Description'); ?>",
						lblCat: "<?php wpl_esc::attr_t('Video Category'); ?>",
						lblThumb: "<?php wpl_esc::attr_t('Url to Video Thumbnail'); ?>",
                        name: file.name,
						select: "<?php wpl_esc::e(addslashes($video_categories_html)); ?>",
                        mediaConfirm: <?php wpl_esc::e($media_confirm ? 'true' : 'false'); ?>
					});

					wplj(hbHTML).hide().appendTo('#ajax_vid_sortable').slideDown();
                    vid_counter++;
                }
            }).promise().done(function ()
            {
				wplj('#progress_vid').hide();
                wpl_vid_sortable();
			});
        },
        progressall: function (e, data)
        {
            wplj("#progress_vid").show('fast');
            var progress = parseInt(data.loaded / data.total * 100, 10);
            wplj('#progress_vid #progress .bar').css('width', progress + '%');
            wplj("#error_ajax_vid").html("");
            wplj("#error_ajax_vid").hide('slow');
        },
        processfail: function (e, data)
        {
            wplj("#progress_vid").hide('slow');
            wplj("#error_ajax_vid").html("<span color='red'><?php wpl_esc::attr_t('Error occured'); ?> : " + data.files[data.index].name + " " + data.files[data.index].error + "</span>");
            wplj("#error_ajax_vid").show('slow');
        }
    }); // End of FileUpload
});

function ajax_video_title_update(video, value)
{
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: "wpl_format=b:listing:videos&wpl_function=title_update&pid=<?php wpl_esc::attr($item_id); ?>&video="+video+"&value="+value+"&kind=<?php wpl_esc::attr($this->kind); ?>&_wpnonce=<?php wpl_esc::attr($nonce); ?>",
	});
}

function ajax_video_desc_update(video, value)
{
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: "wpl_format=b:listing:videos&wpl_function=desc_update&pid=<?php wpl_esc::attr($item_id); ?>&video="+video+"&value="+value+"&kind=<?php wpl_esc::attr($this->kind); ?>&_wpnonce=<?php wpl_esc::attr($nonce); ?>",
	});
}

function ajax_video_cat_update(video, value)
{
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: "wpl_format=b:listing:videos&wpl_function=cat_update&pid=<?php wpl_esc::attr($item_id); ?>&video="+video+"&value="+value+"&kind=<?php wpl_esc::attr($this->kind); ?>&_wpnonce=<?php wpl_esc::attr($nonce); ?>",
	});
}

function ajax_video_thumb_update(video, value)
{
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: "wpl_format=b:listing:videos&wpl_function=thumb_update&pid=<?php wpl_esc::attr($item_id); ?>&video="+video+"&value="+value+"&kind=<?php wpl_esc::attr($this->kind); ?>&_wpnonce=<?php wpl_esc::attr($nonce); ?>",
	});
}

function ajax_video_delete(video, id)
{
	if (confirm("<?php wpl_esc::attr_t('Are you sure?'); ?>"))
	{
		wplj.ajax({
			type: 'POST',
			dataType: 'HTML',
			url: '<?php wpl_esc::current_url(); ?>',
			data: "wpl_format=b:listing:videos&wpl_function=delete_video&pid=<?php wpl_esc::attr($item_id); ?>&video="+encodeURIComponent(video)+"&kind=<?php wpl_esc::attr($this->kind); ?>&_wpnonce=<?php wpl_esc::attr($nonce); ?>",
			success: function (data) {
				wplj("#" + id).slideUp(function ()
				{
					wplj(this).remove();
				});
			}
		});
	}
}

function wpl_video_enabled(video, id)
{
	var status = Math.abs(wplj("#enabled_video_field_" + id).val() - 1);
	wplj("#enabled_video_field_" + id).val(status);

	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: "wpl_format=b:listing:videos&wpl_function=change_status&pid=<?php wpl_esc::attr($item_id); ?>&video="+encodeURIComponent(video)+"&enabled="+status+"&kind=<?php wpl_esc::attr($this->kind); ?>&_wpnonce=<?php wpl_esc::attr($nonce); ?>",
		success: function (data) {
			if (status == 0) wplj("#active_video_tag_" + id).html('<i class="action-btn icon-disabled"></i>');
			else wplj("#active_video_tag_" + id).html('<i class="action-btn icon-enabled"></i>');
		}
	});
}
</script>
<?php
}
?>
</div>

<?php
    $done_this = true;
}