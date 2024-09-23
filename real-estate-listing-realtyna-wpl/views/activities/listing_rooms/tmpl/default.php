<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_properties = $params['wpl_properties'] ?? array();
$property_id = $wpl_properties['current']['data']['id'] ?? NULL;
$rooms = $wpl_properties['current']['items']['rooms'] ?? NULL;

if (!is_array($rooms) or !count($rooms)) return;

$rooms = wpl_items::render_rooms($rooms);
?>
<div class="wpl_rooms_container" id="wpl_rooms_container<?php wpl_esc::attr($property_id); ?>">
	<ul class="wpl_rooms_list_container clearfix">
		<?php foreach ($rooms as $room): ?>
			<li class="wpl_rooms_room wpl_rooms_type<?php wpl_esc::attr($room['category']); ?> room_<?php wpl_esc::attr($room['category']); ?>"
				id="wpl_rooms_room<?php wpl_esc::attr($room['id']); ?>">
				<?php
				wpl_esc::e('<div class="room_name">' . wpl_esc::return_html_t($room['name']) . '</div>');
				if (isset($room['size'])) wpl_esc::e('<div class="room_size">( ' . wpl_esc::return_html($room['size']) . ' )</div>');
				if (isset($room['extra4'])) wpl_esc::e('<div class="room_material">( ' . wpl_esc::return_html($room['extra3'] . ' / ' . $room['extra4']) . ' )</div>');
				?>
			</li>
		<?php endforeach; ?>
	</ul>
</div>