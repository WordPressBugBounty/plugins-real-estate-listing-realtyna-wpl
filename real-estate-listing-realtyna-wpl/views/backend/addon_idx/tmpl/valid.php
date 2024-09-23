<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import($this->tpl_path.'.scripts.js');
?>

<script src="https://js.stripe.com/v3/"></script>

<div class="wpl-idx-addon wrap wpl-wp settings-wp">
    <div class="wpl-idx-wizard-main wpl-idx-valid">
        <header>
            <div id="icon-settings" class="icon48"></div>
            <h2><?php wpl_esc::html_t('Organic IDX / Registration Wizard'); ?></h2>
        </header>
        <section class="sidebar-wp">
            <div class="panel-wp">
                <h3><?php wpl_esc::html_t("Organic IDX Full Version"); ?></h3>
                <div class="panel-body">
                    <div class="wpl-idx-wizard">
                        <div class="wpl-wizard-tabs">
                            <ul class="wpl-row">
                                <li id="wpl-idx-wizard-step1" class="wpl-small-4 wpl-medium-4 wpl-large-4 wpl-column current" >
                                    <span class="number">1</span>
                                    <span><?php wpl_esc::html_t('Sign Up'); ?></span>
                                </li>
                                <li id="wpl-idx-wizard-step2" class="wpl-small-4 wpl-medium-4 wpl-large-4 wpl-column" >
                                    <span class="number">2</span>
                                    <span><?php wpl_esc::html_t('Choose MLS'); ?></span>
                                </li>
                                <li id="wpl-idx-wizard-step3" class="wpl-small-4 wpl-medium-4 wpl-large-4 wpl-column" >
                                    <span class="number">3</span>
                                    <span><?php wpl_esc::html_t('Success'); ?></span>
                                </li>
                            </ul>
                        </div>
                        <div class="wpl-wizard-sections">
                            <div id="wpl-wizard-section1" class="wpl-wizard-section wpl-idx-sign-up wpl-idx-form current">
                                <div class="wpl-idx-form-element">
                                    <input id="name" name="name" type="text" placeholder="Name"  />
                                    <span class="wpl-idx-icon user-icon"></span>
                                </div>
                                <div class="wpl-idx-form-element">
                                    <input id="email" name="email" type="email" placeholder="Email" />
                                    <span class="wpl-idx-icon email-icon"></span>
                                </div>
                                <div class="wpl-idx-form-element">
                                    <input id="phone" name="phone" type="tel" placeholder="Phone" />
                                    <span class="wpl-idx-icon phone-icon"></span>
                                </div>
                                <div class="wpl-idx-wizard-navigation clearfix">
                                    <span class="loading"></span>
                                    <a class="btn next" onclick="wpl_idx_form_validation('.wpl-idx-sign-up','registration');"><?php wpl_esc::html_t('Next');?></a>
                                </div>
                            </div>
                            <div id="wpl-wizard-section2" class="wpl-wizard-section wpl-idx-choose-mls wpl-idx-table clearfix">
                                <div class="wpl-row wpl-expanded wpl-idx-table-tools">
                                    <div class="wpl-small-12 wpl-medium-6 wpl-large-4 wpl-column wpl-idx-form">
                                        <div class="wpl-idx-form-element">
                                            <input id="wpl-idx-search-mls-provider" value="" type="text" placeholder="<?php wpl_esc::html_t('Search Your MLS'); ?>">
                                            <span class="wpl-idx-icon search-icon"></span>
                                        </div>
                                    </div>
                                    <div class="wpl-small-12 wpl-medium-6 wpl-large-6 wpl-large-offset-2 wpl-column wpl-idx-add-mls-request">
                                        <a class="btn" href="#wpl_request_mls_fancybox_cnt" data-realtyna-lightbox data-realtyna-lightbox-opts="reloadPage:false" data-realtyna-href="#wpl_request_mls_fancybox_cnt">
											<?php wpl_esc::html_t('My MLS is not in the list! Request MLS'); ?>
										</a>
                                        <div id="wpl_request_mls_fancybox_cnt" class="wpl_hidden_element">
                                            <div class="fanc-content size-width-1">
                                                <h2><?php wpl_esc::html_t('Request MLS'); ?></h2>
                                                <div class="fanc-body">
                                                    <div class="fanc-row">
                                                        <label for="wpl_location_name"><?php wpl_esc::html_t('MLS Provider'); ?></label>
                                                        <input class="text_box" type="text" id="wpl_request_mls_provider" value="" autocomplete="off" />
                                                    </div>
                                                    <div class="fanc-row">
                                                        <label for="wpl_location_abbr"><?php wpl_esc::html_t('State'); ?></label>
                                                        <input class="text_box" type="text" id="wpl_request_mls_state" value="" autocomplete="off" />
                                                    </div>
                                                    <div class="fanc-row fanc-button-row">
                                                        <input class="wpl-button button-1" type="submit" id="wpl_submit" value="<?php wpl_esc::html_t('Save'); ?>" onclick="wpl_idx_request_mls();" />
                                                        <span class="ajax-inline-save" id="wpl_ajax_loader"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <table id="wpl-idx-all-mls-providers" class="wpl-idx-addon-table page">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <div class="message"><?php wpl_esc::html_t('No MLS Provider is Found!');?></div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="clearfix"></div>
                                <div class="wpl-idx-wizard-navigation clearfix">
                                    <a class="btn next" onclick="wpl_idx_save();"><?php wpl_esc::html_t('Next');?></a>
                                    <a class="btn back" onclick="wpl_idx_back_step('register');"><?php wpl_esc::html_t('Back');?></a>
                                </div>
                            </div>
                            <div id="wpl-wizard-section3" class="wpl-wizard-section wpl-idx-thank-you">
                                Thank you for registration.
                                We will be in touch soon.
                            </div>
                        </div>
                        <div class="wpl_show_message_idx"></div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>