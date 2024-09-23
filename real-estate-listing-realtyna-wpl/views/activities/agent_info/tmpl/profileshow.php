<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$user_id = $params['user_id'] ?? '';
$wpl_properties = $params['wpl_properties'] ?? NULL;
$picture_width = $params['picture_width'] ?? 'auto';
$picture_height = $params['picture_height'] ?? '145';
$mailto = $params['mailto'] ?? 0;

$description_column = 'about';
if (wpl_global::check_multilingual_status() and wpl_addon_pro::get_multiligual_status_by_column($description_column, 2)) $description_column = wpl_addon_pro::get_column_lang_name($description_column, wpl_global::get_current_language(), false);

/** getting user id from current property (used in property_show and property_listing) **/
if (!trim($user_id ?? '')) $user_id = $wpl_properties['current']['data']['user_id'];

$user_id = apply_filters('wpl_activities/agent_info/profileshow/user_id', $user_id, $wpl_properties['current']['data'], $params);

$wpl_user = wpl_users::full_render($user_id, wpl_users::get_pshow_fields(), NULL, array(), true);

/** resizing profile image **/
$params['image_parentid'] = $user_id;
$params['image_name'] = $wpl_user['profile_picture']['name'] ?? '';
$profile_path = $wpl_user['profile_picture']['path'] ?? '';
$profile_image = wpl_images::create_profile_images($profile_path, $picture_width, $picture_height, $params);

/** resizing company logo **/
$params['image_parentid'] = $user_id;
$params['image_name'] = $wpl_user['company_logo']['name'] ?? '';
$logo_path = $wpl_user['company_logo']['path'] ?? '';
$logo_image = $wpl_user['company_logo']['url'] ?? '';

$agent_name = $wpl_user['materials']['first_name']['value'] ?? '';
$agent_l_name = $wpl_user['materials']['last_name']['value'] ?? '';
$company_name = $wpl_user['materials']['company_name']['value'] ?? '';
$description = stripslashes($wpl_user['raw'][$description_column] ?? '');
$description = (!preg_match('!!u', $description) ? utf8_decode($description) : $description);

