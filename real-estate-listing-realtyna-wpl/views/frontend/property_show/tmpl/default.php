<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$prp_type           = $this->wpl_properties['current']['materials']['property_type']['value'] ?? '';
$prp_listings       = $this->wpl_properties['current']['materials']['listing']['value'] ?? '';
$build_up_area      = $this->wpl_properties['current']['materials']['living_area']['value'] ?? ($this->wpl_properties['current']['materials']['lot_area']['value'] ?? '');
$build_up_area_name = isset($this->wpl_properties['current']['materials']['living_area']['value']) ? $this->wpl_properties['current']['materials']['living_area']['name'] : (isset($this->wpl_properties['current']['materials']['lot_area']['value']) ? $this->wpl_properties['current']['materials']['lot_area']['name'] : '');
$bedroom            = $this->wpl_properties['current']['materials']['bedrooms']['value'] ?? '';
$bathroom           = $this->wpl_properties['current']['materials']['bathrooms']['value'] ?? '';
$listing_id         = $this->wpl_properties['current']['materials']['mls_id']['value'] ?? '';
$price              = $this->wpl_properties['current']['materials']['price']['value'] ?? '';
$price_type         = $this->wpl_properties['current']['materials']['price_period']['value'] ?? '';
$location_string 	= (isset($this->wpl_properties['current']['location_text']) and $this->location_visibility === true) ? $this->wpl_properties['current']['location_text'] : $this->location_visibility;
$prp_title          = $this->wpl_properties['current']['property_title'] ?? '';
$visits             = wpl_property::get_property_stats_item($this->pid, 'visit_time');
$add_date           = $this->wpl_properties['current']['raw']['add_date'] ?? '0000-00-00 00:00:00';

/** Calculate how many vists per days **/
$days = 0;
if($add_date != '0000-00-00 00:00:00')
{
    $datetime1 = strtotime($add_date);
    $datetime2 = time();
    $interval = abs($datetime2 - $datetime1);
    $days = round($interval / 60 / 60 / 24);
}

if(wpl_global::check_addon('MLS') && $this->show_agent_name || $this->show_office_name )
{
    $office_name = isset($this->wpl_properties['current']['raw']['field_2111']) ? '<div class="wpl-prp-office-name"><label>'. wpl_esc::return_html($this->label_office_name).'</label><span>'.wpl_esc::return_html($this->wpl_properties['current']['raw']['field_2111']).'</span></div>' : '';
    $agent_name = isset($this->wpl_properties['current']['raw']['field_2112']) ? '<div class="wpl-prp-agent-name"> <label>'.wpl_esc::return_html($this->label_agent_name).'</label><span>'.wpl_esc::return_html($this->wpl_properties['current']['raw']['field_2112']).'</span></div>' : '';
}

$pshow_gallery_activities = wpl_activity::get_activities('pshow_gallery', 1);
$pshow_googlemap_activities = wpl_activity::get_activities('pshow_googlemap', 1, '', 'loadObject');
$pshow_walkscore_activities = wpl_activity::get_activities('pshow_walkscore', 1);
$pshow_bingmap_activities = wpl_activity::get_activities('pshow_bingmap', 1, '', 'loadObject');

$this->pshow_googlemap_activity_id = isset($pshow_googlemap_activities->id) ? $pshow_googlemap_activities->id : NULL;
$this->pshow_bingmap_activity_id = isset($pshow_bingmap_activities->id) ? $pshow_bingmap_activities->id : NULL;

/** video tab for showing videos **/
$pshow_video_activities = count(wpl_activity::get_activities('pshow_video', 1));
if(!isset($this->wpl_properties['current']['items']['video']) or (isset($this->wpl_properties['current']['items']['video']) and !count($this->wpl_properties['current']['items']['video']))) $pshow_video_activities = 0;

