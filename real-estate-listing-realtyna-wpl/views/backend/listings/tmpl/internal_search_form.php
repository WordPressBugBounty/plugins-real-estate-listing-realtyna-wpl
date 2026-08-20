<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<style>
	.wpl_listing_manager_search_form_element_cnt {
		margin-bottom: 10px;
	}
</style>
<div class="panel-wp lm-search-form-wp">
    <h3><?php wpl_esc::html_t('Search'); ?></h3>

    <div id="wpl_listing_manager_search_form_cnt" class="panel-body">
        <div class="pwizard-panel">
            <div class="pwizard-section">
                <div class="prow">
					<?php if(wpl_settings::is_mls_on_the_fly() && $this->kind == 0 && wpl_global::get_client() == 1): ?>
					<?php $current_value = stripslashes(wpl_request::getVar('sf_select_source', 'RF')); ?>
					<div class="wpl_listing_manager_search_form_element_cnt">
						<select name="sf_select_source" id="sf_select_source">
							<option value=""><?php esc_html_e('Property Source', 'real-estate-listing-realtyna-wpl'); ?></option>
							<option value="rf" <?php echo($current_value == 'rf' ? 'selected="selected"' : ''); ?>><?php esc_html_e('MLS On The Fly™', 'real-estate-listing-realtyna-wpl'); ?></option>
							<option value="wpl" <?php echo($current_value == 'wpl' ? 'selected="selected"' : ''); ?>><?php esc_html_e('WPL', 'real-estate-listing-realtyna-wpl'); ?></option>
						</select>
					</div>
					<?php endif; ?>
                    <?php $current_value = stripslashes(wpl_request::getVar('sf_select_listing', '-1')); ?>
                    <div class="wpl_listing_manager_search_form_element_cnt">
                        <select name="sf_select_listing" id="sf_select_listing">
                            <option value="-1"><?php wpl_esc::html_t('Listing'); ?></option>
                            <?php foreach ($this->listings as $listing): ?>
                                <option value="<?php wpl_esc::attr($listing['id']); ?>" <?php wpl_esc::attr_str_if($current_value == $listing['id'], 'selected', 'selected'); ?>>
									<?php wpl_esc::html_t($listing['name']); ?>
								</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <?php $current_value = stripslashes(wpl_request::getVar('sf_select_property_type', '-1')); ?>
                    <div class="wpl_listing_manager_search_form_element_cnt">
                        <select name="sf_select_property_type" id="sf_select_property_type">
                            <option value="-1"><?php wpl_esc::html_t('Property Type'); ?></option>
                            <?php foreach ($this->property_types as $property_type): ?>
                                <option value="<?php wpl_esc::attr($property_type['id']); ?>" <?php wpl_esc::attr_str_if($current_value == $property_type['id'], 'selected', 'selected'); ?>>
									<?php wpl_esc::html_t($property_type['name']); ?>
								</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <?php if((wpl_users::is_administrator() or wpl_users::is_broker()) and count($this->users)): ?>
                        <?php $current_value = stripslashes(wpl_request::getVar('sf_select_user_id', '-1')); ?>
                        <div class="wpl_listing_manager_search_form_element_cnt">
                            <select name="sf_select_user_id" id="sf_select_user_id">
                                <option value="-1"><?php wpl_esc::html_t('User'); ?></option>
                                <?php foreach($this->users as $user): ?>
                                    <option value="<?php wpl_esc::attr($user->ID); ?>" <?php wpl_esc::attr_str_if($current_value == $user->ID, 'selected', 'selected'); ?>><?php wpl_esc::html_t($user->user_login); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <?php $current_value = stripslashes(wpl_request::getVar('sf_select_confirmed', '-1')); ?>
                    <div class="wpl_listing_manager_search_form_element_cnt">
                        <select name="sf_select_confirmed" id="sf_select_confirmed">
                            <option value="-1"><?php wpl_esc::html_t('Confirm Status'); ?></option>
                            <option value="1" <?php wpl_esc::attr_str_if($current_value == '1', 'selected', 'selected'); ?>><?php wpl_esc::html_t('Confirmed'); ?></option>
                            <option value="0" <?php wpl_esc::attr_str_if($current_value == '0', 'selected', 'selected'); ?>><?php wpl_esc::html_t('Unconfirmed'); ?></option>
                        </select>
                    </div>

                    <?php $current_value = stripslashes(wpl_request::getVar('sf_select_finalized', '-1')); ?>
                    <div class="wpl_listing_manager_search_form_element_cnt">
                        <select name="sf_select_finalized" id="sf_select_finalized">
                            <option value="-1"><?php wpl_esc::html_t('Finalize Status'); ?></option>
                            <option value="1" <?php wpl_esc::attr_str_if($current_value == '1', 'selected', 'selected'); ?>><?php wpl_esc::html_t('Finalized'); ?></option>
                            <option value="0" <?php wpl_esc::attr_str_if($current_value == '0', 'selected', 'selected'); ?>><?php wpl_esc::html_t('Unfinalized'); ?></option>
                        </select>
                    </div>
                    <?php $current_value = stripslashes(wpl_request::getVar('sf_select_mls_id', '')); ?>
                    <div class="wpl_listing_manager_search_form_element_cnt">
                        <input type="text" name="sf_select_mls_id" id="sf_select_mls_id" value="<?php wpl_esc::attr($current_value); ?>"
                               placeholder="<?php wpl_esc::html_t('Listing ID'); ?>"/>
                    </div>

                    <?php $current_value = stripslashes(wpl_request::getVar('sf_locationtextsearch', '')); ?>
                    <div class="wpl_listing_manager_search_form_element_cnt">
                        <input type="text" name="sf_locationtextsearch" id="sf_locationtextsearch"
                               value="<?php wpl_esc::attr($current_value); ?>"
                               placeholder="<?php wpl_esc::html_t('Location'); ?>"/>
                    </div>

                    <?php $current_value = stripslashes(wpl_request::getVar('sf_textsearch_textsearch', '')); ?>
                    <div class="wpl_listing_manager_search_form_element_cnt">
                        <input type="text" name="sf_textsearch_textsearch" id="sf_textsearch_textsearch"
                               value="<?php wpl_esc::attr($current_value); ?>"
                               placeholder="<?php wpl_esc::html_t('Text Search'); ?>"/>
                    </div>
					<?php do_action('wpl_view/backend/listings/tmpl/internal_search_form', $this); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="prow wpl-btn-wp">
        <div class="wpl_listing_manager_search_form_element_cnt">
            <button class="wpl-button button-1" onclick="wpl_search_listings();"><?php wpl_esc::html_t('Search'); ?></button>
            <span class="wpl_reset_button" onclick="wpl_reset_listings();"><?php wpl_esc::html_t('Reset'); ?></span>
        </div>
    </div>
</div>
<script type="text/javascript">
/**
 * Enter submits the search.
 *
 * This panel is a <div>, not a <form> - the Search button just calls
 * wpl_search_listings() - so pressing Enter in a field did nothing at all and the
 * only way to run a search was to reach for the mouse. Bound on the panel so it
 * also covers fields added later, and restricted to text-ish inputs so it cannot
 * hijack Enter inside a select or a textarea.
 */
wplj(function () {
    wplj('#wpl_listing_manager_search_form_cnt').on('keydown', 'input', function (e) {
        if (e.which !== 13 && e.keyCode !== 13) return;
        var type = (this.type || '').toLowerCase();
        if (type !== 'text' && type !== 'search' && type !== 'number' && type !== 'email') return;

        e.preventDefault();
        if (typeof wpl_search_listings === 'function') wpl_search_listings();
    });
});
</script>