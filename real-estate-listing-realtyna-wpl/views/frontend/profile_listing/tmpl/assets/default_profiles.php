<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$description_column = 'about';
if(wpl_global::check_multilingual_status() and wpl_addon_pro::get_multiligual_status_by_column($description_column, 2)) $description_column = wpl_addon_pro::get_column_lang_name($description_column, wpl_global::get_current_language(), false);

foreach($this->wpl_profiles as $key=>$profile)
{
    if($key == 'current') continue;

    /** unset previous property **/
    unset($this->wpl_profiles['current']);

    /** set current property **/
    $this->wpl_profiles['current'] = $profile;

    $agent_name   = ($profile['materials']['first_name']['value'] ?? '') ;
    $agent_l_name = ($profile['materials']['last_name']['value'] ?? '');

    $description = stripslashes(strip_tags($profile['raw'][$description_column] ?? ''));
    ?>
    <div class="wpl-column">
      <div <?php wpl_esc::item_type($this->microdata, 'RealEstateAgent'); ?> class="wpl_profile_container <?php wpl_esc::attr($this->property_css_class ?? ''); ?>" id="wpl_profile_container<?php wpl_esc::attr($profile['data']['id']); ?>">
          <div class="wpl_profile_picture">
              <div class="front">
                  <?php
                      if(isset($profile['profile_picture']['url'])): ?>
						  <img <?php wpl_esc::item_prop($this->microdata, 'image'); ?>
								  src="<?php wpl_esc::url($profile['profile_picture']['url']); ?>"
								  alt="<?php wpl_esc::attr($agent_name.' '.$agent_l_name); ?>"
								  title="<?php wpl_esc::attr($agent_name.' '.$agent_l_name); ?>"
						  />
                      <?php elseif(isset($profile['company_logo']['url'])): ?>
					  	<img <?php wpl_esc::item_prop($this->microdata, 'image'); ?>
								src="<?php wpl_esc::url($profile['company_logo']['url']); ?>"
								alt="<?php wpl_esc::attr($agent_name.' '.$agent_l_name); ?>"
								title="<?php wpl_esc::attr($agent_name.' '.$agent_l_name); ?>"
						/>
                      <?php else: ?>
				  		<div <?php wpl_esc::item_prop($this->microdata, 'image'); ?> class="no_image"></div>
                      <?php endif; ?>

                  ?>
              </div>
              <div class="back">
                  <a <?php wpl_esc::item_prop($this->microdata, 'url'); ?> href="<?php wpl_esc::url($profile['profile_link']); ?>" class="view_properties"><?php wpl_esc::html_t('View properties'); ?></a>
              </div>
          </div>

          <div class="wpl_profile_container_title">
			  <div class="title">
				  <a <?php wpl_esc::item_prop($this->microdata, 'name'); ?> href="<?php wpl_esc::attr($profile['profile_link']); ?>" ><?php wpl_esc::attr($agent_name.' '.$agent_l_name); ?></a>
				  <a <?php wpl_esc::item_prop($this->microdata, 'url'); ?> href="<?php wpl_esc::attr($profile['profile_link']); ?>>" class="view_properties"><?php wpl_esc::html_t('View properties') ?></a>
			  </div>
			  <?php if(isset($profile['main_email_url']) and wpl_global::get_setting('profile_email_type') == '0'): ?>
				  <a href="mailto:<?php wpl_esc::attr($profile['data']['main_email']); ?>">
					  <img src="<?php wpl_esc::url($profile["main_email_url"]); ?>" alt="<?php wpl_esc::attr($agent_name.' '.$agent_l_name); ?>" title="<?php wpl_esc::attr($agent_name.' '.$agent_l_name); ?>" />
				  </a>
			  <?php endif; ?>
			  <?php if(isset($profile['main_email_url']) and wpl_global::get_setting('profile_email_type') == '1'): ?>
				<a class="email" href="mailto:<?php wpl_esc::attr($profile['data']['main_email']); ?>"><?php wpl_esc::html($profile['data']['main_email']); ?></a>
			  <?php endif; ?>
              <?php
                  $cut_position = (trim($description) ? strrpos(substr($description, 0, 400), '.', -1) : 0);
                  if(!$cut_position) $cut_position = 399;
              ?>
			  <div class="about" <?php wpl_esc::item_prop($this->microdata, 'description'); ?>><?php wpl_esc::kses(substr($description, 0, $cut_position + 1)); ?></div>
          </div>
          <ul>
              <?php if(isset($profile['materials']['website']['value'])): ?>
              <li class="website">
                <a class="wpl-tooltip-top" <?php wpl_esc::item_prop($this->microdata, 'url'); ?> href="<?php wpl_esc::url($profile['materials']['website']['value']); ?>" target="_blank"><?php wpl_esc::url($profile['materials']['website']['value']); ?></a>
                <div class="wpl-util-hidden"><?php wpl_esc::html($profile['materials']['website']['value']); ?></div>
              </li>
              <?php endif; ?>

              <?php if(isset($profile['materials']['tel']['value'])): ?>
              <li <?php wpl_esc::item_prop($this->microdata, 'telephone'); ?> class="phone">
                  <span><?php wpl_esc::html($profile['materials']['tel']['value']); ?></span>
                <a class="wpl-tooltip-top phone-link" href="tel:<?php wpl_esc::attr($profile['materials']['tel']['value']); ?>">
					<?php wpl_esc::html($profile['materials']['tel']['value']); ?>
				</a>
                <div class="wpl-util-hidden"><?php wpl_esc::html($profile['materials']['tel']['value']); ?></div>
              </li>
              <?php endif; ?>

              <?php if(isset($profile['materials']['mobile']['value'])): ?>
              <li <?php wpl_esc::item_prop($this->microdata, 'telephone'); ?> class="mobile">
                  <span><?php wpl_esc::html($profile['materials']['mobile']['value']); ?></span>
  				<a class="wpl-tooltip-top mobile-link" href="tel:<?php wpl_esc::attr($profile['materials']['mobile']['value']); ?>"><?php wpl_esc::attr($profile['materials']['mobile']['value']); ?></a>
                <div class="wpl-util-hidden"><?php wpl_esc::html($profile['materials']['mobile']['value']); ?></div>
  			  </li>
              <?php endif; ?>

              <?php if(isset($profile['materials']['fax']['value'])): ?>
              <li <?php wpl_esc::item_prop($this->microdata, 'faxNumber'); ?> class="fax wpl-tooltip-top">
                  <span><?php wpl_esc::html($profile['materials']['fax']['value']); ?></span>
              </li>
              <div class="wpl-util-hidden"><?php wpl_esc::html($profile['materials']['fax']['value']); ?></div>
              <?php endif ;?>
          </ul>
      </div>
    </div>
    <?php
}
