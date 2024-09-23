<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'parent' and !$done_this)
{
    /** converts property id to listing id **/
    if($value) $value = wpl_property::listing_id($value);
    
    $parent_kind = isset($options['parent_kind']) ? $options['parent_kind'] : 0;
    $replace = isset($options['replace']) ? $options['replace'] : 1;
    $parent_key = isset($options['key']) ? $options['key'] : 'parent';
    
    wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-autocomplete');
?>
<label for="wpl_c_<?php wpl_esc::numeric($field->id); ?>"><?php wpl_esc::html_t($label); ?><?php if(in_array($mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
<input type="text" class="wpl_c_<?php wpl_esc::attr($field->table_column); ?>" id="wpl_c_<?php wpl_esc::numeric($field->id); ?>" name="<?php wpl_esc::attr($field->table_column); ?>" value="<?php wpl_esc::attr($value); ?>" <?php wpl_esc::e((isset($options['readonly']) and $options['readonly'] == 1) ? 'disabled="disabled"' : ''); ?> />
<span id="wpl_listing_saved_span_<?php wpl_esc::numeric($field->id); ?>" class="wpl_listing_saved_span"></span>
<script type="text/javascript">
jQuery(document).ready(function()
{
    wplj("#wpl_c_<?php wpl_esc::numeric($field->id); ?>").autocomplete(
    {
        source: "<?php wpl_esc::url(wpl_global::add_qs_var('wpl_format', 'b:listing:ajax', wpl_global::get_full_url())); ?>&wpl_function=get_parents&kind=<?php wpl_esc::attr($parent_kind); ?>&exclude=<?php wpl_esc::attr($item_id); ?>&_wpnonce=<?php wpl_esc::attr($nonce); ?>",
        minLength: 1,
        select: function(event, ui)
        {
            wpl_parent_is_selected<?php wpl_esc::numeric($field->id); ?>(ui.item.id);
        },
        change: function(event, ui)
        {
            if(ui.item == null)
            {
                wplj("#wpl_c_<?php wpl_esc::numeric($field->id); ?>").val(0);
                ajax_save('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::js($field->table_column); ?>', 0, '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::numeric($field->id); ?>');
            }
        }
    });
});

function wpl_parent_is_selected<?php wpl_esc::numeric($field->id); ?>(parent_id)
{
    var url = "<?php wpl_esc::url(wpl_global::add_qs_var('pid', $item_id, wpl_global::get_full_url())); ?>";

	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: "wpl_format=b:listing:ajax&wpl_function=set_parent&item_id=<?php wpl_esc::attr($item_id); ?>&parent_id="+parent_id+"&kind=<?php wpl_esc::attr($this->kind); ?>&replace=<?php wpl_esc::attr($replace); ?>&key=<?php wpl_esc::attr($parent_key); ?>&_wpnonce=<?php wpl_esc::attr($nonce); ?>",
		success: function (data) {
			<?php if($replace): ?>window.location.href = url;<?php endif; ?>
		}
	});
}
</script>
<?php
	$done_this = true;
}
elseif($type == 'parents' and !$done_this)
{
    $parent_ids = explode(',', trim($value ?? '', ', '));
    
    $parent_kind = isset($options['parent_kind']) ? $options['parent_kind'] : 0;
    $parent_key = isset($options['key']) ? $options['key'] : 'parents';

    $parents = wpl_db::select(wpl_db::prepare("SELECT `id`, `field_313` FROM `#__wpl_properties` WHERE `kind`= %d AND `finalized` = 1 AND `confirmed` = 1 AND `expired` = 0 AND `deleted` = 0 ", $parent_kind), 'loadObjectList');
?>
<label for="wpl_c_<?php wpl_esc::numeric($field->id); ?>"><?php wpl_esc::html_t($label); ?><?php if(in_array($mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
<select class="wpl_c_<?php wpl_esc::attr($field->table_column); ?>" id="wpl_c_<?php wpl_esc::numeric($field->id); ?>" name="<?php wpl_esc::attr($field->table_column); ?>" value="<?php wpl_esc::attr($value); ?>" multiple="multiple" onchange="ajax_save('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::js($field->table_column); ?>', wplj(this).val(), '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::numeric($field->id); ?>');">
    <?php foreach($parents as $p): ?>
    <option value="<?php wpl_esc::attr($p->id); ?>" <?php wpl_esc::e( in_array($p->id, $parent_ids) ? 'selected="selected"' : ''); ?>><?php wpl_esc::html($p->field_313); ?></option>
    <?php endforeach; ?>
</select>
<span id="wpl_listing_saved_span_<?php wpl_esc::numeric($field->id); ?>" class="wpl_listing_saved_span"></span>
<?php
    $done_this = true;
}