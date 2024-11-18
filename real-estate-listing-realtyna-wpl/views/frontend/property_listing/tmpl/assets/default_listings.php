<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$description_column = 'field_308';
if(wpl_global::check_multilingual_status() and wpl_addon_pro::get_multiligual_status_by_column($description_column, $this->kind)) $description_column = wpl_addon_pro::get_column_lang_name($description_column, wpl_global::get_current_language(), false);

// Membership ID of current user
$current_user_membership_id = wpl_users::get_user_membership();

// Favorites
if(wpl_global::check_addon('PRO') and $this->favorite_btn) $favorites = wpl_addon_pro::favorite_get_pids();

foreach($this->wpl_properties as $key=>$property)
{
	if($key == 'current') continue;

	/** unset previous property **/
	unset($this->wpl_properties['current']);

	/** set current property **/
	$this->wpl_properties['current'] = $property;

	if(isset($property['materials']['bedrooms']['value']) and trim($property['materials']['bedrooms']['value'])) $room = sprintf('<div class="bedroom"><span class="value">%s</span><span class="name">%s</span></div>', wpl_esc::return_html($property['materials']['bedrooms']['value']), wpl_esc::return_html_t("Bedroom(s)"));
	elseif(isset($property['materials']['rooms']['value']) and trim($property['materials']['rooms']['value'])) $room = sprintf('<div class="room"><span class="value">%s</span><span class="name">%s</span></div>', wpl_esc::return_html($property['materials']['rooms']['value']), wpl_esc::return_html_t("Room(s)"));
	else $room = '';

	$bathroom = (isset($property['materials']['bathrooms']['value']) and trim($property['materials']['bathrooms']['value'])) ? sprintf('<div class="bathroom"><span class="value">%s</span><span class="name">%s</span></div>', wpl_esc::return_html($property['materials']['bathrooms']['value']), wpl_esc::return_html_t("Bathroom(s)")) : '';
	$parking = (isset($property['materials']['f_150']['values'][0]) and trim($property['materials']['f_150']['values'][0])) ? sprintf('<div class="parking"><span class="value">%s</span><span class="name">%s</span></div>', wpl_esc::return_html($property['materials']['f_150']['values'][0]), wpl_esc::return_html_t("Parking(s)")) : '';
	$pic_count = (isset($property['raw']['pic_numb']) and trim($property['raw']['pic_numb'])) ? sprintf('<div class="pic_count"><span class="value">%s</span><span class="name">%s</span></div>', wpl_esc::return_html($property['raw']['pic_numb']), wpl_esc::return_html_t("Picture(s)")) : '';

	$living_area = isset($property['materials']['living_area']['value']) ? explode(' ', $property['materials']['living_area']['value']) : (isset($property['materials']['lot_area']['value']) ? explode(' ', $property['materials']['lot_area']['value']): array());
	$living_area_count = count($living_area);

	$build_up_area = $living_area_count ? '<div class="built_up_area">'.wpl_esc::return_html(isset($living_area[0]) ? implode(' ', array_slice($living_area, 0, $living_area_count-1)) : '').'<span>'.wpl_esc::return_html($living_area[$living_area_count-1]).'</span></div>' : '';
	$property_price = isset($property['materials']['price']['value']) ? $property['materials']['price']['value'] : '&nbsp;';

	$cut_position = 399;
	$description = stripslashes(strip_tags($property['raw'][$description_column] ?? ""));
	if($description) {
		$cut_position = (trim($description) ? strrpos(substr($description, 0, 400), '.', -1) : 0);
	}

	$property_id = $property['data']['id'];

	$show_office_agent = wpl_global::check_addon('MLS') && $this->show_agent_name || $this->show_office_name;
?>
	<div class="wpl-column">
		<div class="wpl_prp_cont wpl_prp_cont_old
			<?php wpl_esc::attr((isset($this->property_css_class) and in_array($this->property_css_class, array('row_box', 'grid_box'))) ? $this->property_css_class : ''); ?>"
			 id="wpl_prp_cont<?php wpl_esc::e($property['data']['id']); ?>"
			 <?php wpl_esc::item_type($this->microdata, 'SingleFamilyResidence');?>
			>
			<div class="wpl_prp_top">
				<div class="wpl_prp_top_boxes front">
					<?php wpl_activity::load_position('wpl_property_listing_image', array('wpl_properties'=>$this->wpl_properties)); ?>
				</div>
				<div class="wpl_prp_top_boxes back">
					<a <?php wpl_esc::item_prop($this->microdata, 'url'); ?> id="prp_link_id_<?php wpl_esc::attr($property['data']['id']); ?>" href="<?php wpl_esc::url($property['property_link']); ?>" class="view_detail">
						<?php wpl_esc::html_t('More Details'); ?>
					</a>
				</div>
			</div>
			<div class="wpl_prp_bot">

				<a id="prp_link_id_<?php wpl_esc::attr($property['data']['id']); ?>_view_detail" href="<?php wpl_esc::url($property['property_link']); ?>" class="view_detail" title="<?php wpl_esc::attr($property['property_title']); ?>">
					<h3 class="wpl_prp_title"	<?php wpl_esc::item_prop($this->microdata, 'name'); ?> >
						<?php wpl_esc::html($property['property_title']) ?>
					</h3>
				</a>

				<?php $location_visibility = wpl_property::location_visibility($property['data']['id'], $property['data']['kind'], $current_user_membership_id); ?>
				<h4 class="wpl_prp_listing_location">
					<span <?php wpl_esc::item_address($this->microdata); ?>>
						<span <?php wpl_esc::item_prop($this->microdata, 'addressLocality'); ?>>
							<?php wpl_esc::html($location_visibility === true ? $property['location_text'] : $location_visibility);?>
						</span>
					</span>
				</h4>
				<?php if(wpl_global::check_addon('MLS') && $this->show_agent_name || $this->show_office_name): ?>
					<div class="wpl-mls-brokerage-info">
						<?php if($show_office_agent && !empty(($property['raw']['field_2112']))): ?>
							<div class="wpl-prp-agent-name">
								<label><?php wpl_esc::html($this->label_agent_name); ?></label>
								<span><?php wpl_esc::html(stripslashes($property['raw']['field_2112'] ?? '')); ?></span>
							</div>
						<?php endif; ?>
						<?php if($show_office_agent && !empty(($property['raw']['field_2111']))): ?>
							<div class="wpl-prp-office-name">
								<label><?php wpl_esc::html($this->label_office_name); ?></label>
								<span><?php wpl_esc::html(stripslashes($property['raw']['field_2111'] ?? '')); ?></span>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<div class="wpl_prp_listing_icon_box">
					<?php wpl_esc::e($room . $bathroom . $parking . $pic_count . $build_up_area); ?>
					<?php if(wpl_global::get_setting('show_plisting_visits')): ?>
						<div class="visits_box">
							<span class="name"><?php wpl_esc::html_t('Visits'); ?>:</span>
							<span class="value"><?php wpl_esc::html(wpl_property::get_property_stats_item($property['data']['id'], 'visit_time')); ?></span>
						</div>
					<?php endif; ?>
				</div>
				<div class="wpl_prp_desc" <?php wpl_esc::item_prop($this->microdata, 'description'); ?>><?php wpl_esc::html(substr($description, 0, $cut_position + 1)); ?></div>
			</div>
			<div class="price_box" <?php wpl_esc::item_type($this->microdata, 'offer'); ?>>
				<span <?php wpl_esc::item_prop($this->microdata, 'price'); ?>><?php wpl_esc::html($property_price); ?></span>
			</div>

			<?php if(wpl_global::check_addon('PRO') and $this->favorite_btn): ?>
				<div class="wpl_prp_listing_like">
					<div class="wpl_listing_links_container">
						<ul>
							<?php $find_favorite_item = in_array($property_id, $favorites); ?>
							<li class="favorite_link<?php wpl_esc::attr($find_favorite_item ? ' added' : ''); ?>">
								<a href="#" style="<?php wpl_esc::attr($find_favorite_item ? 'display: none;' : '') ?>" id="wpl_favorite_add_<?php wpl_esc::e($property_id); ?>" onclick="return wpl_favorite_control('<?php wpl_esc::e($property_id); ?>', 1);" title="<?php wpl_esc::attr_t('Add to favorites'); ?>"></a>
								<a href="#" style="<?php wpl_esc::attr(!$find_favorite_item ? 'display: none;' : '') ?>" id="wpl_favorite_remove_<?php wpl_esc::e($property_id); ?>" onclick="return wpl_favorite_control('<?php wpl_esc::e($property_id); ?>', 0);" title="<?php wpl_esc::attr_t('Remove from favorites'); ?>"></a>
							</li>
						</ul>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php
}