/** Import JS file **/
$this->_wpl_import($this->tpl_path.'.scripts.js', true, true);
?>
<div class="wpl_prp_show_container" id="wpl_prp_show_container">
    <div class="wpl_prp_container" id="wpl_prp_container<?php wpl_esc::attr($this->pid); ?>" <?php wpl_esc::item_type($this->microdata, 'SingleFamilyResidence'); ?>>
        <div class="wpl_prp_show_tabs">
            <div class="tabs_container">
            	<?php if($pshow_gallery_activities): ?>
                <div id="tabs-1" class="tabs_contents">
                    <?php /** load position gallery **/ wpl_activity::load_position('pshow_gallery', array('wpl_properties'=>$this->wpl_properties)); ?>
                </div>
                <?php endif; ?>
                <?php if($pshow_googlemap_activities and $this->location_visibility === true): ?>
                <div id="tabs-2" class="tabs_contents">
                    <?php /** load position googlemap **/ wpl_activity::load_position('pshow_googlemap', array('wpl_properties'=>$this->wpl_properties)); ?>
                </div>
                <?php endif; ?>
                <?php if($pshow_video_activities): ?>
                <div id="tabs-3" class="tabs_contents">
                    <?php /** load position video **/ wpl_activity::load_position('pshow_video', array('wpl_properties'=>$this->wpl_properties)); ?>
                </div>
                <?php endif; ?>
                <?php if($pshow_bingmap_activities and $this->location_visibility === true): ?>
                <div id="tabs-4" class="tabs_contents">
                    <?php /** load position bingmap **/ wpl_activity::load_position('pshow_bingmap', array('wpl_properties'=>$this->wpl_properties)); ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="tabs_box">
                <ul class="tabs">
                	<?php if($pshow_gallery_activities): ?>
                    <li><a href="#tabs-1" data-for="tabs-1"><?php wpl_esc::html('Pictures') ?></a></li>
                    <?php endif; ?>
                    <?php if($pshow_googlemap_activities and $this->location_visibility === true): ?>
                    <li><a href="#tabs-2" data-for="tabs-2" data-init-googlemap="1"><?php wpl_esc::html('Google Map') ?></a></li>
                    <?php endif; ?>
                    <?php if($pshow_video_activities): ?>
                    <li><a href="#tabs-3" data-for="tabs-3"><?php wpl_esc::html('Video') ?></a></li>
                    <?php endif; ?>
                    <?php if($pshow_bingmap_activities and $this->location_visibility === true): ?>
                    <li><a href="#tabs-4" data-for="tabs-4" data-init-bingmap="1"><?php wpl_esc::html("Bird's eye") ?></a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="wpl_prp_container_content">
            <div class="wpl-row wpl-expanded wpl_prp_container_content_title">
				<div class="wpl-large-10 wpl-medium-10 wpl-small-12 wpl-columns">
					<h1 class="title_text" <?php wpl_esc::item_prop($this->microdata, 'name'); ?>><?php wpl_esc::html($prp_title); ?></h1>
					<h2 class="location_build_up" <?php wpl_esc::item_address($this->microdata); ?> >
						<span class="wpl-location" <?php wpl_esc::item_prop($this->microdata, 'addressLocality'); ?>><?php wpl_esc::html($location_string); ?></span>
					</h2>
				</div>
				<div class="wpl-large-2 wpl-medium-2 wpl-small-12 wpl-columns">
					<?php wpl_activity::load_position('pshow_qr_code', array('wpl_properties'=>$this->wpl_properties)); ?>
				</div>
            </div>
            <div class="wpl_prp_container_content_top clearfix">
                <?php /** listing result **/ wpl_activity::load_position('pshow_listing_results', array('wpl_properties'=>$this->wpl_properties)); ?>
            </div>
            <div class="wpl-row wpl-expanded">
				<div class="wpl-large-8 wpl-medium-7 wpl-small-12 wpl_prp_container_content_left wpl-column">
				<?php wpl_activity::load_position('pshow_top', array('wpl_properties' => $this->wpl_properties)); ?>
				<?php
                    $description_column = 'field_308';
                    if(wpl_global::check_multilingual_status() and wpl_addon_pro::get_multiligual_status_by_column($description_column, $this->kind)) $description_column = wpl_addon_pro::get_column_lang_name($description_column, wpl_global::get_current_language(), false);
                    
                    if(isset($this->wpl_properties['current']['data'][$description_column]) and $this->wpl_properties['current']['data'][$description_column]):
                ?>
                <div class="wpl_prp_show_detail_boxes wpl_category_description">
                    <div class="wpl_prp_show_detail_boxes_title"><?php wpl_esc::html(wpl_flex::get_dbst_key('name', wpl_flex::get_dbst_id('field_308', $this->kind))) ?></div>
                    <div class="wpl_prp_show_detail_boxes_cont" <?php wpl_esc::item_prop($this->microdata, 'description'); ?>>
                        <?php wpl_esc::e(apply_filters('the_content', stripslashes($this->wpl_properties['current']['data'][$description_column] ?? ''))); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php
                $i = 0;
                $details_boxes_num = count($this->wpl_properties['current']['rendered']);
                
                foreach($this->wpl_properties['current']['rendered'] as $values)
				{
                    /** skip empty categories **/
					if(!count($values['data'])) continue;
                    
                    /** skip location if property address is hiden **/
					if($values['self']['prefix'] == 'ad' and $this->location_visibility !== true) continue;
                    ?>
					<div class="wpl_prp_show_detail_boxes wpl_category_<?php wpl_esc::attr($values['self']['id']); ?>">
						<div class="wpl_prp_show_detail_boxes_title"><span><?php wpl_esc::html($values['self']['name']); ?></span></div>
						<dl class="wpl-small-up-1 wpl-medium-up-1 wpl-large-up-<?php wpl_esc::attr($this->fields_columns); ?> wpl_prp_show_detail_boxes_cont">
					<?php
                    foreach($values['data'] as $key => $value)
					{
                        if(!isset($value['type'])) continue;
                        
                        elseif($value['type'] == 'neighborhood')
                        {
						?>
							<div id="wpl-dbst-show<?php wpl_esc::attr($value['field_id']); ?>" class="wpl-column rows neighborhood">
								<dt><?php wpl_esc::html($value['name']); ?></dt>
								<?php if(isset($value['distance'])): ?>
									<span class="<?php wpl_esc::attr($value['vehicle_type']); ?>"><?php wpl_esc::html($value['distance']); ?> <?php wpl_esc::html_t('Minutes'); ?></span>
								<?php endif; ?>
							</div>
						<?php
                        }
                        elseif($value['type'] == 'feature')
                        {
							?>
							<div id="wpl-dbst-show<?php wpl_esc::attr($value['field_id']); ?>" class="wpl-column rows feature <?php wpl_esc::attr(!isset($value['values'][0]) ? 'single' : ''); ?>">
								<dt><?php wpl_esc::html_t($value['name']);?><?php if(isset($value['values'][0])): ?>:<?php endif;?></dt>
                                <dd>
								<?php if(isset($value['values'][0])): ?>
									<?php
									$featured_values = [];
									foreach($value['values'] as $val) {
										$featured_values[] = wpl_esc::return_html_t($val);
									}
									wpl_esc::e(implode(', ', $featured_values));
									?>
								<?php endif; ?>
                                </dd>
							</div>
							<?php
                        }
                        elseif($value['type'] == 'locations' and isset($value['locations']) and is_array($value['locations']))
                        {
                            if(isset($value['settings']) and is_array($value['settings']))
                            {
                                foreach($value['settings'] as $ii=>$lvalue)
                                {
                                    if(isset($lvalue['enabled']) and !$lvalue['enabled']) continue;

                                    $lk = $value['keywords'][$ii] ?? '';
                                    if(trim($lk) == '') continue;
									?>
									<div id="wpl-dbst-show<?php wpl_esc::attr($value['field_id']); ?>-<?php wpl_esc::attr($lk); ?>" class="wpl-column rows location <?php wpl_esc::attr($lk); ?>">
										<dt><?php wpl_esc::html_t($lk); ?>:</dt> <dd><?php wpl_esc::html($value['locations'][$ii]); ?></dd>
									</div>
									<?php
                                }
                            }
                            else
                            {
                                foreach($value['locations'] as $ii=>$lvalue)
                                {
                                    $lk = $value['keywords'][$ii] ?? '';
                                    if(trim($lk) == '') continue;
									?>
									<div id="wpl-dbst-show<?php wpl_esc::attr($value['field_id']); ?>" class="wpl-column rows location <?php wpl_esc::attr($lk); ?>">
										<dt><?php wpl_esc::html_t($lk); ?>:</dt> <dd><?php wpl_esc::html($lvalue); ?></dd>
									</div>
									<?php
                                }
                            }
                        }
                        elseif($value['type'] == 'separator')
                        {
							?>
                            <div id="wpl-dbst-show<?php wpl_esc::attr($value['field_id']); ?>" class="wpl-column rows separator">
								<dt><?php wpl_esc::html_t($value['name']); ?></dt><dd></dd>
							</div>
							<?php
                        }
						elseif($value['type'] == 'textarea')
						{
							?>
							<div id="wpl-dbst-show<?php wpl_esc::attr($value['field_id']); ?>" class="wpl-column rows textarea">
								<?php wpl_esc::kses($value['value'], !empty($value['iframe']) ? ['iframe' => ['src' => [], 'width' => [], 'height' => [], 'style' => []]] : []); ?>
							</div>
							<?php
						}
						elseif($value['type'] == 'url')
						{
							?>
							<div id="wpl-dbst-show<?php wpl_esc::attr($value['field_id']); ?>" class="wpl-column rows url">
								<dt><?php wpl_esc::html_t($value['name']); ?>:</dt> <dd><?php wpl_esc::e($value['value'] ?? ''); ?></dd>
							</div>
							<?php
						}
                        else {
							?>
							<div id="wpl-dbst-show<?php wpl_esc::attr($value['field_id']); ?>" class="wpl-column rows other">
								<dt><?php wpl_esc::html_t($value['name']); ?>:</dt> <dd><?php wpl_esc::e($value['value'] ?? ''); ?></dd>
							</div>
							<?php
						}
                    }
                    ?>
                    </dl>
                </div>
                	<?php
                    $i++;
                }
                ?>
                
                <div class="wpl_prp_show_position3">
                    <?php
                        $activities = wpl_activity::get_activities('pshow_position3');
                        foreach($activities as $activity)
                        {
                            $content = wpl_activity::render_activity($activity, array('wpl_properties'=>$this->wpl_properties));
                            if(trim($content ?? '') == '') continue;
                            
                            $activity_title =  explode(':', $activity->activity);
                            ?>
                            <div class="wpl_prp_position3_boxes <?php wpl_esc::attr($activity_title[0]); ?>">
                                <?php
                                if($activity->show_title and trim($activity->title ?? '') != '')
                                {
                                    $activity_box_title = NULL;
                                    $title_parts = explode(' ', wpl_esc::return_html_t($activity->title ?? 'Activity Title'));
                                    $i_part = 0;

                                    foreach($title_parts as $title_part)
                                    {
                                        if($i_part == 0) $activity_box_title .= '<span>'.wpl_esc::return_html($title_part).'</span> ';
                                        else $activity_box_title .= wpl_esc::return_html($title_part).' ';

                                        $i_part++;
                                    }

                                    wpl_esc::e('<div class="wpl_prp_position3_boxes_title">'.wpl_esc::e($activity_box_title).'</div>');
                                }
                                ?>
                                <div class="wpl_prp_position3_boxes_content clearfix">
                                    <?php wpl_esc::e($content); ?>
                                </div>
                            </div>
                            <?php
                        }
                    ?>
                </div>
            </div>
			    <div class="wpl-large-4 wpl-medium-5 wpl-small-12 wpl_prp_container_content_right wpl-column">
			
                <div class="wpl_prp_right_boxes details">
                    <div class="wpl_prp_right_boxes_title">
						<span><?php wpl_esc::html($prp_type); ?></span>
						<?php wpl_esc::html($prp_listings); ?>
                    </div>
                    <div class="wpl_prp_right_boxes_content">
                        <div class="wpl_prp_right_boxe_details_top clearfix">
                            <div class="wpl_prp_right_boxe_details_left">
                                <dl>
                                    <?php if(trim($listing_id ?? '') != ''): ?>
                                        <div class="wpl-listing-id">
                                            <dt><?php wpl_esc::html($this->wpl_properties['current']['materials']['mls_id']['name']);?>:</dt> <dd class="value"><?php wpl_esc::html($listing_id); ?></dd>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(trim($bedroom ?? '') != ''): ?>
                                        <div class="wpl-bedroom" <?php wpl_esc::item_prop($this->microdata, 'numberOfRooms'); ?><?php wpl_esc::item_type($this->microdata, 'QuantitativeValue'); ?>>
                                            <dt <?php wpl_esc::item_prop($this->microdata, 'name'); ?> ><?php wpl_esc::html($this->wpl_properties['current']['materials']['bedrooms']['name']); ?>: </dt>
											<dd <?php wpl_esc::item_prop($this->microdata, 'value'); ?> class="value"><?php wpl_esc::html($bedroom); ?></dd>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(trim($bathroom ?? '') != ''): ?>
                                        <div class="wpl-bathroom" <?php wpl_esc::item_prop($this->microdata, 'numberOfRooms'); ?><?php wpl_esc::item_type($this->microdata, 'QuantitativeValue'); ?>>
                                            <dt <?php wpl_esc::item_prop($this->microdata, 'name'); ?>><?php wpl_esc::html($this->wpl_properties['current']['materials']['bathrooms']['name']); ?>: </dt>
											<dd <?php wpl_esc::item_prop($this->microdata, 'value'); ?> class="value"><?php wpl_esc::html($bathroom); ?></dd>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(trim($build_up_area ?? '') != ''): ?>
                                        <div class="wpl-build-up-area">
                                            <dt><?php wpl_esc::html($build_up_area_name) ?>:</dt>
											<dd class="value" <?php wpl_esc::item_prop($this->microdata, 'floorSize'); ?><?php wpl_esc::item_type($this->microdata, 'QuantitativeValue'); ?> >
												<span class="value" <?php wpl_esc::item_prop($this->microdata, 'value'); ?>><?php wpl_esc::html($build_up_area); ?></span>
											</dd>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($price_type): ?>
                                        <div class="wpl-price">
                                            <dt><?php wpl_esc::html($this->wpl_properties['current']['materials']['price_period']['name']); ?>:</dt>
											<dd class="value"><?php wpl_esc::html($price_type); ?></dd>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(wpl_global::get_setting('show_plisting_visits')): ?>
                                    <div class="wpl-property-visit">
                                        <dt><?php wpl_esc::html('Visits:'); ?></dt>
                                        <?php wpl_esc::e('<dd class="value">'.$visits.($days ? ' '.sprintf(__('in %d days'), $days) : '').'</dd>'); ?>
                                    </div>
                                    <?php endif; ?>
                                    <?php if(wpl_global::check_addon('MLS') && $this->show_agent_name || $this->show_office_name): ?>
                                        <div class="wpl-mls-brokerage-info">
                                            <?php if($this->show_agent_name): ?> <li><?php wpl_esc::e($agent_name) ?></li> <?php endif; ?>
                                            <?php if($this->show_office_name): ?> <li><?php wpl_esc::e($office_name) ?></li> <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </dl>
                            </div>
                            <div class="wpl_prp_right_boxe_details_right">
                                <?php /** load wpl_pshow_link activity **/ wpl_activity::load_position('wpl_pshow_link', array('wpl_properties'=>$this->wpl_properties)); ?>
                            </div>
                        </div>
                        <div class="wpl_prp_right_boxe_details_bot" <?php wpl_esc::item_type($this->microdata, 'offer'); ?>>
							<div class="price_box" <?php wpl_esc::item_prop($this->microdata, 'price'); ?>><?php wpl_esc::html($price); ?></div>
                        </div>
                    </div>
                </div>
                <div class="wpl_prp_show_position2">
                    <?php
                        $activities = wpl_activity::get_activities('pshow_position2');
                        foreach($activities as $activity)
                        {
                            $content = wpl_activity::render_activity($activity, array('wpl_properties'=>$this->wpl_properties));
                            if(trim($content ?? '') == '') continue;
                            
                            $activity_title =  explode(':', $activity->activity);
                            ?>
                            <div class="wpl_prp_right_boxes <?php wpl_esc::attr($activity_title[0]); ?>">
                                <?php
                                if($activity->show_title and trim($activity->title ?? '') != '')
                                {
                                    $activity_box_title = NULL;
                                    $title_parts = explode(' ', wpl_esc::return_html_t($activity->title ?? 'Activity Title'));
                                    $i_part = 0;

                                    foreach($title_parts as $title_part)
                                    {
                                        if($i_part == 0) $activity_box_title .= '<span>'.wpl_esc::return_html($title_part).'</span> ';
                                        else $activity_box_title .= wpl_esc::return_html($title_part).' ';

                                        $i_part++;
                                    }
									?>
                                    <div class="wpl_prp_right_boxes_title"><?php wpl_esc::e($activity_box_title); ?></div>
									<?php
                                }
                                ?>
                                <div class="wpl_prp_right_boxes_content clearfix">
                                    <?php wpl_esc::e($content); ?>
                                </div>
                            </div>
                            <?php
                        }
                    ?>
                </div>
            </div>
            </div>
            <div class="wpl_prp_show_bottom">
                <?php if($pshow_walkscore_activities): ?>
                <div class="wpl_prp_show_walkscore">
                    <?php /** load position walkscore **/ wpl_activity::load_position('pshow_walkscore', array('wpl_properties'=>$this->wpl_properties)); ?>
                </div>
                <?php endif; ?>
                <?php if(is_active_sidebar('wpl-pshow-bottom')) dynamic_sidebar('wpl-pshow-bottom'); ?>
            </div>
        </div>
    </div>
    <?php /** Don't remove this element **/ ?>
    <div id="wpl_pshow_lightbox_content_container" class="wpl-util-hidden"></div>
    
    <?php if(wpl_global::check_addon('membership') and wpl_session::get('wpl_dpr_popup')): ?>
    <a id="wpl_dpr_lightbox" class="wpl-util-hidden" data-realtyna-href="#wpl_pshow_lightbox_content_container" data-realtyna-lightbox-opts="title:<?php wpl_esc::attr('Login to continue'); ?>"></a>
    <?php endif; ?>
    <?php if($this->show_signature): ?>
    <div class="wpl-powered-by-realtyna">
        <a href="https://realtyna.com/wpl-platform/ref/<?php wpl_esc::attr($this->affiliate_id); ?>/">
            <img src="<?php wpl_esc::e(wpl_global::get_wpl_url().'assets/img/idx/powered-by-realtyna.png'); ?>" alt="Realtyna" title="Powered By Realtyna" width="120"/>
        </a>
    </div>
    <?php endif; ?>
</div>