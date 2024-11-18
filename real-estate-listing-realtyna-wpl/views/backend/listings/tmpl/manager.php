<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path.'.scripts.css');
$this->_wpl_import($this->tpl_path.'.scripts.js');
?>
<div class="wrap wpl-wp pmanager-wp wpl_view_container">
    <header>
        <div id="icon-pmanager" class="icon48"></div>
        <h2><?php wpl_esc::e(sprintf(wpl_esc::return_html_t('%s Manager'), wpl_esc::return_html_t(ucfirst($this->kind_label)))); ?></h2>
        <button class="wpl-button button-1" onclick="window.location.href = wplj(this).data('href');" data-href="<?php wpl_esc::url($this->add_link); ?>">
			<?php wpl_esc::html_t('Add Listing'); ?>
		</button>
    </header>

    <?php $this->include_tabs(); ?>

    <div class="wpl_property_manager_list"><div class="wpl_show_message"></div></div>
    <div class="pmanager-cnt">
        
        <!-- generate search form -->
        <?php $this->generate_search_form(); ?>

        <div class="mass-panel-wp">
            <h3><?php wpl_esc::html_t("Mass actions") ?>: </h3>
            <div class="mass-actions-wp p-actions-wp">
                <div class="group-btn">
                    <div class="mass-btn icon-select-all p-action-btn" onclick="rta.util.checkboxes.selectAll('.properties-wp');">
                        <span><?php wpl_esc::html_t('Select all'); ?></span>
                        <i class="icon-select"></i>
                    </div>
                    <div class="mass-btn icon-deselect-all p-action-btn" onclick="rta.util.checkboxes.deSelectAll('.properties-wp');">
                        <span><?php wpl_esc::html_t('Deselect all'); ?></span>
                        <i class="icon-unselect"></i>
                    </div>
                    <div class="mass-btn icon-toggle p-action-btn" onclick="rta.util.checkboxes.toggle('.properties-wp');">
                        <span><?php wpl_esc::html_t('Toggle'); ?></span>
                        <i class="icon-toggle"></i>
                    </div>
                </div>
                <div class="group-btn">
                    <div class="mass-btn icon-confirm p-action-btn" onclick="mass_confirm_properties();">
                        <span><?php wpl_esc::html_t('Confirm'); ?></span>
                        <i class="icon-confirm"></i>
                    </div>
                    <div class="mass-btn icon-unconfirm p-action-btn" onclick="mass_unconfirm_properties();">
                        <span><?php wpl_esc::html_t('Unconfirm'); ?></span>
                        <i class="icon-unconfirm"></i>
                    </div>
                    <div class="mass-btn icon-delete p-action-btn" onclick="mass_trash_properties();">
                        <span><?php wpl_esc::html_t('Trash'); ?></span>
                        <i class="icon-trash"></i>
                    </div>
                    <div class="mass-btn icon-restore p-action-btn" onclick="mass_restore_properties();">
                        <span><?php wpl_esc::html_t('Restore'); ?></span>
                        <i class="icon-restore"></i>
                    </div>
                    <div class="mass-btn icon-delete-permanently p-action-btn" onclick="mass_delete_completely_properties();">
                        <span><?php wpl_esc::html_t('Purge'); ?></span>
                        <i class="icon-delete"></i>
                    </div>
                    <?php if(wpl_global::check_addon('facebook') && get_option('wpl_addon_facebook_init_info') !== false && get_option( 'wpl_addon_facebook_catalog_id' ) !== false): ?>
                    <div class="mass-btn p-action-btn p-action-facebook-btn wpl-mass-publish-facebook" onclick="mass_facebook_publish_properties();">
                        <label><?php wpl_esc::html_t('Publish on Facebook'); ?></label>
                        <i class="icon-confirm"></i>
                    </div>
                    <?php endif; ?>
                </div>
                <?php /** load position1 **/ wpl_activity::load_position('pmanager_position1', array('wpl_properties'=>$this->wpl_properties)); ?>
            </div>
            <?php if(wpl_users::check_access('change_user') and !wpl_global::is_multisite()): ?>
                <div class="change-user-cnt-wp">
                    <div class="change-user-wp">
                        <label id="pmanager_mass_change_user_label" for="pmanager_mass_change_user_select">
							<?php wpl_esc::html_t('Change User to'); ?>
						</label>
						<?php wpl_users::autocomplete(null, [
							'id' => 'pmanager_mass_change_user_select',
							'onchange' => 'mass_change_user(this.value);',
						], true); ?>
                    </div>
                </div>
                <?php if(wpl_users::check_access('multi_agents') and wpl_global::check_addon('multi_agents')): ?>
                <div class="change-multi-agent-cnt-wp">
                    <div class="change-multi-agent-wp">
                        <label id="pmanager_mass_additional_agents_label" for="pmanager_mass_additional_agents_select">
							<?php wpl_esc::html_t('Additional Agents'); ?>
						</label>
						<?php wpl_users::autocomplete(null, [
							'id' => 'pmanager_mass_additional_agents_select',
							'multiple' => 'multiple',
							'data-chosen-opt' => 'width:40%',
						], true); ?>
                        <div class="wpl-button button-1 mass-btn icon-additional-agents p-action-btn" onclick="mass_additional_agents();">
                            <span><?php wpl_esc::html_t('Assign'); ?></span>
                            <i class="icon-agent"></i>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php if(isset($this->pagination->max_page) and $this->pagination->max_page > 1): ?>
        <div class="pagination-wp">
            <?php wpl_esc::e($this->pagination->show()); ?>
        </div>
        <?php endif; ?>
        <div class="properties-wp">
            <?php
            foreach($this->wpl_properties as $key=>$property)
            {
                if($key == 'current') continue;

                $property_type = isset($property['materials']['property_type']['value']) ? wpl_esc::return_html($property['materials']['property_type']['value']) : '';
                $listing_type = isset($property['materials']['listing']['value']) ? wpl_esc::return_html($property['materials']['listing']['value']) : '';
                $price = isset($property['materials']['price']['value']) ? '<span class="plist_price">'. wpl_esc::return_html($property['materials']['price']['value']).'</span>' : '';
                $builtup_area = isset($property['materials']['living_area']['value']) ? wpl_esc::return_html($property['materials']['living_area']['value']) : '';

                $details_string = trim($property_type.', '.$listing_type.', '.$price.', '.$builtup_area, ', ');
                $location_string = $property['location_text'];

                /** unset previous property **/
                unset($this->wpl_properties['current']);

                /** set current property **/
                $this->wpl_properties['current'] = $property;
                ?>

                <div id="plist_main_div_<?php wpl_esc::attr($property['data']['id']); ?>" class="propery-wp <?php if(wpl_users::check_access('multi_agents') and wpl_global::check_addon('multi_agents') and in_array($property['data']['kind'], array(0,1))) wpl_esc::e("propery-wp-multi-agent"); ?>">
                    <div class="checkbox-wp">
                        <input class="js-pcheckbox" type="checkbox" id="<?php wpl_esc::attr($property['data']['id']); ?>" />
                    </div>

                    <div class="property-image">
                        <?php /** load position3 **/ wpl_activity::load_position('pmanager_position3', array('wpl_properties'=>$this->wpl_properties)); ?>
                        <?php $listing_target_page = wpl_global::get_client() == 1 ? wpl_global::get_setting('backend_listing_target_page') : NULL; ?>
                        <a class="p-links" href="<?php wpl_esc::url(wpl_property::get_property_link([], $property['data']['id'], $listing_target_page)); ?>">
							<?php wpl_esc::html_t('View this listing'); ?>
						</a>
                    </div>
                    <div class="info-action-wp">
                        <div class="property-detailes">
                            
                            <?php if(isset($property['property_title']) and trim($property['property_title'] ?? '')): ?>
                            <a class="detail p-title" href="<?php wpl_esc::url(wpl_property::get_property_link([], $property['data']['id'], $listing_target_page)); ?>">
								<span class="value"><?php wpl_esc::html($property['property_title']); ?></span>
							</a>
                            <?php endif; ?>
                            
                            <?php if(trim($details_string ?? '') != ''): ?>
                            <span class="detail p-types"><?php wpl_esc::e($details_string); ?></span>
                            <?php endif; ?>
                            
                            <?php if(trim($location_string ?? '') != ''): ?>
                            <span class="detail p-location"><?php wpl_esc::html($location_string); ?></span>
                            <?php endif; ?>
                            
                            <div class="detail p-add-date">
                                <span class="title"><?php wpl_esc::html_t('Add date'); ?> : </span>
                                <span class="value" title="<?php wpl_esc::attr_t('Visited times'); ?> : <?php wpl_esc::attr(wpl_property::get_property_stats_item($property['data']['id'], 'visit_time')) ?>">
									<?php wpl_esc::html($property['data']['add_date']); ?>
								</span>
                            </div>

                            <?php if(!$property['data']['finalized']): ?>
                            <div class="finilize-msg" id="pmanager_finalized_status<?php wpl_esc::attr($property['data']['id']); ?>">
                                <span><?php wpl_esc::html_t('Property is not finalized.'); ?></span>
                            </div>
                            <?php endif; ?>
							
                            <?php if ( !empty($property['data']['expired']) ): ?>
                            <div class="expire-msg" id="pmanager_expired_status<?php wpl_esc::attr($property['data']['id']); ?>">
                                <span>
									<?php wpl_esc::html_t('Expired'); ?>
									<a href="javascript:void(0)" onclick="return wpl_revert_expire( this.id );" id="revert_<?php wpl_esc::attr($property['data']['id']); ?>" data-id="<?php wpl_esc::attr($property['data']['id']); ?>">
										<?php wpl_esc::html_t('Click here to revert it!'); ?>
									</a>
								</span>								
                            </div>
                            <?php endif; ?>

                        </div>
                        <div class="property-actions">
                            <?php /** load position2 **/ wpl_activity::load_position('pmanager_position2', array('property_data'=>$property)); ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <?php if(isset($this->pagination->max_page) and $this->pagination->max_page > 1): ?>
        <div class="pagination-wp">
            <?php wpl_esc::e($this->pagination->show()); ?>
        </div>
        <?php endif; ?>
    </div>
    <div id="wpl_listing_stats_div" class="wpl_hidden_element"></div>
    <footer>
        <div class="logo"></div>
    </footer>
</div>