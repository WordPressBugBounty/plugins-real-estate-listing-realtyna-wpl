<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->lazyload = ( trim($instance['data']['lazyload'] ?? '') != '') ? $instance['data']['lazyload'] : 0;
$lazy_load = $this->lazyload ? 'lazyimg' : '';
$src = $this->lazyload ? 'data-src' : 'src';

/** import js codes **/
$this->_wpl_import('widgets.agents.scripts.js', true, true);
?>
<ul class="wpl_agents_widget_container list <?php wpl_esc::attr($this->css_class); ?>" >
    <?php
    foreach($wpl_profiles as $key=>$profile)
    {
        $agent_name   = (isset($profile['materials']['first_name']['value']) ? $profile['materials']['first_name']['value'] : '') ;
        $agent_l_name = (isset($profile['materials']['last_name']['value']) ? $profile['materials']['last_name']['value'] : '');
        ?>
        <li class="wpl_profile_box" id="wpl_profile_container<?php wpl_esc::attr($profile['data']['id']); ?>" <?php wpl_esc::item_type($this->microdata, 'RealEstateAgent'); ?>>
            <div class="profile_left">
                <a class="more_info view_properties" href="<?php wpl_esc::url($profile['profile_link']); ?>">
                    <span
						style="<?php
						wpl_esc::attr(isset($profile['profile_picture']['image_width']) ? 'width:'.$profile['profile_picture']['image_width'].'px;' : '');
						wpl_esc::attr(isset($profile['profile_picture']['image_height']) ? 'height:'.$profile['profile_picture']['image_height'].'px;' : '');
						?>"
					>
					<?php if(!empty($profile['profile_picture']['url'])): ?>
						<img class="<?php wpl_esc::e($lazy_load); ?>"
						<?php wpl_esc::item_prop($this->microdata, 'image'); ?>
						<?php wpl_esc::e($src); ?>="<?php wpl_esc::url($profile['profile_picture']['url']); ?>"
						alt="<?php wpl_esc::attr($agent_name); ?> <?php wpl_esc::attr($agent_l_name); ?>"
						title="<?php wpl_esc::attr($agent_name); ?> <?php wpl_esc::attr($agent_l_name); ?>"
						/>
					<?php else: ?>
						<div class="no_image"></div>
					<?php endif; ?>
                    </span>
                </a>
            </div>
            <div class="profile_right">
                 <ul>
					 <li class="title" <?php wpl_esc::item_prop($this->microdata, 'name'); ?>>
						 <?php wpl_esc::html($agent_name); ?> <?php wpl_esc::html($agent_l_name); ?>
					 </li>
					 <li>
						 <?php
						 $show_email_link = (isset($profile['main_email_url']) and !empty($instance['data']['mailto_status']) and in_array($instance['data']['mailto_status'], [1, 'Yes']));
						 ?>
						 <?php if($show_email_link): ?>
						 <a class="<?php wpl_esc::e(wpl_global::get_setting('profile_email_type') == '1' ? 'email' : '') ?>" <?php wpl_esc::item_prop($this->microdata, 'email'); ?> href="mailto:<?php wpl_esc::attr($profile['data']['main_email']); ?>">
						 <?php endif; ?>
							 <?php if(wpl_global::get_setting('profile_email_type') == '0'): ?>
								 <img class="<?php wpl_esc::e($lazy_load); ?>"
								 alt="<?php wpl_esc::attr($agent_name); ?> <?php wpl_esc::attr($agent_l_name); ?>"
								 title="<?php wpl_esc::attr($agent_name); ?> <?php wpl_esc::attr($agent_l_name); ?>"
								 <?php wpl_esc::e($src); ?>="<?php wpl_esc::url($profile['main_email_url']); ?>"
								 />
							 <?php elseif(wpl_global::get_setting('profile_email_type') == '1'): ?>
								 <?php wpl_esc::html($profile['data']['main_email']); ?>
							 <?php endif; ?>
						 <?php if($show_email_link): ?>
						 </a>
					 	<?php endif; ?>
					 </li>
                    <?php if(isset($profile['materials']['website']['value'])): ?>
                        <li class="website">
                            <a <?php wpl_esc::item_prop($this->microdata, 'url'); ?> href="<?php
                            $urlStr = $profile['materials']['website']['value'] ?? '';
                            $parsed = parse_url($urlStr);
                            if (empty($parsed['scheme'])) {
                                $urlStr = 'http://' . ltrim($urlStr, '/');
                            }
							wpl_esc::url($urlStr);
                            ?>" target="_blank">
								<?php wpl_esc::html_t('View website') ?>
							</a>
                        </li>
                    <?php endif; ?>
                    <?php if(isset($profile['materials']['tel']['value'])): ?>
                        <li class="phone">
                            <a href="tel:<?php wpl_esc::attr($profile['materials']['tel']['value']); ?>">
                                <span <?php wpl_esc::item_prop($this->microdata, 'telephone'); ?>>
									<?php wpl_esc::html($profile['materials']['tel']['value']); ?>
								</span>
                            </a>
                        </li>
                    <?php endif; ?>
                     <?php if(isset($profile['materials']['company_address'])): ?>
                         <li style="display:none">
                             <div <?php wpl_esc::item_address($this->microdata); ?> class="company_address">
								 <span <?php wpl_esc::item_prop($this->microdata, 'addressLocality'); ?>>
									 <?php wpl_esc::html($profile['materials']['company_address']['value']); ?>
								 </span>
							 </div>
                         </li>
                     <?php endif; ?>
                </ul>
            </div>
        </li>
    <?php
    }
    ?>
</ul>