/** Preparing website URL **/
$website = '';
if (isset($wpl_user['materials']['website']['value'])) {
	$website = $wpl_user['materials']['website']['value'];
	if (stripos($website, 'http://') === false and stripos($website, 'https://') === false) {
		$website = 'http://' . $website;
	}
}
?>
<div <?php wpl_esc::item_type($this->microdata, 'RealEstateAgent'); ?> class="wpl_agent_info clearfix"
																	   id="wpl_agent_info">
	<div class="wpl_agent_details clearfix">
		<div class="wpl_agent_info_l wpl_agent_info_pic" style="width:<?php wpl_esc::attr($picture_width); ?>">

			<?php if (isset($wpl_user['profile_picture'])): ?>
				<img <?php wpl_esc::item_prop($this->microdata, 'image'); ?>
						src="<?php wpl_esc::url($profile_image); ?>"
						alt="<?php wpl_esc::attr($agent_name . ' ' . $agent_l_name); ?>"
						title="<?php wpl_esc::attr($agent_name . ' ' . $agent_l_name); ?>"/>';
			<?php else: ?>
				<div class="no_image"></div>
			<?php endif; ?>
		</div>
		<div class="wpl_agent_info_detail">
			<div class="wpl_agent_info_c wpl-large-8 wpl-medium-8 wpl-small-12 wpl-column clearfix">
				<div class="wpl_profile_container_title" <?php wpl_esc::item_prop($this->microdata, 'name'); ?>>
					<?php wpl_esc::html( $agent_name . ' ' . $agent_l_name); ?>
				</div>
				<ul class="wpl-agent-info-main-fields">
					<?php if ($website): ?>
						<li class="website">
							<a <?php wpl_esc::item_prop($this->microdata, 'url'); ?>
									href="<?php wpl_esc::url($website); ?>"
									target="_blank">
								<?php wpl_esc::html_t('View website') ?>
							</a>
						</li>
					<?php endif; ?>

					<?php if (isset($wpl_user['materials']['tel']['value'])): ?>
						<li class="tel" <?php wpl_esc::item_prop($this->microdata, 'telephone'); ?>>
							<label><?php wpl_esc::attr($wpl_user['materials']['tel']['name']); ?>:</label><a
									href="tel:<?php wpl_esc::attr($wpl_user['materials']['tel']['value']); ?>"><?php wpl_esc::html($wpl_user['materials']['tel']['value']); ?></a>
						</li>
					<?php endif; ?>

					<?php if (isset($wpl_user['materials']['mobile']['value'])): ?>
						<li class="mobile" <?php wpl_esc::item_prop($this->microdata, 'telephone'); ?>>
							<label><?php wpl_esc::attr($wpl_user['materials']['mobile']['name']); ?>:</label><a
									href="tel:<?php wpl_esc::attr($wpl_user['materials']['mobile']['value']); ?>"><?php wpl_esc::html($wpl_user['materials']['mobile']['value']); ?></a>
						</li>
					<?php endif; ?>

					<?php if (isset($wpl_user['materials']['fax']['value'])): ?>
						<li class="fax" <?php wpl_esc::item_prop($this->microdata, 'faxNumber'); ?>>
							<label><?php wpl_esc::attr($wpl_user['materials']['fax']['name']); ?>
								:</label><?php wpl_esc::attr($wpl_user['materials']['fax']['value']); ?></li>
					<?php endif; ?>

					<?php if (isset($wpl_user['main_email_url']) and wpl_global::get_setting('profile_email_type') == '0'): ?>
						<li class="email">
							<label><?php wpl_esc::html($wpl_user['materials']['main_email']['name']); ?>:</label>
							<?php if ($mailto): ?>
								<a <?php wpl_esc::item_prop($this->microdata, 'email'); ?>
										href="mailto:<?php wpl_esc::attr($wpl_user['materials']['main_email']['value']); ?>"><img
											src="<?php wpl_esc::url($wpl_user['main_email_url']); ?>"
											alt="<?php wpl_esc::attr($agent_name . ' ' . $agent_l_name); ?>"
											title="<?php wpl_esc::attr($agent_name . ' ' . $agent_l_name); ?>"/></a>
							<?php else: ?>
								<img src="<?php wpl_esc::url($wpl_user['main_email_url']); ?>"
									 alt="<?php wpl_esc::attr($agent_name . ' ' . $agent_l_name); ?>"
									 title="<?php wpl_esc::attr($agent_name . ' ' . $agent_l_name); ?>"/>
							<?php endif; ?>
						</li>
					<?php endif; ?>
					<?php if (isset($wpl_user['main_email_url']) and wpl_global::get_setting('profile_email_type') == '1'): ?>
						<li class="email">
							<label><?php wpl_esc::attr($wpl_user['materials']['main_email']['name']); ?>:</label>
							<?php if ($mailto): ?>
								<a <?php wpl_esc::item_prop($this->microdata, 'email'); ?>
										href="mailto:<?php wpl_esc::attr($wpl_user['materials']['main_email']['value']); ?>"><?php wpl_esc::html($wpl_user['materials']['main_email']['value']); ?></a>
							<?php else: ?>
								<p><?php wpl_esc::html($wpl_user['materials']['main_email']['value']); ?></p>
							<?php endif; ?>
						</li>
					<?php endif; ?>

					<?php if (isset($wpl_user['second_email_url']) and wpl_global::get_setting('profile_email_type') == '0'): ?>
						<li class="second_email">
							<label><?php wpl_esc::html($wpl_user['materials']['secondary_email']['name']); ?>:</label>
							<?php if ($mailto): ?>
								<a <?php wpl_esc::item_prop($this->microdata, 'email'); ?>
										href="mailto:<?php wpl_esc::attr($wpl_user['materials']['secondary_email']['value']); ?>"><img
											src="<?php wpl_esc::url($wpl_user['second_email_url']); ?>"
											alt="<?php wpl_esc::attr($agent_name . ' ' . $agent_l_name); ?>"
											title="<?php wpl_esc::attr($agent_name . ' ' . $agent_l_name); ?>"/></a>
							<?php else: ?>
								<img src="<?php wpl_esc::url($wpl_user['second_email_url']); ?>"
									 alt="<?php wpl_esc::attr($agent_name . ' ' . $agent_l_name); ?>"
									 title="<?php wpl_esc::attr($agent_name . ' ' . $agent_l_name); ?>"/>
							<?php endif; ?>
						</li>
					<?php endif; ?>
					<?php if (isset($wpl_user['second_email_url']) and wpl_global::get_setting('profile_email_type') == '1'): ?>
						<li class="second_email">
							<label><?php wpl_esc::html($wpl_user['materials']['secondary_email']['name']); ?>:</label>
							<?php if ($mailto): ?>
								<a <?php wpl_esc::item_prop($this->microdata, 'email'); ?>
										href="mailto:<?php wpl_esc::attr($wpl_user['materials']['secondary_email']['value']); ?>"><?php wpl_esc::html($wpl_user['materials']['secondary_email']['value']); ?></a>
							<?php else: ?>
								<p><?php wpl_esc::html($wpl_user['materials']['secondary_email']['value']); ?></p>
							<?php endif; ?>
						</li>
					<?php endif; ?>

				</ul>
				<ul class="wpl-agent-info-other-fields">
					<?php
					foreach ($wpl_user['materials'] as $values) {
						if ($values['field_id'] == 900 || $values['field_id'] == 901 || $values['field_id'] == 902 || $values['field_id'] == 903 || $values['field_id'] == 904 || $values['field_id'] == 905 || $values['field_id'] == 914 || $values['field_id'] == 907 || $values['field_id'] == 908 || $values['field_id'] == 909 || $values['field_id'] == 918 || $values['field_id'] == 919 || $values['field_id'] == 920 || $values['field_id'] == 911) {
							continue;
						} elseif ($values['type'] == 'upload') {
							?>
							<li>
								<label><?php wpl_esc::html($values['name']); ?>:</label>
								<span>
									<a target="_blank" href="<?php wpl_esc::url(wpl_items::get_folder($user_id, 2) . $values['value']); ?>">
										<?php wpl_esc::html($values['value']); ?>
									</a>
								</span>
							</li>
							<?php
						} else {
							?>
							<li>
								<label><?php wpl_esc::html($values['name']); ?>:</label>
								<span><?php wpl_esc::html($values['value']); ?></span>
							</li>
							<?php
						}
					}
					?>
				</ul>
			</div>
			<div class="wpl_agent_info_r wpl-large-4 wpl-medium-4 wpl-small-12  wpl-column">
				<?php if (isset($wpl_user['company_logo'])): ?>
					<img <?php wpl_esc::item_prop($this->microdata, 'logo'); ?> src="<?php wpl_esc::attr($logo_image); ?>" alt="<?php wpl_esc::attr($company_name); ?>" title="<?php wpl_esc::attr($company_name); ?>" />
				<?php endif; ?>
				<?php if (isset($wpl_user['company_logo'])): ?>
					<div class="company" <?php wpl_esc::item_prop($this->microdata, 'name'); ?>><?php wpl_esc::html($company_name); ?></div>
				<?php endif; ?>
				<?php if (isset($wpl_user['materials']['company_address'])): ?>
				 	<div class="location"  <?php wpl_esc::item_address($this->microdata); ?>>
						<span <?php wpl_esc::item_prop($this->microdata, 'addressLocality'); ?>>
							<?php wpl_esc::html($wpl_user['materials']['company_address']['value']); ?>
						</Span>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="wpl_agent_about" <?php wpl_esc::item_prop($this->microdata, 'description'); ?>><?php wpl_esc::kses($description); ?></div>
</div>