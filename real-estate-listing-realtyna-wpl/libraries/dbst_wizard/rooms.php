<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'rooms' and !$done_this)
{
    _wpl_import('libraries.room_types');

    $room_items = wpl_items::get_items($item_id, 'rooms', $this->kind);
    $all_room_type = wpl_room_types::get_room_types();
?>
<script type="text/javascript">
function wpl_delete_room(id)
{
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: "wpl_format=b:listing:rooms&wpl_function=delete_room&pid=<?php wpl_esc::attr($item_id); ?>&kind=<?php wpl_esc::attr($this->kind); ?>&item_id=" + id + "&_wpnonce=<?php wpl_esc::attr($nonce); ?>",
		success: function (data) {
			wplj(".room_" + id).hide(500).remove();
		}
	});
}

function wpl_save_room()
{
    var yroom = wplj("#yroom<?php wpl_esc::numeric($field->id); ?>").val();
    var xroom = wplj("#xroom<?php wpl_esc::numeric($field->id); ?>").val();
    var room_type_id = wplj("#room_types<?php wpl_esc::numeric($field->id); ?>").val();
    var room_name = wplj("#room_types<?php wpl_esc::numeric($field->id); ?> option:selected").text();

	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: "wpl_format=b:listing:rooms&wpl_function=save_room&pid=<?php wpl_esc::attr($item_id); ?>&kind=<?php wpl_esc::attr($this->kind); ?>" + "&x_param=" + xroom + "&y_param=" + yroom + "&room_type_id=" + room_type_id + "&room_name=" + room_name + "&_wpnonce=<?php wpl_esc::attr($nonce); ?>",
		success: function (data) {
			var added_id = data.data;
			var html = '';

			html += '<div class=" room_' + added_id + '">';
			html += '<span class="action-btn icon-recycle wpl_show cursor" onclick="wpl_delete_room(' + added_id + ');"></span>';
			html += '<span class="room-preview"><span>' + room_name + '</span><i>' + xroom + 'x' + yroom + '</i></span>';
			html += '</div>';

			wplj("#xroom<?php wpl_esc::numeric($field->id); ?>").val('');
			wplj("#yroom<?php wpl_esc::numeric($field->id); ?>").val('');
			wplj(html).appendTo('#room_list<?php wpl_esc::numeric($field->id); ?>');
		}
	});
}
</script>
<div class="new-rooms-wp" id="room_add">
    <select id="room_types<?php wpl_esc::numeric($field->id); ?>">
        <?php foreach($all_room_type as $room_type): ?>
        <option value="<?php wpl_esc::attr($room_type['id']); ?>"><?php wpl_esc::html_t($room_type['name']); ?></option>
        <?php endforeach; ?>
    </select>
    <input type="text" id="xroom<?php wpl_esc::numeric($field->id); ?>" name="xroom<?php wpl_esc::numeric($field->id); ?>" placeholder="<?php wpl_esc::attr_t('Width'); ?>" />
    <input type="text" id="yroom<?php wpl_esc::numeric($field->id); ?>" name="yroom<?php wpl_esc::numeric($field->id); ?>" placeholder="<?php wpl_esc::attr_t('Length'); ?>" />
    <button class="wpl-button button-1" onclick="wpl_save_room();"><?php wpl_esc::html_t('Add room') ?></button>
</div>
<div class="rooms-list-wp" id="room_list<?php wpl_esc::numeric($field->id); ?>">
    <?php foreach($room_items as $room_item): ?>
        <div class="new-rooms room_<?php wpl_esc::attr($room_item->id); ?>">
            <span class="action-btn icon-recycle wpl_show cursor" onclick="wpl_delete_room(<?php wpl_esc::attr($room_item->id); ?>);"></span>
            <span class="room-preview"><?php wpl_esc::e('<span>'.wpl_esc::return_html_t($room_item->item_name).'</span>'.(($room_item->item_extra1 and $room_item->item_extra2) ? '<i>'.$room_item->item_extra1.'x'.$room_item->item_extra2.'</i>' : '')); ?></span>
        </div>
    <?php endforeach; ?>
</div>
<?php
    $done_this = true;
}