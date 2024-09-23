<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
$this->_wpl_import($this->tpl_path . '.scripts.modify_js');
?>
<div class="fanc-content size-width-2" id="wpl_activity_modify_container">
    <h2>
		<?php if($this->activity_id): ?>
			<?php wpl_esc::html_t('Modify Activity'); ?> => <?php wpl_esc::html(!empty($this->activity_data->title) ? $this->activity_data->title : $this->activity_data->activity) ?>
		<?php else: ?>
			<?php wpl_esc::html_t('Add Activity'); ?>
		<?php endif; ?>
	</h2>
    <div class="fanc-body">
        <div class="fanc-row fanc-button-row-2">
            <span class="ajax-inline-save" id="wpl_activity_modify_ajax_loader"></span>
            <input class="wpl-button button-1" type="button" value="<?php wpl_esc::html_t('Save'); ?>" id="wpl_activity_submit_button" onclick="wpl_save_activity();" />
        </div>
        <div class="col-wp">
            <div class="col-fanc-left">
                <div class="fanc-row fanc-inline-title">
                    <?php wpl_esc::html_t('Activity Information'); ?>
                </div>
                <div class="fanc-row">
                    <label for="wpl_title"><?php wpl_esc::html_t('Title'); ?>: </label>
                    <input class="text_box" name="info[title]" type="text" id="wpl_title" value="<?php wpl_esc::attr($this->activity_data->title ?? ''); ?>" />
                </div>
                <div class="fanc-row">
                    <label id="layout_select_label" for="wpl_layout"><?php wpl_esc::html_t('Layout'); ?></label>
                    <select id="wpl_layout" class="text_box" name="info[layout]">
                        <option value="">-----</option>
                        <?php foreach($this->activity_layouts as $activity_layout): ?>
                        <option <?php wpl_esc::attr_str_if(isset($this->activity_raw_name[1]) and $this->activity_raw_name[1] == $activity_layout, 'selected', 'selected'); ?> value="<?php wpl_esc::attr($activity_layout); ?>">
							<?php wpl_esc::html($activity_layout); ?>
						</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="fanc-row">
                    <label for="wpl_position"><?php wpl_esc::html_t('Position'); ?></label>
                    <input class="text_box" name="info[position]" type="text" id="wpl_position" value="<?php wpl_esc::attr($this->activity_data->position ?? '') ?>" />
                </div>
                <div class="fanc-row">
                    <label for="wpl_show_title<?php wpl_esc::attr($this->activity_id); ?>">
						<?php wpl_esc::html_t('Show Title'); ?>
					</label>
                    <select class="text_box" id="wpl_show_title<?php wpl_esc::attr($this->activity_id); ?>" name="info[show_title]">
                        <option value="1" <?php wpl_esc::attr_str_if(isset($this->activity_data->show_title) and $this->activity_data->show_title == '1', 'selected', 'selected'); ?>>
							<?php wpl_esc::html_t('Yes'); ?>
						</option>
                        <option value="0" <?php wpl_esc::attr_str_if(isset($this->activity_data->show_title) and $this->activity_data->show_title == '0', 'selected', 'selected'); ?>>
							<?php wpl_esc::html_t('No'); ?>
						</option>
                    </select>
                </div>
                <div class="fanc-row">
                    <label for="wpl_enabled<?php wpl_esc::attr($this->activity_id); ?>">
						<?php wpl_esc::html_t('Status'); ?>
					</label>
                    <select class="text_box" id="wpl_enabled<?php wpl_esc::attr($this->activity_id); ?>" name="info[enabled]">
                        <option value="1" <?php wpl_esc::attr_str_if(isset($this->activity_data->enabled) and $this->activity_data->enabled == '1', 'selected', 'selected'); ?>>
							<?php wpl_esc::html_t('Enabled'); ?>
						</option>
                        <option value="0" <?php wpl_esc::attr_str_if(isset($this->activity_data->enabled) and $this->activity_data->enabled == '0', 'selected', 'selected'); ?>>
							<?php wpl_esc::html_t('Disabled'); ?>
						</option>
                    </select>
                </div>
                <div class="fanc-row">
                    <label for="wpl_client<?php wpl_esc::attr($this->activity_id); ?>">
						<?php wpl_esc::html_t('Site Section'); ?>
					</label>
                    <select class="text_box" id="wpl_client<?php wpl_esc::attr($this->activity_id); ?>" name="info[client]">
                        <option value="2" <?php wpl_esc::attr_str_if(isset($this->activity_data->client) and $this->activity_data->client == '2', 'selected', 'selected'); ?>>
							<?php wpl_esc::html_t('Both'); ?>
						</option>
                        <option value="1" <?php wpl_esc::attr_str_if(isset($this->activity_data->client) and $this->activity_data->client == '1', 'selected', 'selected'); ?>>
							<?php wpl_esc::html_t('Backend'); ?>
						</option>
                        <option value="0" <?php wpl_esc::attr_str_if(isset($this->activity_data->client) and $this->activity_data->client == '0', 'selected', 'selected'); ?>>
							<?php wpl_esc::html_t('Frontend'); ?>
						</option>
                    </select>
                </div>
                <div class="fanc-row">
                    <label for="wpl_index"><?php wpl_esc::html_t('Index'); ?></label>
                    <input class="text_box" name="info[index]" type="text" id="wpl_index" value="<?php wpl_esc::attr($this->activity_data->index ?? '99.00'); ?>" />
                </div>
                <div class="wpl_show_message<?php wpl_esc::attr($this->activity_id); ?>"></div>
            </div>
            <div class="col-fanc-right">
                <div class="fanc-row fanc-inline-title">
                    <?php wpl_esc::html_t('Options'); ?>
                </div>
                <div id="options_section">
                <?php
                    /** including activity option form **/
                    $options_form = wpl_activity::get_activity_option_form($this->activity_raw_name[0]);
                    if($options_form) include($options_form);
                ?>
                </div>
            </div>
        </div>
        <div class="col-wp">
            <div class="col-fanc-bottom wpl-fanc-full-row">
                <div class="fanc-row fanc-inline-title">
                    <?php wpl_esc::html_t('Page Association'); ?>
                </div>
                <div class="fanc-row">
                    <?php if(!wpl_global::check_addon('pro')): ?>
                    <p><?php wpl_esc::html_t('The PRO Add-on must be installed for this feature.'); ?></p>
                    <?php else: ?>
						<label for="wpl_page_association<?php wpl_esc::attr($this->activity_id); ?>">
							<?php wpl_esc::html_t('Association'); ?>
						</label>
						<select class="select_box" id="wpl_page_association<?php wpl_esc::attr($this->activity_id); ?>" name="info[association_type]" onchange="wpl_page_association_selected('<?php wpl_esc::attr($this->activity_id); ?>');">
							<option value="1" <?php wpl_esc::attr_str_if(isset($this->activity_data->association_type) and $this->activity_data->association_type == 1, 'selected', 'selected'); ?>>
								<?php wpl_esc::html_t('Show on all pages'); ?>
							</option>
							<option value="2" <?php wpl_esc::attr_str_if(isset($this->activity_data->association_type) and $this->activity_data->association_type == 2, 'selected', 'selected'); ?>>
								<?php wpl_esc::html_t('Show on selected pages'); ?>
							</option>
							<option value="3" <?php wpl_esc::attr_str_if(isset($this->activity_data->association_type) and $this->activity_data->association_type == 3, 'selected', 'selected'); ?>>
								<?php wpl_esc::html_t('Hide on selected pages'); ?>
							</option>
							<option value="0" <?php wpl_esc::attr_str_if(isset($this->activity_data->association_type) and $this->activity_data->association_type == 0, 'selected', 'selected'); ?>>
								<?php wpl_esc::html_t('Hide on all pages'); ?>
							</option>
						</select>
						<div class="wpl-page-list wpl_activity_pages_container" style="<?php if(!isset($this->activity_data->association_type) or (isset($this->activity_data->association_type) and in_array($this->activity_data->association_type, array(0,1)))) wpl_esc::e('display: none;'); ?>">
							<?php
							$pages = wpl_global::get_wp_pages();
							if(count($pages)):
							?>
								<?php foreach ($pages as $page): ?>
									<div class="wpl-page wpl_activity_page_container" id="wpl_activity_page_container<?php wpl_esc::attr($page->ID); ?>">
										<input type="checkbox" class="wpl_activity_page_selectbox" name="associations[<?php wpl_esc::attr($page->ID); ?>]" id="wpl_activity_page_checkbox<?php wpl_esc::attr($page->ID); ?>" <?php wpl_esc::attr_str_if(isset($this->activity_data->associations) and strpos($this->activity_data->associations ?? '', '['.$page->ID.']') !== false, 'checked', 'checked'); ?>
										<label for="wpl_activity_page_checkbox<?php wpl_esc::attr($page->ID); ?>"><?php wpl_esc::html($page->post_title); ?></label>
									</div>
								<?php endforeach; ?>
							<?php else: wpl_esc::html_t('No pages'); endif; ?>
						</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-wp">
            <div class="col-fanc-bottom wpl-fanc-full-row">
                <div class="fanc-row fanc-inline-title">
                    <?php wpl_esc::html_t('Accesses'); ?>
                </div>
                <div class="fanc-row">
                    <?php if(!wpl_global::check_addon('membership')): ?>
                    <p><?php wpl_esc::html_t('Membership Add-on must be installed for this!'); ?></p>
                    <?php else: ?>
                    <label for="accesses<?php wpl_esc::attr($this->activity_id); ?>"><?php wpl_esc::html_t('Viewable by'); ?></label>
                    <select id="accesses<?php wpl_esc::attr($this->activity_id); ?>" name="info[access_type]" onchange="wpl_activity_change_accesses(this.value, <?php wpl_esc::attr($this->activity_id); ?>);">
                        <option value="2"><?php wpl_esc::html_t('All Users'); ?></option>
                        <option value="1" <?php wpl_esc::attr_str_if( trim($this->activity_data->access_type ?? '') == 1, 'selected', 'selected'); ?>>
							<?php wpl_esc::html_t('Selected Users'); ?>
						</option>
                    </select>
                    <?php endif; ?>
                    <div class="wpl_flex_accesses_cnt" id="accesses_cnt<?php wpl_esc::attr($this->activity_id); ?>" style="<?php if(!isset($this->activity_data->access_type) or (isset($this->activity_data->access_type) and $this->activity_data->access_type == 2)) wpl_esc::e('display: none;'); ?>">
                        <ul id="accesses_ul<?php wpl_esc::attr($this->activity_id); ?>" class="wpl_accesses_ul">
                            <?php
                            $accesses = ( trim($this->activity_data->accesses ?? '' ) ) ? explode(',', $this->activity_data->accesses) : array();
                            foreach($this->memberships as $membership):
                            ?>
                                <li><input name="accesses[<?php wpl_esc::attr($membership->id); ?>]" id="wpl_activity_membership_checkbox<?php wpl_esc::attr($membership->id); ?>" type="checkbox" value="1" <?php if(!isset($this->activity_data->accesses) or ( trim($this->activity_data->accesses ?? '' ) == '') or in_array($membership->id, $accesses)) wpl_esc::e('checked="checked"'); ?> />
									<label class="wpl_specific_label" for="wpl_activity_membership_checkbox<?php wpl_esc::attr($membership->id); ?>">&nbsp;
										<?php wpl_esc::html_t($membership->membership_name); ?>
									</label>
								</li>
							<?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="info[activity]" value="<?php wpl_esc::attr($this->activity_raw_name[0]); ?>" />
        <?php if($this->activity_id): ?>
        <input type="hidden" name="info[activity_id]" value="<?php wpl_esc::attr($this->activity_id); ?>" />
        <?php endif; ?>
    </div>
</div>